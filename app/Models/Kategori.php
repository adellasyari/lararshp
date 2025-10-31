<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    // 1. Menentukan nama tabel database
    protected $table = 'kategori';

    // 2. Menentukan primary key (jika bukan 'id')
    protected $primaryKey = 'idkategori';

    // 3. Menentukan kolom yang boleh diisi (untuk keamanan)
    protected $fillable = [
        'nama_kategori'
    ];
}
