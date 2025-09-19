<?php

namespace App\Models;

use App\Models\Tagihan;
use App\Models\DataPenghuni;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pembayaran extends Model
{
    use HasFactory;
    protected $fillable = [
        'data_penghuni_id',
        'invoice',
        'bukti_transfer',
        'tgl_bayar',
        'status_pembayaran',
        'keterangan',
    ];

    public function penghuni(): BelongsTo
    {
        return $this->belongsTo(DataPenghuni::class, 'data_penghuni_id');
    }

    // App\Models\Pembayaran.php
    public function verifikasiDanLunaskan()
    {
        if ($this->status_verifikasi === 'lunas') {
            Tagihan::where('data_penghuni_id', $this->data_penghuni_id)
                ->where('status', '!=', 'lunas')
                ->update(['status' => 'lunas']);
        }
    }

    public function tagihans(): BelongsToMany
    {
        return $this->belongsToMany(Tagihan::class, 'transaksi_detail');
    }
    
    protected static function booted()
    {
        static::created(function ($pembayaran) {
            $pembayaran->tagihans()->update(['status' => $pembayaran->status_pembayaran]);
        });
    
        static::updated(function ($pembayaran) {
            $pembayaran->tagihans()->update(['status' => $pembayaran->status_pembayaran]);
        });
    
        static::deleting(function ($pembayaran) {
            // Ambil semua tagihan terkait sebelum pivot dihapus
            $tagihanIds = $pembayaran->tagihans()->pluck('tagihans.id');
    
            // Hapus pivot-nya
            $pembayaran->tagihans()->detach();
    
            // Cek apakah masing-masing tagihan masih punya pembayaran lain?
            foreach ($tagihanIds as $tagihanId) {
                $masihAdaPembayaran = \DB::table('transaksi_detail')
                    ->where('tagihan_id', $tagihanId)
                    ->exists();
    
                if (! $masihAdaPembayaran) {
                    \App\Models\Tagihan::where('id', $tagihanId)->update([
                        'status' => 'belum_dibayar',
                    ]);
                }
            }
        });
    }
    
}
