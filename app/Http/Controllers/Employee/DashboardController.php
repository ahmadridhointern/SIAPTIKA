<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Document;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman Dashboard Pegawai dengan statistik kegiatan.
     */
    public function index(): View
    {
        $today    = today();
        $todayStr = $today->format('Y-m-d');

        // Statistik kegiatan — 4 kartu utama
        $totalKegiatan     = Activity::count();
        $kegiatanHariIni   = Activity::whereDate('activity_date', $today)->count();
        $kegiatanMendatang = Activity::whereDate('activity_date', '>', $today)->count();
        $totalArsip        = Document::count();

        // Daftar kegiatan hari ini (urut waktu)
        $agendaHariIni = Activity::whereDate('activity_date', $today)
            ->orderBy('time')
            ->get();

        // 5 kegiatan mendatang terdekat
        $agendaMendatang = Activity::whereDate('activity_date', '>', $today)
            ->orderBy('activity_date')
            ->orderBy('time')
            ->take(5)
            ->get();

        // Tanggal terdekat & terlama untuk Kegiatan Mendatang
        $upcomingMinDate = Activity::whereDate('activity_date', '>', $today)->min('activity_date');
        $upcomingMaxDate = Activity::whereDate('activity_date', '>', $today)->max('activity_date');

        $upcomingMinStr = $upcomingMinDate ? \Carbon\Carbon::parse($upcomingMinDate)->format('Y-m-d') : null;
        $upcomingMaxStr = $upcomingMaxDate ? \Carbon\Carbon::parse($upcomingMaxDate)->format('Y-m-d') : null;

        return view('employee.dashboard', compact(
            'totalKegiatan',
            'kegiatanHariIni',
            'kegiatanMendatang',
            'totalArsip',
            'agendaHariIni',
            'agendaMendatang',
            'todayStr',
            'upcomingMinStr',
            'upcomingMaxStr',
        ));
    }
}
