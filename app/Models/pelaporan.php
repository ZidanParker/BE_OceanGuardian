<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pelaporan extends Model
{
    protected $table = 'pelaporan'; // Nama tabel

    protected $primaryKey = 'id_pelaporan'; // Nama kolom primary key
    
    protected $fillable = [
        'nama',
        'provinsi',
        'lokasi',
        'jenis_laporan',
        'gambar',
    ];
}
