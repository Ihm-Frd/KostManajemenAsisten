<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use Illuminate\Support\Arr;

class PembayaranSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua data_penghuni_id unik dari tabel tagihans
        $penghuniIds = Tagihan::pluck('data_penghuni_id')->unique()->toArray();

        foreach ($penghuniIds as $penghuniId) {
            Pembayaran::create([
                'data_penghuni_id'   => $penghuniId,
                'invoice'            => 'invoices/invoice.pdf',       // directory + nama file
                'bukti_transfer'     => 'bukti-transfer/Bukti_TF.jpg', // directory + nama file
                'tgl_bayar'          => now()->subDays(rand(0, 30)),
                'status_pembayaran'  => Arr::random(['proses', 'lunas', 'ditolak']),
                'keterangan'         => 'Pembayaran dummy seeder',
            ]);
        }
    }
}
