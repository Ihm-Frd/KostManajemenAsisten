<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosterBeranda extends Model
{
    use HasFactory;
    protected $fillable =[
        'judul',
        'deskripsi',
        'gambar',
        'link',
    ];
}
