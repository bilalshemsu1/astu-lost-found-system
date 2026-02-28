<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\DismissedMatch;
use App\Models\Item;
use App\Models\SimilarityLog;
use App\Services\ItemMatcher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    public function dashboard()
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

    public function index(Request $request)
    {
        $userId = Auth::id();
        $categories = $this->categories();
        $query = Item::with('user')
            ->where('status', 'active')
            ->whereIn('type', ['lost', 'found']);

        if ($request->search) {
            $query->where('title', 'like', "%{$request->search}%");
        }
        if ($request->type) {
            $query->where('type', $request->type);
        }
        if ($request->category && array_key_exists($request->category, $categories)) {
            $query->where('category', $request->category);
        }
        if ($request->location) {
            $query->where('location', 'like', "%{$request->location}%");
        }
        
        // Date filter logic
        if ($request->date == 'today') {
            $query->whereDate('created_at', now()->toDateString());
        } elseif ($request->date == 'week') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($request->date == 'month') {
            $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
        }

        // Sort logic
        if ($request->sort == 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $items = $query->paginate(12)->withQueryString();
        $existingClaimQuery = Claim::where('user_id', $userId)
            ->whereIn('status', ['pending', 'approved']);

        if (Schema::hasColumn('claims', 'similarity_log_id')) {
            $existingClaimQuery->whereNull('similarity_log_id');
        }

        $claimedItemIds = $existingClaimQuery->pluck('item_id');

        return view('student.items.index', compact('items', 'claimedItemIds', 'categories'));
    }


    public function showLostForm()
    {
        $categories = $this->categories();

        return view('student.items.create-lost', compact('categories'));
    }
    
    public function showFoundForm()
    {
        $categories = $this->categories();

        return view('student.items.create-found', compact('categories'));
    }

    public function myItems(Request $request)
    {
        $userId = Auth::id();
        $query = Item::where('user_id', $userId);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if (in_array($request->type, ['lost', 'found'], true)) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->sort === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $items = $query->paginate(12)->withQueryString();

        $counts = [
            'total' => Item::where('user_id', $userId)->count(),
            'lost' => Item::where('user_id', $userId)->where('type', 'lost')->count(),
            'found' => Item::where('user_id', $userId)->where('type', 'found')->count(),
        ];

        return view('student.items.my-items', compact('items', 'counts'));
    }

    public function postLostItem(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => ['required', 'string', 'max:100', Rule::in(array_keys($this->categories()))],
            'location' => 'required|string|max:255',
            'item_date' => 'required|date',
            'image' => 'nullable|image|max:2048',
            'share_phone' => 'nullable|boolean',
            'share_telegram' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('items', 'public');
        }

        $validated['type'] = 'lost';
        $validated['status'] = 'pending_verification';
        $validated['user_id'] = Auth::id();
        $validated['share_phone'] = $request->boolean('share_phone');
        $validated['share_telegram'] = $request->boolean('share_telegram');
        $validated['return_location_preference'] = null;

        Item::create($validated);

        return redirect()->route('student.my-items')->with('success', 'Posted!');
}

    public function postFoundItem(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => ['required', 'string', 'max:100', Rule::in(array_keys($this->categories()))],
            'location' => 'required|string|max:255',
            'item_date' => 'required|date',
            'image' => 'required|image|max:2048',
            'return_location' => ['required', Rule::in(['admin_office', 'direct'])],
            'share_phone' => 'nullable|boolean',
            'share_telegram' => 'nullable|boolean',
        ]);

        if (
            $validated['return_location'] === 'direct'
            && !$request->boolean('share_phone')
            && !$request->boolean('share_telegram')
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'share_phone' => 'For direct handover, enable at least one contact method (phone or Telegram).',
                ]);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('items', 'public');
        }

        // Set defaults
        $validated['type'] = 'found';
        $validated['status'] = 'pending_verification';
        $validated['user_id'] = Auth::id();
        $validated['share_phone'] = $request->boolean('share_phone');
        $validated['share_telegram'] = $request->boolean('share_telegram');
        $validated['return_location_preference'] = $validated['return_location'];
        unset($validated['return_location']);

        Item::create($validated);

        return redirect()->route('student.my-items')->with('success', 'Posted!');
    }

    public function matches(ItemMatcher $matcher)
    {
        $userId = Auth::id();
        $userItems = Item::where('user_id', $userId)
            ->whereIn('status', ['active', 'pending_verification'])
            ->get();

        foreach ($userItems as $item) {
            $foundMatches = $matcher->findMatches($item);
            $matcher->saveMatches($item, $foundMatches);
        }

        $userItemIds = $userItems->pluck('id');
        
        $matches = SimilarityLog::where(function($query) use ($userItemIds) {
                $query->whereIn('lost_item_id', $userItemIds)
                    ->orWhereIn('found_item_id', $userItemIds);
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

    public function dismissMatch(SimilarityLog $similarityLog): RedirectResponse
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

    private function categories(): array
    {
        return config('items.categories', []);
    }

}
