<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriKlinis extends Model
{
    // 1. Menentukan nama tabel database
    protected $table = 'kategori_klinis';

    // 2. Menentukan primary key (jika bukan 'id')
    protected $primaryKey = 'id_kategori_klinis';

    // 3. Menentukan kolom yang boleh diisi (untuk keamanan)
    protected $fillable = [
        'nama_kategori_klinis'
    ];
}
