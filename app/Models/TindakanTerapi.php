<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TindakanTerapi extends Model
{
    // Nama tabel (Sudah benar)
    protected $table = 'kode_tindakan_terapi';

    // 1. PERBAIKI PRIMARY KEY
    protected $primaryKey = 'idkode_tindakan_terapi';

    // 2. PERBAIKI FILLABLE
    protected $fillable = [
        'kode',
        'deskripsi_tindakan_terapi', // Sesuaikan dengan database
        'idkategori',
        'idkategori_klinis'
    ];
}