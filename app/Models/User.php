<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'wilayah_id',
        'nama',
        'no_hp',
        'gender',
        'usia',
        'alamat',
        'foto',
        'last_seen_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_seen_at' => 'datetime',
        ];
    }

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class);
    }

    public function usaha()
    {
        return $this->hasOne(Usaha::class);
    }

    public function chatsSent()
    {
        return $this->hasMany(Chat::class, 'sender_id');
    }

    public function chatsReceived()
    {
        return $this->hasMany(Chat::class, 'receiver_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function favoritProduks()
    {
        return $this->belongsToMany(Produk::class, 'favorits', 'user_id', 'produk_id')->withTimestamps();
    }
}
