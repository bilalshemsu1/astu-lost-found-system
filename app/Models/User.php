<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'student_id', 
        'password', 'telegram_chat_id', 'telegram_username',
    ];

    protected $guarded = [
        'id', 'role', 'trust_score', 
        'telegram_verification_code', 'telegram_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'telegram_verified_at' => 'datetime',
        'trust_score' => 'integer',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function routeNotificationForMail(): string
    {
        return $this->email;
    }

}
