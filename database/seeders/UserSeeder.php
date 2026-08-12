<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['id' => 1],
            [
                'login_id' => 'ADM001',
                'name'     => 'Administrator APTIKA',
                'password' => 'password123',
            ]
        );
    }
}
