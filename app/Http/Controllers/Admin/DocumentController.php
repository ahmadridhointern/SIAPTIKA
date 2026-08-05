<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Models\Activity;
use App\Models\Document;
use App\Services\DocumentService;
use App\Services\StorageServiceInterface;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * DocumentController
 *
 * Bertanggung jawab menangani request HTTP untuk dokumen arsip:
 *   - store()    : Upload dokumen + simpan metadata (via DocumentService)
 *   - update()   : Edit metadata & opsi ganti berkas (via DocumentService)
 *   - download() : Stream file dari Supabase dengan header Content-Disposition: attachment
 *
 * Prinsip: Thin Controller — tidak ada logika bisnis di sini.
 */
class DocumentController extends Controller
{
    /**
     * Dependency Injection: DocumentService menangani upload, update, & simpan metadata.
     * StorageServiceInterface digunakan untuk streaming download.
     */
    public function __construct(
        private readonly DocumentService         $documentService,
        private readonly StorageServiceInterface $storage,
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
    public function store(StoreDocumentRequest $request, Activity $activity): \Illuminate\Http\JsonResponse|RedirectResponse
    {
        if ($activity->activity_date->gt(today())) {
            $errorMsg = 'Dokumen tidak dapat diunggah karena kegiatan belum berlangsung.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $errorMsg], 422);
            }
            return redirect()->back()->with('error', $errorMsg);
        }

        try {
            $document = $this->documentService->upload(
                file:         $request->file('file'),
                activity:     $activity,
                documentType: $request->validated('document_type'),
            );

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Dokumen berhasil diunggah.',
                    'data'    => [
                        'id'            => $document->id,
                        'file_name'     => $document->file_name,
                        'document_type' => $document->document_type,
                    ],
                ]);
            }

            return redirect()
                ->route('admin.activities.show', $activity)
                ->with('success', 'Dokumen berhasil diunggah dan disimpan ke arsip.');

        } catch (\Throwable $e) {
            report($e);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengunggah dokumen: ' . $e->getMessage(),
                ], 422);
            }

            return redirect()
                ->route('admin.activities.show', $activity)
                ->with('error', 'Gagal mengunggah dokumen. Silakan coba lagi.');
        }
    }


    /**
     * Perbarui metadata dokumen dan/atau ganti berkas di Supabase Storage.
     *
     * Validasi dilakukan otomatis oleh UpdateDocumentRequest.
     * Logika bisnis didelegasikan ke DocumentService@update.
     *
     * @param  UpdateDocumentRequest  $request
     * @param  Document               $document  Route model binding
     * @return RedirectResponse
     */
    public function update(UpdateDocumentRequest $request, Document $document): RedirectResponse
    {
        try {
            $this->documentService->update(
                document:     $document,
                documentType: $request->validated('document_type'),
                newFile:      $request->file('file'),
            );

            return redirect()
                ->back()
                ->with('success', 'Dokumen arsip berhasil diperbarui.');

        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->back()
                ->with('error', 'Gagal memperbarui dokumen arsip. Silakan coba lagi.');
        }
    }

    /**
     * Tampilkan (preview) dokumen di browser secara inline (tab baru).
     *
     * Me-proxy file dari Supabase Storage ke browser dengan header
     * Content-Disposition: inline sehingga browser membuka preview (PDF/gambar/video).
     *
     * @param  Document  $document  Route model binding
     * @return StreamedResponse
     */
    public function show(Document $document): StreamedResponse
    {
        $path = DocumentService::extractStoragePath($document->file_url);

        if (! $path || ! \Illuminate\Support\Facades\Storage::disk('supabase')->exists($path)) {
            abort(404, 'Berkas dokumen tidak ditemukan di storage.');
        }

        $fileName = $document->file_name;
        $mimeType = $this->getMimeType($fileName);

        return response()->stream(
            callback: function () use ($path) {
                $stream = \Illuminate\Support\Facades\Storage::disk('supabase')->readStream($path);
                if ($stream) {
                    fpassthru($stream);
                    fclose($stream);
                }
            },
            status: 200,
            headers: [
                'Content-Type'        => $mimeType,
                'Content-Disposition' => 'inline; filename="' . addslashes($fileName) . '"',
            ],
        );
    }

    /**
     * Unduh dokumen dari Supabase Storage.
     *
     * Me-proxy file dari Supabase Storage ke browser dengan header
     * Content-Disposition: attachment sehingga browser menampilkan dialog simpan.
     * URL Supabase tidak langsung terekspos ke client.
     *
     * @param  Document  $document  Route model binding
     * @return StreamedResponse
     */
    public function download(Document $document): StreamedResponse
    {
        $path = DocumentService::extractStoragePath($document->file_url);

        if (! $path || ! \Illuminate\Support\Facades\Storage::disk('supabase')->exists($path)) {
            abort(404, 'Berkas dokumen tidak ditemukan di storage.');
        }

        return response()->streamDownload(
            callback: function () use ($path) {
                $stream = \Illuminate\Support\Facades\Storage::disk('supabase')->readStream($path);
                if ($stream) {
                    fpassthru($stream);
                    fclose($stream);
                }
            },
            name:    $document->file_name,
            headers: [
                'Content-Type' => $this->getMimeType($document->file_name),
            ],
        );
    }

    /**
     * Deteksi MIME type berdasarkan ekstensi nama file.
     * Fallback ke application/octet-stream agar browser selalu download.
     */
    private function getMimeType(string $fileName): string
    {
        $ext   = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $types = [
            'pdf'  => 'application/pdf',
            'doc'  => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
            'mp4'  => 'video/mp4',
        ];

        return $types[$ext] ?? 'application/octet-stream';
    }
}
