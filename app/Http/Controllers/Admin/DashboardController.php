<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman Dashboard Admin dengan statistik kegiatan nyata.
     */
    public function index(): View
    {
        $today = today();

        $totalKegiatan = Activity::count();
        $kegiatanHariIni = Activity::whereDate('date', $today)->count();
        $kegiatanMendatang = Activity::whereDate('date', '>', $today)->count();
        $kegiatanSelesai = Activity::whereDate('date', '<', $today)->count();

        // Ambil 5 kegiatan terbaru berdasarkan tanggal input / pelaksanaan terbaru
        $kegiatanTerbaru = Activity::orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalKegiatan',
            'kegiatanHariIni',
            'kegiatanMendatang',
            'kegiatanSelesai',
            'kegiatanTerbaru'
        ));
    }
}
