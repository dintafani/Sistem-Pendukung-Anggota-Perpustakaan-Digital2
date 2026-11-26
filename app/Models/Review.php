<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrowing_id',
        'book_id',
        'user_id',
        'rating',
        'ulasan',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relasi ke Borrowing
    |--------------------------------------------------------------------------
    */
    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Relasi ke Book
    |--------------------------------------------------------------------------
    */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Relasi ke User
    |--------------------------------------------------------------------------
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}