<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemuDokter extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'temu_dokter';
    protected $primaryKey = 'idreservasi_dokter';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'idpet',
        'waktu_daftar',
        'no_urut',
        'idrole_user',
        'status',
        'deleted_by',
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

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'idpet', 'idpet');
    }

    public function pemilik()
    {
        // `temu_dokter` table has no `idpemilik` column in this schema — do not define this relation.
        return null;
    }

    /** Relasi ke role_user (dokter) */
    public function dokter()
    {
        return $this->belongsTo(RoleUser::class, 'idrole_user', 'idrole_user');
    }

    // relation to RekamMedis omitted because temu_dokter table has no idrekam_medis column

    // Status constants matching database values (two-state workflow)
    const STATUS_MENUNGGU = '0';
    const STATUS_DIPERIKSA = '1';

    /**
     * Accessor: human readable status label.
     * Map stored status codes to friendly strings used in the UI.
     * - 0 or '0' => "Tunggu"
     * - 1 or '1' => "Diperiksa"
     * - null or empty => "Tunggu" (default)
     */
    public function getStatusLabelAttribute()
    {
        $s = $this->attributes['status'] ?? null;
        // default to Menunggu
        if ($s === null || $s === '') {
            return 'Menunggu';
        }
        if ((string)$s === self::STATUS_DIPERIKSA) {
            return 'Diperiksa';
        }
        if ((string)$s === self::STATUS_MENUNGGU) {
            return 'Menunggu';
        }
        return (string) $s;
    }
}
