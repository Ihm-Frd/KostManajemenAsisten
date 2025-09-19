<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;         // ⬅️ ini yang kurang
use App\Models\DataKamar;    // pastikan ini juga ada
use App\Models\DataPenghuni;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataPenghuniSeeder extends Seeder
{
    // public function run(): void
    // {
    //     \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    //     \DB::table('data_penghunis')->truncate();
    //     \DB::statement('SET FOREIGN_KEY_CHECKS=1;');


    //     $users = User::all();
    //     $kamarList = DataKamar::all();

    //     foreach ($users as $index => $user) {
    //         $kamar = $kamarList->random();

    //         // Tentukan keterangan berdasarkan fasilitas
    //         if (in_array($kamar->fasilitas, ['⭐', '⭐⭐'])) {
    //             $keterangan = "Wc di luar serta tidak termasuk air & listrik";
    //         } else {
    //             $keterangan = "Tidak ada bayaran tambahan & Wc di dalam";
    //         }

    //         DataPenghuni::create([
    //             'user_id'       => $user->id,
    //             'data_kamar_id' => $kamar->id,
    //             'nama'          => $user->name,
    //             'nik'           => fake()->unique()->numerify('3204#########'),
    //             'tgl_lahir'     => fake()->dateTimeBetween('1992-01-01', '2005-12-31')->format('Y-m-d'),
    //             'no_wa'         => '08' . fake()->numerify('##########'),
    //             'jns_kelamin'   => fake()->randomElement(['Laki-laki', 'Perempuan']),
    //             'status'        => fake()->randomElement(['Aktif', 'Tidak Aktif']),
    //             'pas_foto'      => 'penghuni_' . ($index + 1) . '.jpg',
    //             'keterangan'    => $keterangan,
    //         ]);
    //     }
    // }
    public function run(): void
{
    \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    \DB::table('data_penghunis')->truncate();
    \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    $users = User::all();
    $kamarList = DataKamar::all()->shuffle(); // acak kamar

    foreach ($users as $index => $user) {
        if ($kamarList->isEmpty()) {
            // Kalau kamar sudah habis, berhenti assign kamar ke user berikutnya
            break;
        }

        $kamar = $kamarList->pop(); // ambil 1 kamar dan keluarkan dari list

        // Tentukan keterangan berdasarkan fasilitas kamar
        if (in_array($kamar->fasilitas, ['⭐', '⭐⭐'])) {
            $keterangan = "Wc di luar serta tidak termasuk air & listrik";
        } else {
            $keterangan = "Tidak ada bayaran tambahan & Wc di dalam";
        }

        DataPenghuni::create([
            'user_id'       => $user->id,
            'data_kamar_id' => $kamar->id,
            'nama'          => $user->name,
            'nik'           => fake()->unique()->numerify('3204#########'),
            'tgl_lahir'     => fake()->dateTimeBetween('1992-01-01', '2005-12-31')->format('Y-m-d'),
            'no_wa'         => '08' . fake()->numerify('##########'),
            'jns_kelamin'   => fake()->randomElement(['Laki-laki', 'Perempuan']),
            'status'        => 'Aktif',
            'pas_foto'      => 'penghuni_' . ($index + 1) . '.jpg',
            'keterangan'    => $keterangan,
        ]);
    }
}

}
