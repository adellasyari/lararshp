<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;

    /** Nama tabel (ubah jika berbeda) */
    protected $table = 'dokter';

    /** Primary key */
    protected $primaryKey = 'id_dokter';

    /** Primary key akan di-set manual */
    public $incrementing = false;

    /** Nonaktifkan timestamps Eloquent (tidak ada kolom created_at/updated_at di tabel) */
    public $timestamps = false;

    /** Tipe primary key (ubah ke 'string' jika UUID) */
    protected $keyType = 'int';

    /** Mass assignable */
    protected $fillable = [
        'alamat',
        'no_hp',
        'bidang_dokter',
        'jenis_kelamin',
        'id_user',
    ];

    /** Relasi ke User */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'iduser');
    }
}
