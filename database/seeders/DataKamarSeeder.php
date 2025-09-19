<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataKamarSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama' => 'Bpk.Jamal', 'lokasi' => 'cijingga',  'jenis' => '⭐⭐⭐'],
            ['nama' => 'Bpk.Jamal', 'lokasi' => 'cijingga',  'jenis' => '⭐⭐'],
            ['nama' => 'Bpk.Jamal', 'lokasi' => 'cijingga',  'jenis' => '⭐'],

            ['nama' => 'Bpk.Udin',  'lokasi' => 'sukaresmi', 'jenis' => '⭐⭐⭐'],
            ['nama' => 'Bpk.Udin',  'lokasi' => 'sukaresmi', 'jenis' => '⭐⭐'],
            ['nama' => 'Bpk.Udin',  'lokasi' => 'sukaresmi', 'jenis' => '⭐'],

            ['nama' => 'hj.Edah',   'lokasi' => 'ciantra',   'jenis' => '⭐⭐⭐'],
            ['nama' => 'hj.Edah',   'lokasi' => 'ciantra',   'jenis' => '⭐⭐'],
            ['nama' => 'hj.Edah',   'lokasi' => 'ciantra',   'jenis' => '⭐'],
        ];

        $harga = [
            '⭐'   => 500000,
            '⭐⭐'  => 750000,
            '⭐⭐⭐' => 1000000,
        ];

        foreach ($data as $index => $item) {
            DB::table('data_kamars')->insert([
                'nama_kamar'     => str_pad($index + 1, 3, '0', STR_PAD_LEFT) . '-' . $item['nama'],
                'lokasi'         => $item['lokasi'],
                'harga_bulanan'  => $harga[$item['jenis']],
                'fasilitas'    => $item['jenis'],
                'status_kamar'   => 'Kosong',
                'keterangan'     => 'Kamar Mandi Dalam',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    }
}
