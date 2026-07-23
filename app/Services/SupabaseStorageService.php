<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * SupabaseStorageService
 *
 * Implementasi StorageServiceInterface yang menggunakan Supabase Storage
 * melalui driver S3-compatible bawaan Laravel.
 *
 * Semua komunikasi dengan Supabase dilakukan melalui disk 'supabase'
 * yang dikonfigurasi di config/filesystems.php.
 *
 * Prinsip: Single Responsibility — kelas ini hanya bertanggung jawab
 * untuk operasi file storage ke Supabase.
 */
class SupabaseStorageService implements StorageServiceInterface
{
    /**
     * Nama disk Laravel yang dikonfigurasi untuk Supabase.
     */
    private const DISK = 'supabase';

    /**
     * Upload file ke Supabase Storage.
     *
     * Menghasilkan nama file unik menggunakan UUID untuk menghindari collision.
     * File disimpan di direktori yang ditentukan dengan visibilitas public.
     *
     * @param  UploadedFile  $file
     * @param  string        $directory  Misal: 'documents', 'notulen'
     * @return string                    Path file di storage (contoh: 'documents/uuid.pdf')
     *
     * @throws RuntimeException jika upload gagal
     */
    public function upload(UploadedFile $file, string $directory): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename  = Str::uuid() . '.' . $extension;
        $path      = $directory . '/' . $filename;

        $stored = Storage::disk(self::DISK)->put(
            $path,
            file_get_contents($file->getRealPath()),
        );

        if (! $stored) {
            throw new RuntimeException(
                'Gagal mengupload file ke Supabase Storage. Path: ' . $path
            );
        }

        return $path;
    }

    /**
     * Hapus file dari Supabase Storage.
     *
     * @param  string  $path  Path file di storage
     * @return bool
     */
    public function delete(string $path): bool
    {
        return Storage::disk(self::DISK)->delete($path);
    }

    /**
     * Ambil URL publik file dari Supabase Storage.
     *
     * Menghasilkan URL langsung ke file menggunakan endpoint Supabase.
     *
     * @param  string  $path  Path file di storage
     * @return string         URL publik
     */
    public function url(string $path): string
    {
        return Storage::disk(self::DISK)->url($path);
    }

    /**
     * Periksa apakah file ada di Supabase Storage.
     *
     * @param  string  $path
     * @return bool
     */
    public function exists(string $path): bool
    {
        return Storage::disk(self::DISK)->exists($path);
    }

    /**
     * Verifikasi koneksi ke Supabase Storage.
     *
     * Mencoba me-list file di root bucket. Jika tidak ada exception,
     * koneksi dianggap berhasil.
     *
     * @return bool  True jika koneksi berhasil, false jika gagal
     */
    public function ping(): bool
    {
        try {
            Storage::disk(self::DISK)->files('/');
            return true;
        } catch (\Throwable $e) {
            report($e);
            return false;
        }
    }
}
