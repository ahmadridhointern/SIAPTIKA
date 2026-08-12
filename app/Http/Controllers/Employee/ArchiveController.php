<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArchiveController extends Controller
{
    /**
     * Tampilkan daftar seluruh arsip dokumen untuk pegawai (read-only, publik).
     *
     * Eager load 'activity' untuk menghindari N+1 query.
     */
    public function index(Request $request): View
    {
        $query = Document::with('activity');

        // Filter berdasarkan jenis dokumen
        if ($request->filled('type') && in_array($request->input('type'), ['surat', 'notulen', 'dokumentasi'])) {
            $query->where('document_type', $request->input('type'));
        }

        // Filter berdasarkan format/ekstensi berkas (whitelist format yang valid)
        $allowedFormats = ['pdf', 'jpg', 'png', 'word', 'mp4'];
        if ($request->filled('file_format') && in_array(strtolower($request->input('file_format')), $allowedFormats)) {
            $format = strtolower($request->input('file_format'));
            match ($format) {
                'pdf'   => $query->where('file_name', 'ilike', '%.pdf'),
                'jpg'   => $query->where(fn($q) => $q->where('file_name', 'ilike', '%.jpg')->orWhere('file_name', 'ilike', '%.jpeg')),
                'png'   => $query->where('file_name', 'ilike', '%.png'),
                'word'  => $query->where(fn($q) => $q->where('file_name', 'ilike', '%.doc')->orWhere('file_name', 'ilike', '%.docx')),
                'mp4'   => $query->where('file_name', 'ilike', '%.mp4'),
                default => null,
            };
        }

        // Pencarian berdasarkan nama file ATAU judul kegiatan terkait
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('file_name', 'ilike', "%{$search}%")
                  ->orWhereHas('activity', fn ($a) => $a->where('title', 'ilike', "%{$search}%"));
            });
        }

        // ── Filter Lanjutan ──────────────────────────────────────────
        // Filter rentang tanggal unggah dokumen (wajib terisi keduanya)
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'))
                  ->whereDate('created_at', '<=', $request->input('date_to'));
        }

        // Filter rentang tanggal kegiatan asal (wajib terisi keduanya)
        if ($request->filled('activity_date_from') && $request->filled('activity_date_to')) {
            $query->whereHas('activity', fn ($a) =>
                $a->whereDate('activity_date', '>=', $request->input('activity_date_from'))
                  ->whereDate('activity_date', '<=', $request->input('activity_date_to'))
            );
        }


        // Pengurutan
        $sort = $request->input('sort', 'newest');
        match ($sort) {
            'oldest', 'created_oldest' => $query->orderBy('created_at', 'asc'),
            'az'                       => $query->orderBy('file_name', 'asc'),
            'activity_date'            => $query->orderByDesc(
                \App\Models\Activity::select('activity_date')
                    ->whereColumn('activities.id', 'documents.activity_id')
                    ->limit(1)
            ),
            default                    => $query->orderBy('created_at', 'desc'),
        };

        $documents = $query->paginate(10)->withQueryString();

        // AJAX request → kembalikan hanya partial HTML (tanpa layout)
        if ($request->ajax()) {
            return view('employee.archive.partials.list', compact('documents'));
        }

        return view('employee.archive.index', compact('documents'));
    }
}
