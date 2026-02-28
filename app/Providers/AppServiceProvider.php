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
                
                $unreadMatchCount = \App\Models\SimilarityLog::where(function($query) use ($userItemIds) {
                        $query->whereIn('lost_item_id', $userItemIds)
                              ->orWhereIn('found_item_id', $userItemIds);
                    })
                    ->where('notified', false)
                    ->count();
            }
            
            $view->with('unreadMatchCount', $unreadMatchCount);
        });
    }
}
