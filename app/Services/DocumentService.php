<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Document;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

/**
 * DocumentService
 *
 * Bertanggung jawab untuk mengorchestrasi proses upload & update dokumen:
 *   1. Upload/Ganti file di Supabase Storage
 *   2. Simpan/Perbarui metadata di database dalam DB::transaction
 *   3. Rollback file dari Storage jika transaksi database gagal
 *   4. Hapus file lama dari Storage setelah penggantian berkas berhasil
 *
 * Prinsip: Single Responsibility — service ini hanya mengurusi
 * operasi bisnis dokumen (upload, update & persist), terpisah dari HTTP layer.
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
            try {
                $this->storage->delete($path);
            } catch (\Throwable $deleteException) {
                report($deleteException);
            }

            throw $e;
        }
    }

    /**
     * Perbarui metadata dokumen dan/atau ganti file di Storage secara atomik.
     *
     * @param  Document      $document      Record dokumen yang diperbarui
     * @param  string        $documentType  Jenis dokumen baru
     * @param  UploadedFile|null $newFile   File baru (opsional)
     * @return Document                     Record dokumen yang telah diperbarui
     *
     * @throws \Throwable  Jika upload Storage atau transaksi DB gagal
     */
    public function update(
        Document $document,
        string $documentType,
        ?UploadedFile $newFile = null,
    ): Document {
        // Kasus 1: File tidak diganti, hanya jenis dokumen yang diubah
        if ($newFile === null) {
            return DB::transaction(function () use ($document, $documentType): Document {
                $document->update([
                    'document_type' => $documentType,
                ]);

                return $document;
            });
        }

        // Kasus 2: File diganti
        // Dapatkan path relatif file lama SEBELUM $document di-update di DB
        $oldPath = self::extractStoragePath($document->file_url);

        // 1. Upload file baru ke Storage
        $newPath = $this->storage->upload(
            file:      $newFile,
            directory: "documents/{$document->activity_id}",
        );

        try {
            // 2. Perbarui metadata di database dalam transaksi
            $updatedDocument = DB::transaction(function () use ($document, $documentType, $newFile, $newPath): Document {
                $document->update([
                    'document_type' => $documentType,
                    'file_name'     => $newFile->getClientOriginalName(),
                    'file_url'      => $this->storage->url($newPath),
                ]);

                return $document;
            });

            // 3. Hapus file lama dari Storage setelah DB berhasil di-update
            if ($oldPath) {
                try {
                    $this->storage->delete($oldPath);
                } catch (\Throwable $deleteOldException) {
                    report($deleteOldException);
                }
            }

            return $updatedDocument;

        } catch (\Throwable $e) {
            // Rollback: Hapus file BARU dari Storage jika transaksi DB gagal
            try {
                $this->storage->delete($newPath);
            } catch (\Throwable $rollbackException) {
                report($rollbackException);
            }

            throw $e;
        }
    }

    /**
     * Ekstrak path relatif storage dari file_url.
     * Mendukung format URL Supabase S3 (/storage/v1/s3/{bucket}/...)
     * maupun format REST public API (/storage/v1/object/public/{bucket}/...).
     *
     * Contoh output: "documents/2/uuid.jpg"
     */
    public static function extractStoragePath(string $fileUrl): ?string
    {
        // Format 1: /storage/v1/(s3|object/public)/{bucket}/(path)
        if (preg_match('#/storage/v1/(?:s3|object/public)/[^/]+/(.+)$#', $fileUrl, $matches)) {
            return $matches[1];
        }

        // Format 2 Fallback: ambil dari /documents/ (nama bucket) ke kanan
        if (preg_match('#/(documents/.+)$#', $fileUrl, $matches)) {
            return $matches[1];
        }

        // Format 3 Fallback: jika sudah berupa relative path documents/...
        if (str_starts_with($fileUrl, 'documents/')) {
            return $fileUrl;
        }

        return null;
    }
}
