<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\DismissedMatch;
use App\Models\Item;
use App\Models\SimilarityLog;
use App\Services\ItemMatcher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class MatchesController extends Controller
{
    public function __construct(private ItemMatcher $matcher)
    {
    }

    public function index()
    {
        $userId = Auth::id();
        $userItems = Item::where('user_id', $userId)
            ->whereIn('status', ['active', 'pending_verification'])
            ->get();

        foreach ($userItems as $item) {
            $foundMatches = $this->matcher->findMatches($item);
            $this->matcher->saveMatches($item, $foundMatches);
        }

        $userItemIds = $userItems->pluck('id');

        $matches = SimilarityLog::where(function ($query) use ($userItemIds) {
                $query->whereIn('lost_item_id', $userItemIds)
                    ->orWhereIn('found_item_id', $userItemIds);
            })
            // filter out matches where both items are owned by current user
            ->where(function ($q) use ($userItemIds) {
                $q->whereNotIn('lost_item_id', $userItemIds)
                  ->orWhereNotIn('found_item_id', $userItemIds);
            })
            ->whereDoesntHave('dismissedMatches', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->whereHas('lostItem', function ($query) {
                $query->where('type', 'lost');
            })
            ->whereHas('foundItem', function ($query) {
                $query->where('type', 'found');
            })
            ->with(['lostItem', 'foundItem'])
            ->latest()
            ->paginate(10);

        $claimColumns = ['item_id'];
        if (Schema::hasColumn('claims', 'similarity_log_id')) {
            $claimColumns[] = 'similarity_log_id';
        }

        $existingClaims = Claim::where('user_id', $userId)
            ->whereIn('status', ['pending', 'approved'])
            ->get($claimColumns);

        return view('student.matches', compact('matches', 'existingClaims'));
    }

    public function dismiss(SimilarityLog $similarityLog): RedirectResponse
    {
        $userId = Auth::id();

        $ownsMatchedItem = Item::where('user_id', $userId)
            ->whereIn('id', [$similarityLog->lost_item_id, $similarityLog->found_item_id])
            ->exists();

        if (!$ownsMatchedItem) {
            abort(403, 'You are not allowed to dismiss this match.');
        }

        DismissedMatch::firstOrCreate([
            'user_id' => $userId,
            'similarity_log_id' => $similarityLog->id,
        ]);

        return redirect()->route('student.matches')->with('success', 'Match removed from your list.');
    }
}
