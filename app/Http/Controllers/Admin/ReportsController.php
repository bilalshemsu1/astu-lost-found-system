<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HasAdminViewData;
use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\Item;
use App\Models\SimilarityLog;

class ReportsController extends Controller
{
    use HasAdminViewData;

    public function index()
    {
        return $this->statistics();
    }

    public function statistics()
    {
        $totalItems = Item::count();
        $lostItems = Item::where('type', 'lost')->count();
        $foundItems = Item::where('type', 'found')->count();
        $returnedItems = Item::where('status', 'returned')->count();
        $totalClaims = Claim::count();
        $activeMatches = SimilarityLog::count();

        $returnRate = $foundItems > 0 ? round(($returnedItems / $foundItems) * 100, 1) : 0.0;

        $categoryBreakdown = Item::selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->take(8)
            ->get();

        return view('admin.reports', array_merge([
            'totalItems' => $totalItems,
            'lostItems' => $lostItems,
            'foundItems' => $foundItems,
            'returnedItems' => $returnedItems,
            'totalClaims' => $totalClaims,
            'activeMatches' => $activeMatches,
            'returnRate' => $returnRate,
            'categoryBreakdown' => $categoryBreakdown,
            'categories' => $this->categories(),
        ], $this->navCounts()));
    }
}
