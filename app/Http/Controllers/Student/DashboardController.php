<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\Item;
use App\Models\SimilarityLog;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $userItemsQuery = Item::where('user_id', $userId);

        $myLostCount = (clone $userItemsQuery)->where('type', 'lost')->count();
        $myFoundCount = (clone $userItemsQuery)->where('type', 'found')->count();
        $itemsReturnedCount = (clone $userItemsQuery)->where('status', 'returned')->count();

        $userItemIds = (clone $userItemsQuery)->pluck('id');
        $activeMatchesCount = SimilarityLog::where(function ($query) use ($userItemIds) {
            $query->whereIn('lost_item_id', $userItemIds)
                ->orWhereIn('found_item_id', $userItemIds);
        })
            ->whereDoesntHave('dismissedMatches', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->count();

        $recentItems = Item::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        $recentItemIds = $recentItems->pluck('id');
        $matchRows = SimilarityLog::where(function ($query) use ($recentItemIds) {
            $query->whereIn('lost_item_id', $recentItemIds)
                ->orWhereIn('found_item_id', $recentItemIds);
        })
            ->whereDoesntHave('dismissedMatches', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->get(['lost_item_id', 'found_item_id']);

        $matchedItemLookup = [];
        foreach ($matchRows as $row) {
            $matchedItemLookup[(int) $row->lost_item_id] = true;
            $matchedItemLookup[(int) $row->found_item_id] = true;
        }

        $recentItems = $recentItems->map(function ($item) use ($matchedItemLookup) {
            $item->has_match = isset($matchedItemLookup[(int) $item->id]);
            return $item;
        });

        $myLostItemIds = Item::where('user_id', $userId)
            ->where('type', 'lost')
            ->pluck('id');

        $recentMatchCandidates = SimilarityLog::with(['lostItem', 'foundItem'])
            ->whereIn('lost_item_id', $myLostItemIds)
            ->whereDoesntHave('dismissedMatches', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->latest()
            ->take(3)
            ->get();

        $recentClaims = Claim::with('item')
            ->where(function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->orWhereHas('item', function ($itemQuery) use ($userId) {
                        $itemQuery->where('user_id', $userId);
                    });
            })
            ->latest()
            ->take(5)
            ->get();

        $trustScore = (int) (Auth::user()->trust_score ?? 0);

        return view('student.dashboard', compact(
            'myLostCount',
            'myFoundCount',
            'itemsReturnedCount',
            'activeMatchesCount',
            'recentItems',
            'recentClaims',
            'recentMatchCandidates',
            'trustScore'
        ));
    }
}
