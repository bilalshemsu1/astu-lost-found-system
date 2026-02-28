<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Student\Concerns\HasStudentItemCategories;
use App\Models\Claim;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class ItemsController extends Controller
{
    use HasStudentItemCategories;

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

        if ($request->date === 'today') {
            $query->whereDate('created_at', now()->toDateString());
        } elseif ($request->date === 'week') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($request->date === 'month') {
            $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
        }

        if ($request->sort === 'oldest') {
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

    public function storeLost(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => ['required', 'string', 'max:100', Rule::in(array_keys($this->categories()))],
            'location' => 'required|string|max:255',
            'item_date' => 'required|date|before_or_equal:today',
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

    public function storeFound(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => ['required', 'string', 'max:100', Rule::in(array_keys($this->categories()))],
            'location' => 'required|string|max:255',
            'item_date' => 'required|date|before_or_equal:today',
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

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('items', 'public');
        }

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
}
