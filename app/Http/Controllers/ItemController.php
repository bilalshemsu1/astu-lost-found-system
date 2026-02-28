<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\SimilarityLog;
use App\Services\ItemMatcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{

    public function index(Request $request)
    {
        $query = Item::with('user')->where('status', 'active');

        if ($request->search) {
            $query->where('title', 'like', "%{$request->search}%");
        }
        if ($request->type) {
            $query->where('type', $request->type);
        }
        if ($request->category) {
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

        return view('student.items.index', compact('items'));
    }


    public function showLostForm()
    {
        return view('student.items.create-lost');
    }
    
    public function showFoundForm()
    {
        return view('student.items.create-found');
    }

    public function postLostItem(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'item_date' => 'required|date',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('items', 'public');
        }

        $validated['type'] = 'lost';
        $validated['status'] = 'pending_verification';
        $validated['user_id'] = Auth::id();

        Item::create($validated);

        return redirect()->route('student.items')->with('success', 'Posted!');
}

    public function postFoundItem(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'item_date' => 'required|date',
            'image' => 'required|image|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('items', 'public');
        }

        // Set defaults
        $validated['type'] = 'found';
        $validated['status'] = 'pending_verification';
        $validated['user_id'] = Auth::id();

        Item::create($validated);

        return redirect()->route('student.items')->with('success', 'Posted!');
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
            ->whereHas('lostItem', function ($query) {
                $query->where('type', 'lost');
            })
            ->whereHas('foundItem', function ($query) {
                $query->where('type', 'found');
            })
            ->with(['lostItem', 'foundItem'])
            ->latest()
            ->paginate(10);

        return view('student.matches', compact('matches'));
    }

}
