<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SimilarityLog extends Model
{
    protected $table = 'similarity_logs';

    protected $fillable = [
        'lost_item_id',
        'found_item_id',
        'similarity_percentage',
        'title_match',
        'category_match',
        'description_match',
        'location_match',
        'date_match',
        'notified',
    ];

    protected $casts = [
        'similarity_percentage' => 'float',
        'title_match' => 'float',
        'category_match' => 'float',
        'description_match' => 'float',
        'location_match' => 'float',
        'date_match' => 'float',
        'notified' => 'boolean',
    ];

    // Relationships
    public function lostItem(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'lost_item_id');
    }

    public function foundItem(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'found_item_id');
    }
    
}
