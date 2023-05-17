<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Posyandu extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
           'nama',
            'JK',
            'berat_badan',
            'tanggal_berkunjung',
            'vaksin',
            'nama_ibu',
            'biaya',
        ];
}