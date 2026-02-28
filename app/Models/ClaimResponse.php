<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClaimResponse extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'claim_id',
        'finder_responded',
        'finder_shares_contact',
        'response_at',
        'handover_confirmed_at',
        'confirmed_by_admin_id',
    ];

    protected $casts = [
        'finder_responded' => 'boolean',
        'finder_shares_contact' => 'boolean',
        'response_at' => 'datetime',
        'handover_confirmed_at' => 'datetime',
    ];

    public function claim(): BelongsTo
    {
        return $this->belongsTo(Claim::class);
    }

    public function confirmedByAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by_admin_id');
    }
}
