<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekamMedis extends Model
{
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

    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'idpet', 'idpet');
    }

    public function roleUser()
    {
        return $this->belongsTo(RoleUser::class, 'dokter_pemeriksa', 'idrole_user');
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
