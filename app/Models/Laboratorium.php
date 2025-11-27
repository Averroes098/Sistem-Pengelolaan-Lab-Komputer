<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Laboratorium extends Model
{   
    protected $table = 'laboratorium';
    protected $guarded = ['id'];

    /**
     * Relasi ke tabel peminjaman
     */
    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'lab_id', 'id');
    }

    /**
     * Relasi ke tabel jadwal
     */
    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'lab_id', 'id');
    }

    /**
     * Relasi ke tabel alat
     */
    public function alats(): HasMany
    {
        return $this->hasMany(Alat::class, 'lab_id', 'id');
    }

    /**
     * Relasi ke tabel documents
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'lab_id', 'id');
    }

    // Accessor biar $lab->nama tetap jalan
    public function getNamaAttribute()
    {
        return $this->nama_lab;
    }
}