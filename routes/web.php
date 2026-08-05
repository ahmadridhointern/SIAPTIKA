<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Employee\ActivityController as EmployeeActivityController;
use App\Http\Controllers\Employee\ArchiveController as EmployeeArchiveController;
use App\Http\Controllers\Employee\DashboardController as EmployeeDashboardController;
use App\Http\Controllers\Employee\DocumentController as EmployeeDocumentController;
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
    Route::get('/arsip', [\App\Http\Controllers\Admin\ArchiveController::class, 'index'])->name('archive.index');


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

/*
|--------------------------------------------------------------------------
| Employee Routes — Publik (tanpa autentikasi)
|--------------------------------------------------------------------------
*/
Route::prefix('pegawai')->name('employee.')->group(function () {
    Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])->name('dashboard');
    Route::get('/kegiatan', [EmployeeActivityController::class, 'index'])->name('activities.index');
    Route::get('/kegiatan/{activity}', [EmployeeActivityController::class, 'show'])->name('activities.show');
    Route::get('/arsip', [EmployeeArchiveController::class, 'index'])->name('archive.index');

    // Dokumen — view (inline) dan download saja; tidak ada upload/edit/hapus
    Route::get('/dokumen/{document}/view', [EmployeeDocumentController::class, 'show'])->name('documents.show');
    Route::get('/dokumen/{document}/download', [EmployeeDocumentController::class, 'download'])->name('documents.download');
});
