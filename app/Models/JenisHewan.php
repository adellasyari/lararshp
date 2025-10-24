<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisHewan extends Model
{
    // 1. Menentukan nama tabel database
    protected $table = 'jenis_hewan';

    // 2. Menentukan primary key (jika bukan 'id')
    protected $primaryKey = 'idjenis_hewan';

    // 3. Menentukan kolom yang boleh diisi (untuk keamanan)
    protected $fillable = [
        'nama_jenis_hewan'
    ];
}