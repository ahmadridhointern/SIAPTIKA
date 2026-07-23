<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentRequest;
use App\Models\Activity;
use App\Services\StorageServiceInterface;
use Illuminate\Http\RedirectResponse;

/**
 * DocumentController
 *
 * Bertanggung jawab untuk menangani upload dokumen arsip ke Supabase Storage.
 * Controller ini TIDAK menyimpan metadata ke database (akan ditambahkan di Sprint 3 tahap berikutnya).
 *
 * Prinsip: Single Responsibility — controller ini hanya mengurusi operasi dokumen,
 * terpisah dari ActivityController yang mengurusi data kegiatan.
 */
class DocumentController extends Controller
{
    /**
     * DocumentController menggunakan Dependency Injection untuk storage service.
     * Controller tidak mengetahui detail implementasi Supabase — hanya mengenal interface.
     */
    public function __construct(
        private readonly StorageServiceInterface $storage,
    ) {}

    /**
     * Upload dokumen ke Supabase Storage.
     *
     * Alur:
     * 1. Validasi request (dilakukan oleh StoreDocumentRequest sebelum method ini dipanggil)
     * 2. Upload file ke Storage dengan path: documents/{activity_id}/{uuid}.{ext}
     * 3. Redirect kembali ke halaman detail kegiatan dengan pesan sukses/error
     *
     * @param  StoreDocumentRequest  $request
     * @param  Activity              $activity  Route model binding
     * @return RedirectResponse
     */
    public function store(StoreDocumentRequest $request, Activity $activity): RedirectResponse
    {
        try {
            // Upload file ke Supabase Storage
            // Path: documents/{activity_id}/{uuid}.{ext}
            $path = $this->storage->upload(
                file:      $request->file('file'),
                directory: "documents/{$activity->id}",
            );

            return redirect()
                ->route('admin.activities.show', $activity)
                ->with('success', 'Dokumen berhasil diunggah ke arsip.');

        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->route('admin.activities.show', $activity)
                ->with('error', 'Gagal mengunggah dokumen. Silakan coba lagi.');
        }
    }
}
