<?php

namespace App\Models;

use App\Models\Pembayaran;
use App\Models\DataPenghuni;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tagihan extends Model
{
    use HasFactory;

    protected $fillable = [
        'data_penghuni_id',
        'periode',
        'nominal',
        'status',
        'jatuh_tempo',
        'catatan',
    ];

    public function dataPenghuni(): BelongsTo
    {
        return $this->belongsTo(DataPenghuni::class, 'data_penghuni_id');
    }

    public function pembayarans(): BelongsToMany
    {
        return $this->belongsToMany(Pembayaran::class, 'transaksi_detail');
    }



}
