<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use App\Models\Activity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ActivityController extends Controller
{
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

        // Filter berdasarkan status
        if ($request->filled('status') && in_array($request->input('status'), ['scheduled', 'completed'])) {
            $query->where('status', $request->input('status'));
        }

        // Ambil data dengan pagination, terurut berdasarkan tanggal terbaru
        $activities = $query->orderBy('activity_date', 'desc')
            ->orderBy('time', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Mendukung auto-buka edit modal dari halaman detail (link ?edit=id)
        $editActivity = null;
        if ($request->filled('edit') && is_numeric($request->input('edit'))) {
            $editActivity = Activity::find($request->integer('edit'));
            // Hanya kegiatan yang belum lewat boleh diubah
            if ($editActivity && $editActivity->activity_date->lt(today())) {
                $editActivity = null;
            }
        }

        if ($request->ajax()) {
            return view('admin.activities.partials.list', compact('activities', 'editActivity'));
        }

        return view('admin.activities.index', compact('activities', 'editActivity'));
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
        $validated['user_id'] = Auth::id(); // Tautkan dengan user yang sedang login

        Activity::create($validated);

        return redirect()->route('admin.activities.index')
            ->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail kegiatan beserta daftar dokumennya (paginated & filtered by type).
     */
    public function show(Request $request, Activity $activity): View
    {
        // Eager load relasi user untuk menghindari N+1 pada info pembuat
        $activity->loadMissing('user');

        // Counter jumlah berkas per jenis dokumen (dioptimasi: 1 query agregasi)
        $rawCounts = $activity->documents()
            ->selectRaw('document_type, COUNT(*) as aggregate')
            ->groupBy('document_type')
            ->pluck('aggregate', 'document_type');

        $documentCounts = [
            'all'         => (int) $rawCounts->sum(),
            'surat'       => (int) ($rawCounts['surat'] ?? 0),
            'notulen'     => (int) ($rawCounts['notulen'] ?? 0),
            'dokumentasi' => (int) ($rawCounts['dokumentasi'] ?? 0),
        ];

        // Filter dokumen berdasarkan document_type jika ada
        $query = $activity->documents()->orderBy('created_at', 'desc');

        if ($request->filled('type') && in_array($request->input('type'), ['surat', 'notulen', 'dokumentasi'])) {
            $query->where('document_type', $request->input('type'));
        }

        $documents = $query->paginate(5, ['*'], 'doc_page')
            ->withQueryString();

        return view('admin.activities.show', compact('activity', 'documents', 'documentCounts'));
    }

    /**
     * Redirect ke index dengan query ?edit=id sehingga modal edit terbuka otomatis.
     */
    public function edit(Activity $activity): RedirectResponse
    {
        // Aturan Bisnis: Kegiatan yang sudah lewat tidak boleh diubah
        if ($activity->activity_date->lt(today())) {
            return redirect()->route('admin.activities.index')
                ->with('error', 'Kegiatan yang sudah lewat tidak dapat diubah.');
        }

        return redirect()->route('admin.activities.index', ['edit' => $activity->id]);
    }

    /**
     * Perbarui data kegiatan.
     */
    public function update(UpdateActivityRequest $request, Activity $activity): RedirectResponse
    {
        // Aturan Bisnis: Kegiatan yang sudah lewat tidak boleh diubah
        if ($activity->activity_date->lt(today())) {
            return redirect()->route('admin.activities.index')
                ->with('error', 'Kegiatan yang sudah lewat tidak dapat diubah.');
        }

        $activity->update($request->validated());

        return redirect()->route('admin.activities.index')
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    /**
     * Hapus data kegiatan.
     */
    public function destroy(Activity $activity): RedirectResponse
    {
        // Aturan Bisnis 1: Kegiatan yang sudah lewat tidak boleh dihapus
        if ($activity->activity_date->lt(today())) {
            return redirect()->route('admin.activities.index')
                ->with('error', 'Kegiatan yang sudah lewat tidak dapat dihapus.');
        }

        // Aturan Bisnis 2: Jika kegiatan sudah memiliki dokumen, penghapusan ditolak
        if ($activity->documents()->count() > 0) {
            return redirect()->route('admin.activities.index')
                ->with('error', 'Kegiatan tidak dapat dihapus karena sudah memiliki dokumen arsip.');
        }

        $activity->delete();

        return redirect()->route('admin.activities.index')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }
}
