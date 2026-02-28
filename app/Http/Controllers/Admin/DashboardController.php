<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HasAdminViewData;
use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\Item;
use App\Models\SimilarityLog;

class DashboardController extends Controller
{
    use HasAdminViewData;

    public function index()
    {
        $lostCount = Item::where('type', 'lost')->count();
        $foundCount = Item::where('type', 'found')->count();
        $returnedCount = Item::where('status', 'returned')->count();

        $recentPendingItems = Item::with('user')
            ->where('status', 'pending_verification')
            ->latest()
            ->take(5)
            ->get();

        $recentMatches = SimilarityLog::with(['lostItem', 'foundItem'])
            ->latest()
            ->take(5)
            ->get();

        $pendingClaims = Claim::with(['item', 'user'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', array_merge([
            'lostCount' => $lostCount,
            'foundCount' => $foundCount,
            'returnedCount' => $returnedCount,
            'recentPendingItems' => $recentPendingItems,
            'recentMatches' => $recentMatches,
            'pendingClaims' => $pendingClaims,
        ], $this->navCounts()));
    }
}
