<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Perawat extends Model
{
    use HasFactory, SoftDeletes;

    /** Nama tabel (ubah jika berbeda) */
    protected $table = 'perawat';

    /** Primary key */
    protected $primaryKey = 'id_perawat';

    /** Primary key akan di-set manual */
    public $incrementing = false;

    /** Tipe primary key (ubah ke 'string' jika UUID) */
    protected $keyType = 'int';

    /** Disable Eloquent timestamps (table does not have created_at/updated_at) */
    public $timestamps = false;

    /** Mass assignable */
    protected $fillable = [
        'alamat',
        'no_hp',
        'jenis_kelamin',
        'pendidikan',
        'id_user',
        'deleted_by',
    ];

    /** Relasi ke User */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

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
}
