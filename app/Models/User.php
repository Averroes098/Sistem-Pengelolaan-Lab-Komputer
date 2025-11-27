<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property bool $is_profile_complete
 * @property string $nim
 * @property string $nama
 * @property string $email
 * @property string $password
 * @property string $level
 */
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
        'no_telp',
        'jenis_kelamin',
        'email',
        'password',
        'level',
        'program_studi',
        'angkatan',
        'alamat',
        'is_profile_complete',
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
     * Relasi ke tabel jadwal
     */
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'user_id', 'id');
    }

    /**
     * Relasi ke tabel documents (user sebagai uploader)
     */
    public function documents()
    {
        return $this->hasMany(Document::class, 'uploaded_by', 'id');
    }

    /**
     * Accessor: Mengecek apakah profil user sudah lengkap
     */
    public function getProfileCompletedAttribute(): bool
    {
        return !empty($this->nim)
            && !empty($this->no_telp)
            && !empty($this->jenis_kelamin);
    }
}
