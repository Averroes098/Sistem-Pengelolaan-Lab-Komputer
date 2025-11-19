<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Laboratorium extends Model
{
    protected $table = 'laboratorium';
    protected $guarded = ['id'];

    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'lab_id','id');
    }
}
