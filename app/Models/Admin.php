<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama',
        'user_id',
        'kode',
        'no_wa',
        'jabatan',
        'jobdesk',
        'foto_ktp',
        'keterangan',
    ];
    
        public function User() :BelongsTo 
        {
            return $this->belongsTo(User::class);
        }
}

