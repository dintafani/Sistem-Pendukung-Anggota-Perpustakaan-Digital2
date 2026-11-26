<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'penulis',
        'penerbit',
        'tahun',
        'kategori',
        'jenis_buku',
        'semester',
        'rak',
        'stok',
        'jumlah_dipinjam',
        'deskripsi',
        'cover',
    ];


    /*
    |--------------------------------------------------------------------------
    | Relasi ke Borrowing
    |--------------------------------------------------------------------------
    */

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

public function copies()
{
    return $this->hasMany(BookCopy::class, 'book_id');
}

public function availableCopies()
{
    return $this->copies()->where('status', 'tersedia');
}
    /*
    |--------------------------------------------------------------------------
    | Relasi ke Review
    |--------------------------------------------------------------------------
    */

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }


    /*
    |--------------------------------------------------------------------------
    | Relasi ke Reading Journal
    |--------------------------------------------------------------------------
    */

    public function readingJournals()
    {
        return $this->hasMany(ReadingJournal::class);
    }


    /*
    |--------------------------------------------------------------------------
    | Relasi ke Poster
    |--------------------------------------------------------------------------
    */

    public function posters()
    {
        return $this->hasMany(Poster::class);
    }


    /*
    |--------------------------------------------------------------------------
    | Poster Aktif
    |--------------------------------------------------------------------------
    */

    public function activePosters()
    {
        return $this->hasMany(Poster::class)
            ->where('status', true)
            ->orderBy('urutan')
            ->orderByDesc('id');
    }


    /*
    |--------------------------------------------------------------------------
    | Rata-rata Rating
    |--------------------------------------------------------------------------
    */

    public function getAverageRatingAttribute()
    {
        $rating = $this->reviews()->avg('rating');

        return $rating
            ? round($rating, 1)
            : 0;
    }


    /*
    |--------------------------------------------------------------------------
    | Jumlah Ulasan
    |--------------------------------------------------------------------------
    */

    public function getTotalReviewAttribute()
    {
        return $this->reviews()->count();
    }
}