<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    protected $fillable = [
        'title',
        'description',
        'category',
        'type', 
        'verification_status',
        'verification_reason',
        'status',
        'image_path',
        'location',
        'item_date',
        'user_id',
    ];

    protected $casts = [
        'item_date' => 'date',
    ];

    // Relationship to User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
