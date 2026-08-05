<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Services\DocumentService;
use App\Services\StorageServiceInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Employee DocumentController
 *
 * Menangani preview (inline) dan unduh dokumen arsip untuk pegawai.
 * Read-only — tidak ada upload, edit, atau hapus.
 */
class DocumentController extends Controller
{
    public function __construct(
        private readonly DocumentService         $documentService,
        private readonly StorageServiceInterface $storage,
    ) {}

    /**
     * Tampilkan (preview) dokumen di browser secara inline (tab baru).
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
