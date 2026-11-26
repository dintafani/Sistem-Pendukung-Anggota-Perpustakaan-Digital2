<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    /*
     Nama tabel database.
     */

    protected $table = 'inventaris';

    /*
     Primary key.
     */

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'kode_inventaris',
        'nama',
        'nama_barang',
        'kategori',
        'jumlah',
        'kondisi',
        'lokasi',
        'tanggal_pengadaan',
        'sumber_dana',
        'keterangan',
    ];

    /*
      Casting tipe data.
     */
    
    protected $casts = [
        'jumlah' => 'integer',
        'tanggal_pengadaan' => 'date',
    ];
}