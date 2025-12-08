<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RekamMedis extends Model
{
    use SoftDeletes;
    protected $table = 'rekam_medis';
    protected $primaryKey = 'idrekam_medis';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'created_at',
        'anamnesa',
        'diagnosa',
        'temuan_klinis',
        'idpet',
        'dokter_pemeriksa',
        'status_verifikasi',
    ];

    // Table only has created_at (no updated_at), so disable automatic timestamps
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

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'idpet', 'idpet');
    }

    public function roleUser()
    {
        return $this->belongsTo(RoleUser::class, 'dokter_pemeriksa', 'idrole_user');
    }
    
    /**
     * Alias/shortcut to access dokter (User) easily from RekamMedis.
     * This returns the RoleUser relation (dokter assignment) —
     * we also provide a dynamic attribute accessor 'dokter' that returns the related User.
     */
    public function dokter()
    {
        return $this->belongsTo(RoleUser::class, 'dokter_pemeriksa', 'idrole_user');
    }

    /**
     * Dynamic attribute to get the User model of the dokter pemeriksa.
     * Usage: $rekamMedis->dokter->nama
     */
    public function getDokterAttribute()
    {
        return $this->roleUser ? $this->roleUser->user : null;
    }
    
    public function pemilik()
    {
        return $this->belongsTo(Pemilik::class, 'idpemilik', 'idpemilik');
    }       

    public function temuDokter()
    {
        return $this->hasMany(TemuDokter::class, 'idrekam_medis', 'idrekam_medis');
    }   

    public function detail_rekam_medis()
    {
        return $this->hasMany(DetailRekamMedis::class, 'idrekam_medis', 'idrekam_medis');
    }
    
}
