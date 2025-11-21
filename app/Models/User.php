<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// PENTING: Tambahkan ini agar 'Pemilik::class' dikenali
use App\Models\Pemilik;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\Dokter;
use App\Models\Perawat;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // --- PERUBAHAN DARI GAMBAR ---

    /**
     * 1. Menentukan nama tabel kustom
     */
    protected $table = 'user';

    /**
     * 2. Menentukan primary key kustom
     */
    protected $primaryKey = 'iduser';

    /**
     * 3. Kolom yang boleh diisi (mass assignable)
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama', // Diubah dari 'name'
        'email',
        'password',
        'idrole', // Foreign key ke tabel role
    ];

    // The `user` table in this project does not have Laravel timestamp columns
    // so disable automatic timestamps to avoid inserting `created_at`/`updated_at`.
    public $timestamps = false;

    /**
     * 4. Relasi ke tabel Pemilik
     * (Satu User memiliki satu data Pemilik)
     */
    public function pemilik()
    {
        // Parameter: (ModelTujuan, foreign_key, local_key)
        return $this->hasOne(Pemilik::class, 'iduser', 'iduser');
    }

    /**
     * Relasi ke data Dokter (jika user adalah dokter)
     */
    public function dokter()
    {
        return $this->hasOne(Dokter::class, 'id_user', 'iduser');
    }

    /**
     * Relasi ke data Perawat (jika user adalah perawat)
     */
    public function perawat()
    {
        return $this->hasOne(Perawat::class, 'id_user', 'iduser');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'iduser', 'idrole')
            ->withPivot('status');
    }
    

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}