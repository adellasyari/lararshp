<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriKlinis extends Model
{
    // 1. Menentukan nama tabel database
    protected $table = 'kategori_klinis';

    // 2. Menentukan primary key (jika bukan 'id')
    // NOTE: some parts of the codebase use 'idkategori_klinis' (no underscore)
    // make the model primaryKey match the actual DB column and relations.
    protected $primaryKey = 'idkategori_klinis';

    // 3. Menentukan kolom yang boleh diisi (untuk keamanan)
    protected $fillable = [
        'nama_kategori_klinis'
    ];
    
    // Non-aktifkan timestamps jika tabel tidak memiliki kolom created_at/updated_at
    public $timestamps = false;
}
