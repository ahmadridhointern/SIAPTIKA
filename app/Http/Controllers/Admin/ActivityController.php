<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use App\Models\Activity;
use App\Traits\ActivityStatusFilter;
use App\Traits\DocumentCountQuery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ActivityController extends Controller
{
    use ActivityStatusFilter;
    use DocumentCountQuery;

    /**
     * Tampilkan daftar kegiatan.
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

        // Filter berdasarkan lokasi (dropdown dari lokasi unik)
        if ($request->filled('location')) {
            $query->where('location', 'ilike', "%{$request->input('location')}%");
        }

        // Pengurutan
        $sort = $request->input('sort', 'newest');
        match ($sort) {
            'oldest' => $query->orderBy('activity_date', 'asc')->orderBy('time', 'asc'),
            'az'     => $query->orderBy('title', 'asc'),
            default  => $query->orderBy('activity_date', 'desc')->orderBy('time', 'desc'),
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

        // Mendukung auto-buka edit modal dari halaman detail (link ?edit=id)
        $editActivity = null;
        if ($request->filled('edit') && is_numeric($request->input('edit'))) {
            $editActivity = Activity::find($request->integer('edit'));
            if ($editActivity && $editActivity->is_started) {
                $editActivity = null;
            }
        }

        if ($request->ajax()) {
            return view('admin.activities.partials.list', compact('activities', 'editActivity'));
        }

        return view('admin.activities.index', compact('activities', 'editActivity', 'locations'));
    }

    /**
     * Redirect ke index (form tambah ada di modal pada index).
     */
    public function create(): RedirectResponse
    {
        return redirect()->route('admin.activities.index', ['create' => 1]);
    }

    /**
     * Simpan kegiatan baru ke database.
     */
    public function store(StoreActivityRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();

        Activity::create($validated);

        return redirect()->route('admin.activities.index')
            ->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail kegiatan beserta daftar dokumennya (paginated & filtered by type).
     */
    public function show(Request $request, Activity $activity): View
    {
        $activity->loadMissing('user');

        // Menggunakan shared trait (menghilangkan duplikasi dengan Employee)
        $documentCounts = $this->getDocumentCounts($activity);

        $query = $activity->documents()->orderBy('created_at', 'desc');

        if ($request->filled('type') && in_array($request->input('type'), ['surat', 'notulen', 'dokumentasi'])) {
            $query->where('document_type', $request->input('type'));
        }

        $documents = $query->get();

        return view('admin.activities.show', compact('activity', 'documents', 'documentCounts'));
    }

    /**
     * Redirect ke index dengan query ?edit=id sehingga modal edit terbuka otomatis.
     */
    public function edit(Activity $activity): RedirectResponse
    {
        // Aturan Bisnis: Kegiatan yang sudah dimulai tidak boleh diubah
        if ($activity->is_started) {
            return redirect()->route('admin.activities.index')
                ->with('error', 'Kegiatan yang sudah dimulai tidak dapat diubah.');
        }

        return redirect()->route('admin.activities.index', ['edit' => $activity->id]);
    }

    /**
     * Perbarui data kegiatan.
     */
    public function update(UpdateActivityRequest $request, Activity $activity): RedirectResponse
    {
        // Aturan Bisnis: Kegiatan yang sudah dimulai tidak boleh diubah
        if ($activity->is_started) {
            return redirect()->route('admin.activities.index')
                ->with('error', 'Kegiatan yang sudah dimulai tidak dapat diubah.');
        }

        $activity->update($request->validated());

        return redirect()->back()
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    /**
     * Hapus data kegiatan.
     */
    public function destroy(Activity $activity): RedirectResponse
    {
        // Aturan Bisnis: Kegiatan yang sudah dimulai tidak boleh dihapus
        if ($activity->is_started) {
            return redirect()->route('admin.activities.index')
                ->with('error', 'Kegiatan yang sudah dimulai tidak dapat dihapus.');
        }

        // Aturan Bisnis: Kegiatan yang sudah memiliki dokumen tidak boleh dihapus
        if ($activity->documents()->count() > 0) {
            return redirect()->route('admin.activities.index')
                ->with('error', 'Kegiatan tidak dapat dihapus karena sudah memiliki dokumen arsip.');
        }

        $activity->delete();

        return redirect()->route('admin.activities.index')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }
}
