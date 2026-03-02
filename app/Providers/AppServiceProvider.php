<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $unreadMatchCount = 0;
            
            if (\Illuminate\Support\Facades\Auth::check()) {
                $userId = \Illuminate\Support\Facades\Auth::id();
                $userItemIds = \App\Models\Item::where('user_id', $userId)->pluck('id');
                
                // count any active matches (not dismissed), but ignore logs where both sides belong to the user
                $unreadMatchCount = \App\Models\SimilarityLog::where(function($query) use ($userItemIds) {
                        $query->whereIn('lost_item_id', $userItemIds)
                              ->orWhereIn('found_item_id', $userItemIds);
                    })
                    ->where(function($q) use ($userItemIds) {
                        $q->whereNotIn('lost_item_id', $userItemIds)
                          ->orWhereNotIn('found_item_id', $userItemIds);
                    })
                    ->whereDoesntHave('dismissedMatches', function ($query) use ($userId) {
                        $query->where('user_id', $userId);
                    })
                    ->count();
            }
            
            $view->with('unreadMatchCount', $unreadMatchCount);
        });
    }
}
