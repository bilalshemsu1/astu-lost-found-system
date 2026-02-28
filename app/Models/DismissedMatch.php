<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DismissedMatch extends Model
{
    protected $fillable = [
        'user_id',
        'similarity_log_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function similarityLog(): BelongsTo
    {
        return $this->belongsTo(SimilarityLog::class);
    }
}

