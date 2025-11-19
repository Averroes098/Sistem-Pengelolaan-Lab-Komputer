<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';
    protected $guarded = ['id'];

    public function laboratorium(): BelongsTo {
        return $this->belongsTo(Laboratorium::class);
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function lab(): BelongsTo {
        return $this->belongsTo(Laboratorium::class, 'lab_id');
    }
}
