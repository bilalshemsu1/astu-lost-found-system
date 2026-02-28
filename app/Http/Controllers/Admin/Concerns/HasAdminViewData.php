<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Models\Claim;
use App\Models\Item;

trait HasAdminViewData
{
    protected function navCounts(): array
    {
        return [
            'pendingCount' => Item::where('status', 'pending_verification')->count(),
            'pendingClaimsCount' => Claim::where('status', 'pending')->count(),
        ];
    }

    protected function categories(): array
    {
        return config('items.categories', []);
    }
}
