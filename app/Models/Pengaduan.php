<?php

namespace App\Models;

use App\Models\DataPenghuni;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengaduan extends Model
{
    use HasFactory;
    protected $fillable = [
        'data_penghuni_id',
        'foto',
        'deskripsi',
        'status_pengaduan',
    ];

    public function Penghuni(): BelongsTo
    {
        return $this->belongsTo(DataPenghuni::class, 'data_penghuni_id');
    }
}
