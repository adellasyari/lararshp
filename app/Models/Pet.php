<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// 1. Ubah nama class menjadi Pet
class Pet extends Model
{
    use HasFactory, SoftDeletes;

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
        ,'deleted_by',
    ];

    // Non-aktifkan timestamps jika tabel tidak memiliki created_at/updated_at
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
