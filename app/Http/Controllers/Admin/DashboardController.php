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
        $kegiatanHariIni = Activity::whereDate('activity_date', $today)->count();
        $kegiatanMendatang = Activity::whereDate('activity_date', '>', $today)->count();
        $kegiatanSelesai = Activity::whereDate('activity_date', '<', $today)->count();

        // Ambil 10 kegiatan terbaru berdasarkan tanggal pelaksanaan terbaru
        $kegiatanTerbaru = Activity::orderBy('activity_date', 'desc')
            ->orderBy('time', 'desc')
            ->take(10)
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
