<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentRequest;
use App\Models\Activity;
use App\Services\DocumentService;
use Illuminate\Http\RedirectResponse;

/**
 * DocumentController
 *
 * Bertanggung jawab menangani request HTTP untuk upload dokumen arsip.
 * Seluruh logika bisnis (upload Storage + simpan DB) didelegasikan ke DocumentService.
 *
 * Prinsip: Thin Controller — controller hanya menerima request, mendelegasikan,
 * dan mengembalikan response. Tidak ada logika bisnis di sini.
 */
class DocumentController extends Controller
{
    /**
     * Dependency Injection: DocumentService menangani semua logika upload.
     */
    public function __construct(
        private readonly DocumentService $documentService,
    ) {}

    /**
     * Upload dokumen dan simpan metadata ke database.
     *
     * Validasi dilakukan otomatis oleh StoreDocumentRequest sebelum method ini dipanggil.
     * Alur lengkap ada di DocumentService@upload.
     *
     * @param  StoreDocumentRequest  $request
     * @param  Activity              $activity  Route model binding
     * @return RedirectResponse
     */
    public function store(StoreDocumentRequest $request, Activity $activity): RedirectResponse
    {
        try {
            $this->documentService->upload(
                file:         $request->file('file'),
                activity:     $activity,
                documentType: $request->validated('document_type'),
            );

            return redirect()
                ->route('admin.activities.show', $activity)
                ->with('success', 'Dokumen berhasil diunggah dan disimpan ke arsip.');

        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->route('admin.activities.show', $activity)
                ->with('error', 'Gagal mengunggah dokumen. Silakan coba lagi.');
        }
    }
}
