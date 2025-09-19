<?php

namespace App\Models;

use App\Models\DataPenghuni;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataKamar extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = [
       'nama_kamar', 
       'lokasi', 
       'harga_bulanan', 
       'fasilitas', 
       'status_kamar', 
       'keterangan', 
    ];

    public function penghuni(): HasMany
    {
        return $this->hasMany(DataPenghuni::class, 'data_kamar_id');
    }

    protected static function booted()
    {
        // Saat model disimpan (create/update), cek apakah ada penghuni
        static::saved(function ($kamar) {
            $adaPenghuni = $kamar->penghuni()->exists();

            $kamar->updateQuietly([
                'status_kamar' => $adaPenghuni ? 'Terpakai' : 'Kosong',
            ]);
        });

        // Saat model dihapus
        static::deleted(function ($kamar) {
            // fallback ke "Kosong"
            $kamar->updateQuietly([
                'status_kamar' => 'Kosong',
            ]);
        });
    }
}
