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
        'share_phone',
        'share_telegram',
        'return_location_preference',
        'image_path',
        'location',
        'item_date',
        'user_id',
    ];

    protected $casts = [
        'item_date' => 'date',
        'share_phone' => 'boolean',
        'share_telegram' => 'boolean',
    ];

    // Relationship to User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function similarityLogs()
    {
        return $this->hasMany(SimilarityLog::class);
    }
}
