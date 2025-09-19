<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\DataPenghuni;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $namaList = [
            'Admin',
            'User',
        ];

        foreach ($namaList as $nama) {
            DB::table('users')->insert([
                'name' => $nama,
                'email' => Str::slug($nama) . '@gmail.com', // contoh: admin@gmail.com
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}    
