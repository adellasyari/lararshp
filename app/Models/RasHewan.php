<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RasHewan extends Model
{
    use HasFactory;

    // 1. Tentukan nama tabel
    protected $table = 'ras_hewan';

    // 2. Tentukan primary key (sesuaikan jika perlu)
    protected $primaryKey = 'idras_hewan';

    // 3. Tentukan kolom yang boleh diisi
    protected $fillable = [
        'nama_ras_hewan',
        'idjenis_hewan' // foreign key ke tabel jenis_hewan
    ];

    /**
     * 4. Relasi ke JenisHewan (Satu Ras Hewan dimiliki oleh satu Jenis Hewan).
     */
    public function jenisHewan()
    {
        // Parameter: (ModelTujuan, foreign_key, primary_key_tujuan)
        return $this->belongsTo(JenisHewan::class, 'idjenis_hewan', 'idjenis_hewan');
    }
}