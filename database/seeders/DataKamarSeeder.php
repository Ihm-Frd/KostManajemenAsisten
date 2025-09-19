<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataKamarSeeder extends Seeder
{
    // public function run(): void
    // {
    //     $data = [
    //         ['nama' => 'Bpk.Jamal', 'lokasi' => 'cijingga',  'jenis' => '⭐⭐⭐'],
    //         ['nama' => 'Bpk.Jamal', 'lokasi' => 'cijingga',  'jenis' => '⭐⭐'],
    //         ['nama' => 'Bpk.Jamal', 'lokasi' => 'cijingga',  'jenis' => '⭐'],

    //         ['nama' => 'Bpk.Udin',  'lokasi' => 'sukaresmi', 'jenis' => '⭐⭐⭐'],
    //         ['nama' => 'Bpk.Udin',  'lokasi' => 'sukaresmi', 'jenis' => '⭐⭐'],
    //         ['nama' => 'Bpk.Udin',  'lokasi' => 'sukaresmi', 'jenis' => '⭐'],

    //         ['nama' => 'hj.Edah',   'lokasi' => 'ciantra',   'jenis' => '⭐⭐⭐'],
    //         ['nama' => 'hj.Edah',   'lokasi' => 'ciantra',   'jenis' => '⭐⭐'],
    //         ['nama' => 'hj.Edah',   'lokasi' => 'ciantra',   'jenis' => '⭐'],
    //     ];

    //     $harga = [
    //         '⭐'   => 500000,
    //         '⭐⭐'  => 750000,
    //         '⭐⭐⭐' => 1000000,
    //     ];

    //     foreach ($data as $index => $item) {
    //         DB::table('data_kamars')->insert([
    //             'nama_kamar'     => str_pad($index + 1, 3, '0', STR_PAD_LEFT) . '-' . $item['nama'],
    //             'lokasi'         => $item['lokasi'],
    //             'harga_bulanan'  => $harga[$item['jenis']],
    //             'fasilitas'    => $item['jenis'],
    //             'status_kamar'   => 'Kosong',
    //             'keterangan'     => 'Kamar Mandi Dalam',
    //             'created_at'     => now(),
    //             'updated_at'     => now(),
    //         ]);
    //     }
    // }

    public function run(): void
    {
        $pemilikList = [
            'Bpk.Jamal',
            'Bpk.Udin',
            'Ibu.Siti',
            'Bpk.Ahmad',
            'Hj.Edah',
        ];

        $lokasiList = ['cijingga', 'sukaresmi', 'ciantra'];

        $harga = [
            '⭐'   => 500000,
            '⭐⭐'  => 750000,
            '⭐⭐⭐' => 1000000,
        ];

        $jenisList = array_keys($harga);

        $totalData = 300;
        $data = [];

        for ($i = 0; $i < $totalData; $i++) {
            $pemilik = $pemilikList[$i % count($pemilikList)];
            $lokasi = $lokasiList[array_rand($lokasiList)];
            $jenis = $jenisList[array_rand($jenisList)];

            // Tentukan keterangan berdasarkan fasilitas
            if ($jenis === '⭐⭐⭐') {
                $keterangan = 'Air, listrik gratis & Wc di dalam';
            } else {
                $keterangan = 'Wc di luar serta tidak termasuk air & listrik';
            }

            // Hitung nomor kamar per pemilik
            static $nomorKamar = [];
            if (!isset($nomorKamar[$pemilik])) {
                $nomorKamar[$pemilik] = 1;
            }

            $data[] = [
                'nama_kamar'    => str_pad($nomorKamar[$pemilik]++, 3, '0', STR_PAD_LEFT) . '-' . $pemilik,
                'lokasi'        => $lokasi,
                'harga_bulanan' => $harga[$jenis],
                'fasilitas'     => $jenis,
                'status_kamar'  => 'Kosong',
                'keterangan'    => $keterangan,
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }

        DB::table('data_kamars')->insert($data);
    }
}
