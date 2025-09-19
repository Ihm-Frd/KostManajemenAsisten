<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataPenghuni;
use App\Models\Tagihan;
use Carbon\Carbon;

class TagihanSeeder extends Seeder
{
    public function run()
    {
        $periodes = ['Juni', 'Juli', 'Agustus'];

        // Ambil semua penghuni beserta kamar-nya
        $penghunis = DataPenghuni::with('dataKamar')->get();

        foreach ($penghunis as $penghuni) {
            foreach ($periodes as $periode) {

                // Tentukan tahun dan bulan berdasarkan periode (misal 2025)
                $tahun = 2025;
                $bulan = match($periode) {
                    'Juni' => 6,
                    'Juli' => 7,
                    'Agustus' => 8,
                    default => 1,
                };

                // Ambil tanggal terakhir bulan tsb
                $jatuhTempo = Carbon::create($tahun, $bulan, 1)->endOfMonth();

                // Cek kalau tagihan untuk penghuni dan periode ini belum ada, supaya gak duplikat
                $exists = Tagihan::where('data_penghuni_id', $penghuni->id)
                    ->where('periode', $periode)
                    ->exists();

                if (!$exists) {
                    Tagihan::create([
                        'data_penghuni_id' => $penghuni->id,
                        'periode' => $periode,
                        'jatuh_tempo' => $jatuhTempo,
                        'nominal' => $penghuni->dataKamar->harga_bulanan,
                        'status' => 'belum_dibayar',
                        'catatan' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
