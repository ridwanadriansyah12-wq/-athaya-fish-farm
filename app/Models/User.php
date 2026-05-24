<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements CanResetPasswordContract
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'nomor_telepon',
        'alamat',
        'foto_profil',
        'saldo',
        'google_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Send the password reset notification using our custom template.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Check if user is customer
     */
    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    /**
     * Check if user is pemilik
     */
    public function isPemilik()
    {
        return $this->role === 'pemilik';
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }



    /**
     * Relationships
     */
    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'customer_id');
    }

    public function keranjang()
    {
        return $this->hasMany(Keranjang::class, 'id_user');
    }

    public function penjualan()
    {
        return $this->hasMany(Penjualan::class, 'ID_User');
    }

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'User_ID');
    }

    public function preOrder()
    {
        return $this->hasMany(PreOrder::class, 'ID_User');
    }
}
