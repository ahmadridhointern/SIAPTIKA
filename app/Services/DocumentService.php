<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Document;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

/**
 * DocumentService
 *
 * Bertanggung jawab untuk mengorchestrasi proses upload dokumen:
 *   1. Upload file ke Supabase Storage
 *   2. Simpan metadata ke database dalam DB::transaction
 *   3. Rollback file dari Storage jika transaksi database gagal
 *
 * Prinsip: Single Responsibility — service ini hanya mengurusi
 * operasi bisnis dokumen (upload + persist), terpisah dari HTTP layer.
 */
class DocumentService
{
    /**
     * DocumentService bergantung pada StorageServiceInterface melalui DI.
     * Tidak mengenal detail implementasi Supabase — hanya mengenal kontrak.
     */
    public function __construct(
        private readonly StorageServiceInterface $storage,
    ) {}

    /**
     * Upload file ke Storage dan simpan metadata-nya ke database secara atomik.
     *
     * Alur:
     *   1. Upload file ke Supabase Storage → dapatkan path
     *   2. Buka DB::transaction → Document::create()
     *   3. Jika DB gagal → rollback otomatis + hapus file dari Storage
     *   4. Return Document yang berhasil dibuat
     *
     * @param  UploadedFile  $file          File yang diupload
     * @param  Activity      $activity      Kegiatan yang terkait
     * @param  string        $documentType  Jenis dokumen: surat|notulen|dokumentasi
     * @return Document                     Record dokumen yang baru dibuat
     *
     * @throws \Throwable  Jika upload Storage atau transaksi DB gagal
     */
    public function upload(
        UploadedFile $file,
        Activity $activity,
        string $documentType,
    ): Document {
        // Langkah 1: Upload file ke Supabase Storage
        // Dilakukan sebelum transaksi DB karena Storage tidak mendukung rollback atomik.
        $path = $this->storage->upload(
            file:      $file,
            directory: "documents/{$activity->id}",
        );

        try {
            // Langkah 2: Simpan metadata dalam transaksi database
            $document = DB::transaction(function () use ($file, $activity, $documentType, $path): Document {
                return Document::create([
                    'activity_id'   => $activity->id,
                    'document_type' => $documentType,
                    'file_name'     => $file->getClientOriginalName(),
                    'file_url'      => $this->storage->url($path),
                ]);
            });

            return $document;

        } catch (\Throwable $e) {
            // Langkah 3: Rollback — hapus file dari Storage agar tidak ada orphan file
            // Jika delete juga gagal, tetap log dan lempar exception asli.
            try {
                $this->storage->delete($path);
            } catch (\Throwable $deleteException) {
                report($deleteException);
            }

            // Lempar exception asli ke controller untuk ditangani
            throw $e;
        }
    }
}
