<?php

namespace App\Console\Commands;

use App\Services\StorageServiceInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * StoragePing
 *
 * Artisan command untuk memverifikasi koneksi ke Supabase Storage.
 * Digunakan untuk health check dan debugging konfigurasi.
 *
 * Penggunaan: php artisan storage:ping
 */
class StoragePing extends Command
{
    protected $signature   = 'storage:ping';
    protected $description = 'Verifikasi koneksi ke Supabase Storage';

    public function handle(StorageServiceInterface $storage): int
    {
        $this->info('🔍 Memeriksa koneksi ke Supabase Storage...');
        $this->newLine();

        // Tampilkan konfigurasi (tanpa kredensial sensitif)
        $this->line('  Endpoint : ' . (config('filesystems.disks.supabase.endpoint') ?: '<tidak dikonfigurasi>'));
        $this->line('  Bucket   : ' . (config('filesystems.disks.supabase.bucket') ?: '<tidak dikonfigurasi>'));
        $this->line('  Region   : ' . (config('filesystems.disks.supabase.region') ?: '<tidak dikonfigurasi>'));
        $this->line('  Key      : ' . (config('filesystems.disks.supabase.key') ? str_repeat('*', 20) . ' (tersedia)' : '<kosong — belum dikonfigurasi>'));
        $this->newLine();

        try {
            // Coba list files
            Storage::disk('supabase')->files('/');
            $this->info('  ✅ Koneksi awal berhasil!');

            // Uji upload file kecil
            $testPath = '_ping/connection-test-' . now()->format('YmdHis') . '.txt';
            $this->line('  Mencoba upload file uji: ' . $testPath);

            Storage::disk('supabase')->put($testPath, 'SIAPTIKA storage ping — ' . now()->toIso8601String());
            $this->info('  ✅ Upload berhasil.');

            // Konfirmasi file ada
            $exists = Storage::disk('supabase')->exists($testPath);
            $this->info('  ✅ File terkonfirmasi ada: ' . ($exists ? 'Ya' : 'Tidak'));

            // Hapus file uji
            Storage::disk('supabase')->delete($testPath);
            $this->info('  ✅ File uji dihapus.');

            $this->newLine();
            $this->info('🎉 Supabase Storage terhubung & siap digunakan!');
            return self::SUCCESS;

        } catch (\Throwable $e) {
            $this->newLine();
            $this->error('  ❌ Koneksi gagal!');
            $this->error('  Detail Error: ' . $e->getMessage());
            $this->newLine();
            $this->warn('  💡 Langkah Penyelesaian:');
            $this->warn('  1. Buka Supabase Dashboard → Project Settings → Storage.');
            $this->warn('  2. Buat "S3 Access Key" baru (New Access Key).');
            $this->warn('  3. Salin "Access Key ID" ke SUPABASE_STORAGE_KEY di .env.');
            $this->warn('  4. Salin "Secret Access Key" ke SUPABASE_STORAGE_SECRET di .env.');
            $this->warn('  5. Pastikan bucket "documents" sudah dibuat di Supabase Dashboard → Storage.');
            $this->warn('  6. Jalankan: php artisan config:clear && php artisan storage:ping');

            return self::FAILURE;
        }

    }
}
