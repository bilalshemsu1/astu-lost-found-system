<?php

namespace App\View\Composers;

use App\Models\Item;
use App\Models\SimilarityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MatchCountComposer
{
    public function compose(View $view): void
    {
        if (!Auth::check()) {
            $view->with('unreadMatchCount', 0);
            return;
        }

        $userId = Auth::id();
        $userItemIds = Item::where('user_id', $userId)->pluck('id');

        $count = SimilarityLog::where(function ($query) use ($userItemIds) {
            $query->whereIn('lost_item_id', $userItemIds)
                ->orWhereIn('found_item_id', $userItemIds);
        })
            ->whereDoesntHave('dismissedMatches', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where('notified', false)
            ->count();

        $view->with('unreadMatchCount', $count);
    }
}
