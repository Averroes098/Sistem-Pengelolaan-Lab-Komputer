<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alat extends Model
{
    protected $table = 'alat';
    protected $guarded = ['id'];

    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'alat_id', 'id');
    }
}
