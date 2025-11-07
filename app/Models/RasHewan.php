<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RasHewan extends Model
{
    use HasFactory;

    // Disable timestamps if your table does not have created_at/updated_at
    public $timestamps = false;

    // 1. Tentukan nama tabel
    protected $table = 'ras_hewan';

    // 2. Tentukan primary key (sesuaikan jika perlu)
    protected $primaryKey = 'idras_hewan';

    // 3. Tentukan kolom yang boleh diisi
    // NOTE: database column is `nama_ras` (see SQL schema). Keep both a direct fillable
    // column and an accessor for backward compatibility with views using
    // `nama_ras_hewan`.
    protected $fillable = [
        'nama_ras',
        'idjenis_hewan' // foreign key ke tabel jenis_hewan
    ];

    /**
     * Backward-compatible accessor so views referencing `nama_ras_hewan`
     * (older code) will still work. Allows $rasHewan->nama_ras_hewan.
     */
    public function getNamaRasHewanAttribute()
    {
        return $this->attributes['nama_ras'] ?? null;
    }

    /**
     * 4. Relasi ke JenisHewan (Satu Ras Hewan dimiliki oleh satu Jenis Hewan).
     */
    public function jenisHewan()
    {
        // Parameter: (ModelTujuan, foreign_key, primary_key_tujuan)
        return $this->belongsTo(JenisHewan::class, 'idjenis_hewan', 'idjenis_hewan');
    }
}