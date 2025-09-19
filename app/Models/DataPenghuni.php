<?php

namespace App\Models;

use App\Models\User;
use App\Models\Tagihan;
use App\Models\DataKamar;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataPenghuni extends Model
{
    use HasFactory;
    protected $fillable =[
        'data_kamar_id',
        'user_id',
        'nama',
        'nik',
        'tgl_lahir',
        'no_wa',
        'jns_kelamin',
        'status',
        'pas_foto',
        'keterangan',
    ];


    public function User():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function dataKamar():BelongsTo
    {
        return $this->belongsTo(DataKamar::class);
    }

    public function tagihan(): HasMany
    {
        return $this->hasMany(Tagihan::class, 'data_penghuni_id');
    }
    

    protected static function booted()
    {
        static::created(function ($penghuni) {
            // Ubah status kamar jadi "Terpakai" setelah penghuni dibuat
            $penghuni->dataKamar()->update([
                'status_kamar' => 'Terpakai',
            ]);
        });

        static::deleted(function ($penghuni) {
            // Saat penghuni dihapus, kembalikan kamar jadi "Kosong"
            $penghuni->dataKamar()->update([
                'status_kamar' => 'Kosong',
            ]);
        });
    }


}
