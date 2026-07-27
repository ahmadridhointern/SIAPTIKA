<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Redirect root ke halaman login
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Auth Routes — Hanya untuk guest (belum login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

/*
|--------------------------------------------------------------------------
| Admin Routes — Dilindungi AdminMiddleware
|--------------------------------------------------------------------------
*/
Route::middleware('admin.auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('activities', \App\Http\Controllers\Admin\ActivityController::class);

    // Upload dokumen arsip — nested di bawah kegiatan
    Route::post(
        'activities/{activity}/documents',
        [DocumentController::class, 'store']
    )->name('activities.documents.store');

    // Update dokumen arsip — edit jenis dokumen / ganti berkas
    Route::put(
        'documents/{document}',
        [DocumentController::class, 'update']
    )->name('documents.update');

    // View (preview) dokumen arsip — proxy via Laravel (header inline)
    Route::get(
        'documents/{document}/view',
        [DocumentController::class, 'show']
    )->name('documents.show');

    // Download dokumen arsip — proxy via Laravel (header attachment)
    Route::get(
        'documents/{document}/download',
        [DocumentController::class, 'download']
    )->name('documents.download');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
