<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Notification;
use App\Models\Borrowing;
use App\Models\Review;
use App\Models\ReadingJournal;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'name',
        'email',
        'password',
        'nis',
        'kelas',
        'role',
        'status',
        'barcode',
        'foto',
        'poin',
        'no_telepon',
    ];

    /*
    |--------------------------------------------------------------------------
    | Hidden Attribute
    |--------------------------------------------------------------------------
    */

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /*
    |--------------------------------------------------------------------------
    | Cast Attribute
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'poin' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relasi Notifikasi
    |--------------------------------------------------------------------------
    */

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Relasi Peminjaman
    |--------------------------------------------------------------------------
    */

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Relasi Review
    |--------------------------------------------------------------------------
    */

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Relasi Jurnal Bacaan
    |--------------------------------------------------------------------------
    */

    public function readingJournals()
    {
        return $this->hasMany(ReadingJournal::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Total Poin
    |--------------------------------------------------------------------------
    */

    public function getTotalPoinAttribute()
    {
        return $this->poin ?? 0;
    }

    /*
    |--------------------------------------------------------------------------
    | Badge Gamifikasi Berdasarkan Poin
    |--------------------------------------------------------------------------
    */

    public function getBadgeAttribute()
    {
        $poin = $this->poin ?? 0;

        if ($poin >= 300) {
            return '🏆 Legend Literasi';
        }

        if ($poin >= 200) {
            return '👑 Master Literasi';
        }

        if ($poin >= 100) {
            return '📚 Pembaca Aktif';
        }

        if ($poin >= 50) {
            return '📘 Anggota Baru';
        }

        return '🌱 Pemula';
    }
}