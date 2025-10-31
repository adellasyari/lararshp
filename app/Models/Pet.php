<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// 1. Ubah nama class menjadi Pet
class Pet extends Model
{
    use HasFactory;

    // 2. Sesuaikan nama tabel
    protected $table = 'pet';

    // 3. Sesuaikan primary key
    protected $primaryKey = 'idpet';

    // 4. Sesuaikan kolom fillable (sesuai screenshot database)
    protected $fillable = [
        'nama',
        'tanggal_lahir',
        'warna_tanda',
        'jenis_kelamin',
        'idpemilik',
        'idras_hewan' // <-- Pastikan nama foreign key benar
    ];

    /**
     * Relasi: "Satu Pet DIMILIKI OLEH satu Pemilik"
     */
    public function pemilik()
    {
        return $this->belongsTo(Pemilik::class, 'idpemilik', 'idpemilik');
    }

    /**
     * Relasi: "Satu Pet MEMILIKI satu RasHewan"
     * Perhatikan foreign key 'idras_hewan' (dengan underscore)
     */
    public function rasHewan()
    {
        return $this->belongsTo(RasHewan::class, 'idras_hewan', 'idras_hewan');
    }

    public function rekamMedis()
    {
        return $this->hasMany(RekamMedis::class, 'idpet', 'idpet');
    }

    
}
