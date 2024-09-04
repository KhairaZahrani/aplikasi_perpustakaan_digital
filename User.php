<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    protected $fillable = [
        'Username',
        'Password',
        'Email',
        'role',
        'NamaLengkap',
        'Alamat',
    ];

    protected $hidden = [
        'Password', // Sesuaikan dengan penulisan di database
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Menggunakan bcrypt untuk hashing password
    public function setPasswordAttribute($password){
        $this->attributes['Password'] = Hash::make($password);
    }
}
