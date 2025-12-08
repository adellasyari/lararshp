<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pemilik extends Model
{
    use HasFactory, SoftDeletes;

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
        'iduser', // Foreign key ke tabel user
        'deleted_by',
    ];

    // Non-aktifkan timestamps jika tabel tidak memiliki kolom created_at/updated_at
    public $timestamps = false;

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::deleting(function ($model) {
            if (auth()->check()) {
                $model->deleted_by = auth()->id();
                if (method_exists($model, 'saveQuietly')) {
                    $model->saveQuietly();
                } else {
                    $model->save();
                }
            }
        });
    }

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
        return $this->belongsTo(User::class, 'iduser');
    }

    public function pets()
    {
        return $this->hasMany(Pet::class, 'idpemilik', 'idpemilik');
    }   
    public function rekamMedis()
    {
        return $this->hasMany(RekamMedis::class, 'idpemilik', 'idpemilik');
    }

    public function temuDokters()
    {
        return $this->hasMany(TemuDokter::class, 'idpemilik', 'idpemilik');
    }
}
