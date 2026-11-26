<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poster extends Model
{
    use HasFactory;

    protected $table = 'posters';

    protected $fillable = [
        'book_id',
        'judul',
        'deskripsi',
        'gambar',
        'status',
        'urutan',
    ];

    protected $casts = [
        'status' => 'boolean',
        'urutan' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI KE BUKU
    |--------------------------------------------------------------------------
    */

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPE POSTER AKTIF
    |--------------------------------------------------------------------------
    */

    public function scopeAktif($query)
    {
        return $query
            ->where('status', true)
            ->orderBy('urutan')
            ->orderByDesc('id');
    }
}