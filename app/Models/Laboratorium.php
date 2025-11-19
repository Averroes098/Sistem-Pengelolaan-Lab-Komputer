<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Laboratorium extends Model
{
    protected $table = 'laboratorium';
    protected $guarded = ['id'];

    // Relasi
    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'lab_id','id');
    }

    // Accessor biar $lab->nama tetap jalan
    public function getNamaAttribute()
    {
        return $this->nama_lab;
    }
}