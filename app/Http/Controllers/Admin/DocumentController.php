<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentRequest;
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
 *   - download() : Stream file dari Supabase dengan header Content-Disposition: attachment
 *
 * Prinsip: Thin Controller — tidak ada logika bisnis di sini.
 */
class DocumentController extends Controller
{
    /**
     * Dependency Injection: DocumentService menangani upload + simpan metadata.
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
        // Ekstrak path relatif dari file_url yang tersimpan di DB
        // file_url format: https://...supabase.co/storage/v1/object/public/documents/42/uuid.pdf
        // Path yang dibutuhkan: documents/42/uuid.pdf
        $url      = $document->file_url;
        $needle   = '/object/public/';
        $pathFull = substr($url, strpos($url, $needle) + strlen($needle));

        // Hapus nama bucket dari awal path (bucket = 'documents')
        // pathFull = "documents/42/uuid.pdf" → storagePath = "42/uuid.pdf"
        $bucketName  = 'documents';
        $storagePath = ltrim(substr($pathFull, strlen($bucketName)), '/');

        // Bangun path lengkap yang dikirim ke Storage disk
        $fullPath = $bucketName . '/' . $storagePath;

        return response()->streamDownload(
            callback: function () use ($fullPath) {
                $stream = \Illuminate\Support\Facades\Storage::disk('supabase')->readStream($fullPath);
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
