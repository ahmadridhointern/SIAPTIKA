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
        $query = Activity::query();

        // Pencarian berdasarkan judul atau tempat
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Ambil data dengan pagination, terurut berdasarkan tanggal terbaru
        $activities = $query->orderBy('activity_date', 'desc')
            ->orderBy('time', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.activities.index', compact('activities'));
    }

    /**
     * Tampilkan form tambah kegiatan.
     */
    public function create(): View
    {
        return view('admin.activities.create');
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
     * Tampilkan detail kegiatan.
     */
    public function show(Activity $activity): View
    {
        // Eager load relasi documents untuk optimasi N+1
        $activity->load('documents');

        return view('admin.activities.show', compact('activity'));
    }

    /**
     * Tampilkan form edit kegiatan.
     */
    public function edit(Activity $activity): View|RedirectResponse
    {
        // Aturan Bisnis: Kegiatan yang sudah lewat tidak boleh diubah
        if ($activity->activity_date->lt(today())) {
            return redirect()->route('admin.activities.index')
                ->with('error', 'Kegiatan yang sudah lewat tidak dapat diubah.');
        }

        return view('admin.activities.edit', compact('activity'));
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
