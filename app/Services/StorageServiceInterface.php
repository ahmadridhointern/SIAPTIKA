<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

/**
 * StorageServiceInterface
 *
 * Kontrak abstrak untuk semua operasi penyimpanan file.
 * Controller bergantung pada interface ini, bukan pada implementasi konkret,
 * sehingga storage provider dapat diganti tanpa mengubah controller.
 *
 * Prinsip: Dependency Inversion (SOLID)
 */
interface StorageServiceInterface
{
    /**
     * Upload file ke storage dan kembalikan path publiknya.
     *
     * @param  UploadedFile  $file        File yang diupload
     * @param  string        $directory   Direktori tujuan di dalam bucket
     * @return string                     Path file di storage (relatif terhadap bucket)
     */
    public function upload(UploadedFile $file, string $directory): string;

    /**
     * Hapus file dari storage berdasarkan path-nya.
     *
     * @param  string  $path  Path file di storage
     * @return bool           True jika berhasil dihapus
     */
    public function delete(string $path): bool;

    /**
     * Ambil URL publik/signed untuk mengakses file.
     *
     * @param  string  $path  Path file di storage
     * @return string         URL yang dapat diakses
     */
    public function url(string $path): string;

    /**
     * Periksa apakah file ada di storage.
     *
     * @param  string  $path  Path file di storage
     * @return bool
     */
    public function exists(string $path): bool;

    /**
     * Verifikasi bahwa koneksi ke storage berfungsi dengan baik.
     * Digunakan untuk health check / diagnostics.
     *
     * @return bool  True jika koneksi berhasil
     */
    public function ping(): bool;
}
