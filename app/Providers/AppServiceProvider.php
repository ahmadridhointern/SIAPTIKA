<?php

namespace App\Providers;

use App\Services\StorageServiceInterface;
use App\Services\SupabaseStorageService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * Mendaftarkan binding antara StorageServiceInterface dan
     * implementasinya (SupabaseStorageService) ke dalam service container.
     *
     * Dengan binding ini, Controller yang menerima StorageServiceInterface
     * via Dependency Injection akan otomatis mendapatkan SupabaseStorageService.
     * Untuk mengganti provider storage, cukup ubah baris binding ini.
     */
    public function register(): void
    {
        $this->app->bind(
            StorageServiceInterface::class,
            SupabaseStorageService::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::defaultView('vendor.pagination.custom');
    }
}
