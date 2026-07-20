<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@siaptika.id')->first();
        $userId = $admin ? $admin->id : User::factory()->create()->id;

        // 1. Kegiatan Hari Ini
        Activity::create([
            'user_id'       => $userId,
            'title'         => 'Rapat Koordinasi Infrastruktur E-Government',
            'activity_date' => today(),
            'time'          => '10:00:00',
            'location'      => 'Ruang Rapat Bidang APTIKA',
            'description'   => 'Membahas integrasi sistem informasi dan koordinasi server terpusat Diskominfotik Riau.',
            'status'        => 'scheduled',
        ]);

        // 2. Kegiatan Mendatang
        Activity::create([
            'user_id'       => $userId,
            'title'         => 'Sosialisasi Keamanan Informasi Ke Kabupaten/Kota',
            'activity_date' => today()->addDay(),
            'time'          => '14:00:00',
            'location'      => 'Aula Diskominfotik Riau',
            'description'   => 'Pelatihan dan penyuluhan kesadaran siber bagi perwakilan dinas komunikasi daerah.',
            'status'        => 'scheduled',
        ]);

        // 3. Kegiatan Selesai (Masa Lalu)
        Activity::create([
            'user_id'       => $userId,
            'title'         => 'Evaluasi Bulanan Layanan Smart City',
            'activity_date' => today()->subDay(),
            'time'          => '09:00:00',
            'location'      => 'Ruang Command Center',
            'description'   => 'Tinjauan performa server layanan publik provinsi selama sebulan terakhir.',
            'status'        => 'completed',
        ]);
    }
}
