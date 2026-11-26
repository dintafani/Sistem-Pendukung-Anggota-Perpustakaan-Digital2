<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AcademicSetting extends Model
{
    use HasFactory;

    protected $table = 'academic_settings';

    protected $fillable = [

        'tahun_ajaran',

        'semester',

        'tanggal_mulai',

        'tanggal_selesai',

        'batas_paket_kelas_10',

        'batas_paket_kelas_11',

        'batas_paket_kelas_12',

        'aktif',
         'mode_ujian',
    ];


    protected $casts = [

        'semester' => 'integer',

        'tanggal_mulai' => 'date',

        'tanggal_selesai' => 'date',

        'batas_paket_kelas_10' => 'integer',

        'batas_paket_kelas_11' => 'integer',

        'batas_paket_kelas_12' => 'integer',

        'aktif' => 'boolean',
        'mode_ujian' => 'boolean',
    ];


    /*
    |--------------------------------------------------------------------------
    | PENGATURAN AKADEMIK AKTIF
    |--------------------------------------------------------------------------
    */

    public static function aktif()
    {
        return static::query()
            ->where('aktif', true)
            ->latest('id')
            ->first();
    }
}