<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'student_id',
        'password',
        'telegram_chat_id',
        'telegram_username',
        'telegram_verification_code',
        'telegram_verified_at',
        'role',
        'trust_score',
        'is_anonymous',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'telegram_verified_at' => 'datetime',
        'trust_score' => 'integer',
        'is_anonymous' => 'boolean',
    ];
}
