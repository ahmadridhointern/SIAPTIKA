<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Kegiatan Hari Ini
        Activity::create([
            'title'       => 'Rapat Koordinasi Infrastruktur E-Government',
            'date'        => today(),
            'time'        => '10:00:00',
            'location'    => 'Ruang Rapat Bidang APTIKA',
            'description' => 'Membahas integrasi sistem informasi dan koordinasi server terpusat Diskominfotik Riau.',
        ]);

        // 2. Kegiatan Mendatang
        Activity::create([
            'title'       => 'Sosialisasi Keamanan Informasi Ke Kabupaten/Kota',
            'date'        => today()->addDay(),
            'time'        => '14:00:00',
            'location'    => 'Aula Diskominfotik Riau',
            'description' => 'Pelatihan dan penyuluhan kesadaran siber bagi perwakilan dinas komunikasi daerah.',
        ]);

        // 3. Kegiatan Selesai (Masa Lalu)
        Activity::create([
            'title'       => 'Evaluasi Bulanan Layanan Smart City',
            'date'        => today()->subDay(),
            'time'        => '09:00:00',
            'location'    => 'Ruang Command Center',
            'description' => 'Tinjauan performa server layanan publik provinsi selama sebulan terakhir.',
        ]);
    }
}
