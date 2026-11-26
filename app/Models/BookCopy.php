<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookCopy extends Model
{
    use HasFactory;

    protected $table = 'book_copies';

    protected $fillable = [
        'book_id',
        'nomor_eksemplar',
        'kode_eksemplar',
        'rak',
        'status',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class, 'book_copy_id');
    }
}
