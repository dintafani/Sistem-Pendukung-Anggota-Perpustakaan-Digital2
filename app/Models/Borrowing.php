<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    protected $table = 'borrowings';


    /*
    |--------------------------------------------------------------------------
    | FILLABLE
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'user_id',
        'book_id',
        'book_copy_id',
        'tanggal_pinjam',
        'tanggal_jatuh_tempo',
        'tanggal_kembali',
        'status',
    ];


    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'tanggal_pinjam'      => 'datetime',
        'tanggal_jatuh_tempo' => 'datetime',
        'tanggal_kembali'     => 'datetime',
    ];


    /*
    |--------------------------------------------------------------------------
    | RELASI USER
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

public function bookCopy()
{
    return $this->belongsTo(BookCopy::class, 'book_copy_id');
}
    /*
    |--------------------------------------------------------------------------
    | RELASI REVIEW
    |--------------------------------------------------------------------------
    */

    public function review()
    {
        return $this->hasOne(
            Review::class,
            'borrowing_id',
            'id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RELASI JURNAL
    |--------------------------------------------------------------------------
    */

    public function journal()
    {
        return $this->hasOne(
            ReadingJournal::class,
            'borrowing_id',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ALIAS READING JOURNAL
    |--------------------------------------------------------------------------
    */

    public function readingJournal()
    {
        return $this->journal();
    }


    /*
    |--------------------------------------------------------------------------
    | SCOPE PEMINJAMAN AKTIF
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->whereIn('status', [
            'menunggu',
            'dipinjam',
            'menunggu_pengembalian',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | SCOPE PEMINJAMAN SELESAI
    |--------------------------------------------------------------------------
    */

    public function scopeFinished($query)
    {
        return $query->where(
            'status',
            'dikembalikan'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SCOPE MASIH DIPINJAM
    |--------------------------------------------------------------------------
    */

    public function scopeStillBorrowed($query)
    {
        return $query->whereIn('status', [
            'dipinjam',
            'menunggu_pengembalian',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | SCOPE MENUNGGU
    |--------------------------------------------------------------------------
    */

    public function scopeWaiting($query)
    {
        return $query->where(
            'status',
            'menunggu'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SCOPE SUDAH DIKEMBALIKAN
    |--------------------------------------------------------------------------
    */

    public function scopeReturned($query)
    {
        return $query->where(
            'status',
            'dikembalikan'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CEK TERLAMBAT
    |--------------------------------------------------------------------------
    */

    public function getIsLateAttribute()
    {
        if (
            $this->status === 'dipinjam'
            && $this->tanggal_jatuh_tempo
            && now()->greaterThan(
                $this->tanggal_jatuh_tempo
            )
        ) {
            return true;
        }

        return false;
    }


    /*
    |--------------------------------------------------------------------------
    | SISA HARI
    |--------------------------------------------------------------------------
    */

    public function getDaysLeftAttribute()
    {
        if (
            !$this->tanggal_jatuh_tempo
            || !in_array(
                $this->status,
                [
                    'dipinjam',
                    'menunggu_pengembalian',
                ],
                true
            )
        ) {
            return null;
        }

        return now()
            ->startOfDay()
            ->diffInDays(
                $this->tanggal_jatuh_tempo->copy()->startOfDay(),
                false
            );
    }


    /*
    |--------------------------------------------------------------------------
    | CEK SUDAH DIKEMBALIKAN
    |--------------------------------------------------------------------------
    */

    public function getIsReturnedAttribute()
    {
        return $this->status === 'dikembalikan';
    }


    /*
    |--------------------------------------------------------------------------
    | CEK MASIH DIPINJAM
    |--------------------------------------------------------------------------
    */

    public function getIsBorrowedAttribute()
    {
        return in_array(
            $this->status,
            [
                'dipinjam',
                'menunggu_pengembalian',
            ],
            true
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CEK JURNAL SUDAH ADA
    |--------------------------------------------------------------------------
    */

    public function getHasJournalAttribute()
    {
        /*
        |--------------------------------------------------------------
        | Jika relasi journal sudah di-load
        |--------------------------------------------------------------
        */

        if ($this->relationLoaded('journal')) {
            return $this->journal !== null;
        }


        /*
        |--------------------------------------------------------------
        | Jika belum di-load, cek langsung database
        |--------------------------------------------------------------
        */

        return $this->journal()->exists();
    }


    /*
    |--------------------------------------------------------------------------
    | AMBIL JURNAL
    |--------------------------------------------------------------------------
    */

    public function getJournalDataAttribute()
    {
        return $this->journal;
    }


    /*
    |--------------------------------------------------------------------------
    | SCOPE HISTORY
    |--------------------------------------------------------------------------
    */

    public function scopeWithHistory($query)
    {
        return $query->with([
            'user',
            'book',
            'bookCopy',
            'review',
            'journal',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | SCOPE PETUGAS SCAN
    |--------------------------------------------------------------------------
    */

    public function scopeForPetugasScan($query)
    {
        return $query->with([
            'user',
            'book',
            'bookCopy',
            'review',
            'journal',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | SCOPE HISTORI SISWA
    |--------------------------------------------------------------------------
    */

    public function scopeForStudentHistory($query)
    {
        return $query->with([
            'book',
            'bookCopy',
            'review',
            'journal',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | SCOPE WITH JOURNAL
    |--------------------------------------------------------------------------
    */

    public function scopeWithJournal($query)
    {
        return $query->with([
            'book',
            'journal',
        ]);
    }
}