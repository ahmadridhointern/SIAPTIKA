<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Traits\ActivityStatusFilter;
use App\Traits\DocumentCountQuery;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityController extends Controller
{
    use ActivityStatusFilter;
    use DocumentCountQuery;

    /**
     * Tampilkan daftar kegiatan untuk pegawai (read-only, publik).
     */
    public function index(Request $request): View
    {
        $query = Activity::withCount('documents');

        // Pencarian berdasarkan judul atau tempat
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'ilike', "%{$search}%")
                  ->orWhere('location', 'ilike', "%{$search}%");
            });
        }

        // Filter berdasarkan status (menggunakan shared trait)
        if ($request->filled('status') && in_array($request->input('status'), ['scheduled', 'ongoing', 'completed'])) {
            $this->applyActivityStatusFilter($query, $request->input('status'), now());
        }

        // ── Filter Lanjutan ──────────────────────────────────────────
        // Filter rentang tanggal kegiatan (wajib terisi keduanya)
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereDate('activity_date', '>=', $request->input('date_from'))
                  ->whereDate('activity_date', '<=', $request->input('date_to'));
        }


        // Filter hanya kegiatan yang sudah memiliki dokumen arsip
        if ($request->filled('has_documents') && $request->input('has_documents') === '1') {
            $query->whereHas('documents');
        }

        // Filter berdasarkan lokasi
        if ($request->filled('location')) {
            $query->where('location', 'ilike', "%{$request->input('location')}%");
        }

        // Pengurutan
        $sort = $request->input('sort', 'newest');
        match ($sort) {
            'oldest'         => $query->orderBy('activity_date', 'asc')->orderBy('time', 'asc'),
            'created_newest' => $query->orderBy('created_at', 'desc'),
            'created_oldest' => $query->orderBy('created_at', 'asc'),
            'az'             => $query->orderBy('title', 'asc'),
            default          => $query->orderBy('activity_date', 'desc')->orderBy('time', 'desc'),
        };

        // Ambil data dengan pagination
        $activities = $query->paginate(10)->withQueryString();

        // Daftar lokasi unik untuk dropdown filter
        $locations = Activity::select('location')
            ->distinct()
            ->orderBy('location', 'asc')
            ->pluck('location')
            ->filter()
            ->values();

        // AJAX request → kembalikan hanya partial HTML (tanpa layout)
        if ($request->ajax()) {
            return view('employee.activities.partials.list', compact('activities'));
        }

        return view('employee.activities.index', compact('activities', 'locations'));
    }

    /**
     * Tampilkan detail kegiatan beserta dokumen-dokumen terlampir (read-only).
     */
    public function show(Request $request, Activity $activity): View
    {
        // Eager load relasi user untuk info pembuat
        $activity->loadMissing('user');

        // Hitung jumlah berkas per jenis dokumen (menggunakan shared trait)
        $documentCounts = $this->getDocumentCounts($activity);

        // Filter dokumen berdasarkan jenis dan paginate
        $query = $activity->documents()->orderBy('created_at', 'desc');

        if ($request->filled('type') && in_array($request->input('type'), ['surat', 'notulen', 'dokumentasi'])) {
            $query->where('document_type', $request->input('type'));
        }

        $documents = $query->get();

        return view('employee.activities.show', compact('activity', 'documents', 'documentCounts'));
    }
}
