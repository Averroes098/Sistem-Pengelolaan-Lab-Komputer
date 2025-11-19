<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nim',
        'nama',
        'email',
        'password',
        'level',
        'program_studi',
        'angkatan',
        'alamat',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke tabel peminjaman
     */
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'user_id', 'id');
    }

    /**
     * Accessor: Mengecek apakah profil user sudah lengkap
     */
    public function getIsProfileCompleteAttribute(): bool
    {
        return !empty($this->program_studi)
            && !empty($this->angkatan)
            && !empty($this->alamat);
    }
}