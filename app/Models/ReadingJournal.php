<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReadingJournal extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    protected $table = 'reading_journals';


    /*
    |--------------------------------------------------------------------------
    | FILLABLE
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'borrowing_id',
        'user_id',
        'book_id',

        'ringkasan',
        'yang_dipelajari',
        'bagian_menarik',

        'verified_by',
        'verified_at',
    ];


    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'verified_at' => 'datetime',
    ];


    /*
    |--------------------------------------------------------------------------
    | RELASI PEMINJAMAN
    |--------------------------------------------------------------------------
    */

    public function borrowing()
    {
        return $this->belongsTo(
            Borrowing::class,
            'borrowing_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RELASI SISWA
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RELASI BUKU
    |--------------------------------------------------------------------------
    */

    public function book()
    {
        return $this->belongsTo(
            Book::class,
            'book_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RELASI PETUGAS VERIFIKATOR
    |--------------------------------------------------------------------------
    */

    public function verifier()
    {
        return $this->belongsTo(
            User::class,
            'verified_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SCOPE JURNAL SISWA
    |--------------------------------------------------------------------------
    */

    public function scopeMilikSiswa($query)
    {
        return $query->where(
            'user_id',
            auth()->id()
        );
    }


    /*
    |--------------------------------------------------------------------------
    | HELPER STATUS VERIFIKASI
    |--------------------------------------------------------------------------
    */

    public function sudahDiverifikasi(): bool
    {
        return !is_null($this->verified_by)
            && !is_null($this->verified_at);
    }


    /*
    |--------------------------------------------------------------------------
    | HELPER BELUM VERIFIKASI
    |--------------------------------------------------------------------------
    */

    public function belumDiverifikasi(): bool
    {
        return !$this->sudahDiverifikasi();
    }
}