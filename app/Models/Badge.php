<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{

    use HasFactory;


    protected $fillable = [

        'nama',
        'icon',
        'deskripsi',

    ];



    /*
    |--------------------------------------------------------------------------
    | Relasi ke User
    |--------------------------------------------------------------------------
    */

    public function users()
    {

        return $this->belongsToMany(User::class);

    }

}