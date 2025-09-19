<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataPenghuniSeeder extends Seeder
{
    public function run(): void
    {
        $namaList = ['Admin', 'User'];

        // Ambil 2 user dari tabel `users`
        $users = DB::table('users')->take(2)->get();

        for ($i = 0; $i < count($namaList); $i++) {
            DB::table('data_penghunis')->insert([
                'user_id' => $users[$i]->id, // Ambil user_id dari data users
                'data_kamar_id' => rand(1, 5),
                'nama' => $namaList[$i],
                'nik' => '32040100' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'tgl_lahir' => Carbon::now()->subYears(rand(18, 30))->format('Y-m-d'),
                'no_wa' => '08' . rand(1000000000, 9999999999),
                'jns_kelamin' => $i % 2 === 0 ? 'Laki-laki' : 'Perempuan',
                'status' => $i % 2 === 0 ? 'Aktif' : 'Nonaktif',
                'pas_foto' => 'penghuni_' . ($i + 1) . '.jpg',
                'keterangan' => $i % 3 === 0 ? 'Tidak ada catatan khusus' : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
