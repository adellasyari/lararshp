<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemilik extends Model
{
    use HasFactory;

    // 1. Menentukan nama tabel
    protected $table = 'pemilik';

    // 2. Menentukan primary key (misalnya 'idpemilik')
    //    Jika Anda tidak punya primary key, Anda bisa hapus baris ini
    //    atau set 'public $incrementing = false;'
    protected $primaryKey = 'idpemilik';

    // 3. Menentukan kolom yang boleh diisi
    protected $fillable = [
        'no_wa',
        'alamat',
        'iduser' // Foreign key ke tabel user
    ];

    /**
     * 4. Mendefinisikan relasi kebalikannya: "Satu Pemilik DIMILIKI OLEH satu User"
     * * Nama fungsi 'user' ini akan dicari oleh relasi 'pemilik()' di model User.
     * * Parameter belongsTo:
     * 1. Model tujuan: User::class
     * 2. Foreign key di tabel 'pemilik' ini: 'iduser'
     * 3. Owner key (primary key) di tabel 'user': 'iduser'
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'iduser');
    }

    public function pets()
    {
        return $this->hasMany(Pet::class, 'idpemilik', 'idpemilik');
    }   
    public function rekamMedis()
    {
        return $this->hasMany(RekamMedis::class, 'idpemilik', 'idpemilik');
    }
}
