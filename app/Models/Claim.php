<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Claim extends Model
{
    protected $fillable = [
        'item_id',
        'user_id',
        'similarity_log_id',
        'similarity_score',
        'similarity_details',
        'proof',
        'status',
        'admin_decision',
        'admin_notes',
    ];

    protected $casts = [
        'similarity_score' => 'float',
        'similarity_details' => 'array',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function similarityLog(): BelongsTo
    {
        return $this->belongsTo(SimilarityLog::class);
    }
}
