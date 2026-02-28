<?php

namespace App\Http\Controllers;

use App\Models\ClaimResponse;
use App\Models\Item;
use Illuminate\Database\QueryException;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $itemsRecovered = 0;
        $matchSuccessRate = 0;
        $activeStudents = 0;
        $avgResponseTimeLabel = 'N/A';
        $recentFoundItems = collect();

        try {
            $foundItemsCount = Item::where('type', 'found')->count();
            $itemsRecovered = Item::where('status', 'returned')->count();
            $matchSuccessRate = $foundItemsCount > 0
                ? (int) round(($itemsRecovered / $foundItemsCount) * 100)
                : 0;

            $activeStudents = Item::distinct('user_id')->count('user_id');
            $avgResponseTimeLabel = $this->averageResponseTimeLabel();
            $categoryLabels = config('items.categories', []);

            $recentFoundItems = Item::query()
                ->where('type', 'found')
                ->where('status', 'active')
                ->latest()
                ->take(6)
                ->get()
                ->map(function (Item $item) use ($categoryLabels) {
                    $item->category_label = $categoryLabels[$item->category]
                        ?? ucfirst(str_replace('_', ' ', $item->category));

                    return $item;
                });
        } catch (QueryException) {
            // Keep homepage renderable when DB/tables are unavailable.
        }

        return view('home', compact(
            'itemsRecovered',
            'matchSuccessRate',
            'activeStudents',
            'avgResponseTimeLabel',
            'recentFoundItems'
        ));
    }

    private function averageResponseTimeLabel(): string
    {
        $responses = ClaimResponse::query()
            ->with('claim:id,created_at')
            ->whereNotNull('response_at')
            ->latest('response_at')
            ->take(200)
            ->get();

        $durationsInMinutes = $responses
            ->map(function (ClaimResponse $response): ?int {
                if (!$response->claim?->created_at || !$response->response_at) {
                    return null;
                }

                return $response->claim->created_at->diffInMinutes($response->response_at);
            })
            ->filter(fn ($minutes) => is_int($minutes));

        if ($durationsInMinutes->isEmpty()) {
            return 'N/A';
        }

        $averageMinutes = (int) round($durationsInMinutes->avg());

        if ($averageMinutes < 60) {
            return $averageMinutes . 'm';
        }

        if ($averageMinutes < 1440) {
            return (int) round($averageMinutes / 60) . 'h';
        }

        return (int) round($averageMinutes / 1440) . 'd';
    }
}
