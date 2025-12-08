<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

// PENTING: Tambahkan ini agar 'Pemilik::class' dikenali
use App\Models\Pemilik;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\Dokter;
use App\Models\Perawat;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    // --- PERUBAHAN DARI GAMBAR ---

    /**
     * Using default Laravel `users` table and `id` primary key
     * (legacy `user`/`iduser` removed after migration)
     */

    /**
     * 3. Kolom yang boleh diisi (mass assignable)
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'deleted_by',
    ];

    // The standard `users` table uses Laravel timestamps
    public $timestamps = true;

    /**
     * 4. Relasi ke tabel Pemilik
     * (Satu User memiliki satu data Pemilik)
     */
    public function pemilik()
    {
        // Parameter: (ModelTujuan, foreign_key, local_key)
        return $this->hasOne(Pemilik::class, 'iduser', 'id');
    }

    /**
     * Relasi ke data Dokter (jika user adalah dokter)
     */
    public function dokter()
    {
        return $this->hasOne(Dokter::class, 'id_user', 'id');
    }

    /**
     * Relasi ke data Perawat (jika user adalah perawat)
     */
    public function perawat()
    {
        return $this->hasOne(Perawat::class, 'id_user', 'id');
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
            'deleted_at' => 'datetime',
        ];
    }

    protected static function booted()
    {
        static::deleting(function ($model) {
            if (auth()->check()) {
                $model->deleted_by = auth()->id();
                // save quietly to avoid firing extra events
                if (method_exists($model, 'saveQuietly')) {
                    $model->saveQuietly();
                } else {
                    $model->save();
                }
            }
        });
    }
}