<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    protected $table = 'documents';
    protected $guarded = ['id'];

    /**
     * Relasi ke tabel laboratorium
     */
    public function laboratorium(): BelongsTo
    {
        return $this->belongsTo(Laboratorium::class, 'lab_id');
    }

    /**
     * Relasi ke tabel users (uploader)
     */
    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Relasi ke tabel alat (opsional, untuk laporan kerusakan)
     */
    public function alat(): BelongsTo
    {
        return $this->belongsTo(Alat::class, 'alat_id');
    }
}
