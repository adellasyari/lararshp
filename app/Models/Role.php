<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    // 1. Menentukan nama tabel
    protected $table = 'role';

    // 2. Menentukan primary key
    protected $primaryKey = 'idrole';

    // 3. Menentukan kolom yang boleh diisi
    protected $fillable = [
        'nama_role'
    ];
}
