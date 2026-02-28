<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HasAdminViewData;
use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Services\ItemMatcher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ItemsController extends Controller
{
    use HasAdminViewData;

    public function __construct(private ItemMatcher $matcher)
    {
    }

    public function pending(Request $request)
    {
        $baseQuery = Item::where('status', 'pending_verification');
        $query = Item::with('user')->where('status', 'pending_verification');

        if (in_array($request->string('type')->toString(), ['lost', 'found'], true)) {
            $query->where('type', $request->string('type')->toString());
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('location', 'like', '%' . $search . '%');
            });
        }

        $items = $query->latest()->paginate(10)->withQueryString();

        return view('admin.pending-items', array_merge([
            'items' => $items,
            'totalPending' => (clone $baseQuery)->count(),
            'lostPending' => (clone $baseQuery)->where('type', 'lost')->count(),
            'foundPending' => (clone $baseQuery)->where('type', 'found')->count(),
            'todayPending' => (clone $baseQuery)->whereDate('created_at', now()->toDateString())->count(),
        ], $this->navCounts()));
    }

    public function index(Request $request)
    {
        $query = Item::with('user');

        if (in_array($request->string('type')->toString(), ['lost', 'found'], true)) {
            $query->where('type', $request->string('type')->toString());
        }

        if (in_array($request->string('status')->toString(), ['pending_verification', 'active', 'returned', 'rejected'], true)) {
            $query->where('status', $request->string('status')->toString());
        }

        if ($request->filled('category')) {
            $category = $request->string('category')->toString();
            if (array_key_exists($category, $this->categories())) {
                $query->where('category', $category);
            }
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('location', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->string('sort')->toString() === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $items = $query->paginate(12)->withQueryString();

        return view('admin.items.index', array_merge([
            'items' => $items,
            'categories' => $this->categories(),
            'totalItems' => Item::count(),
            'lostItems' => Item::where('type', 'lost')->count(),
            'foundItems' => Item::where('type', 'found')->count(),
            'returnedItems' => Item::where('status', 'returned')->count(),
            'activeItems' => Item::where('status', 'active')->count(),
        ], $this->navCounts()));
    }

    public function createFound()
    {
        return view('admin.items.create-found', array_merge([
            'categories' => $this->categories(),
        ], $this->navCounts()));
    }

    public function storeFound(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => ['required', 'string', 'max:100', Rule::in(array_keys($this->categories()))],
            'location' => 'required|string|max:255',
            'item_date' => 'required|date',
            'image' => 'required|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('items', 'public');
        }

        $validated['type'] = 'found';
        $validated['status'] = 'active';
        $validated['verification_status'] = 'approved';
        $validated['verification_reason'] = 'Posted directly by admin.';
        $validated['user_id'] = Auth::id();
        $validated['share_phone'] = false;
        $validated['share_telegram'] = false;
        $validated['return_location_preference'] = 'admin_office';

        $item = Item::create($validated);

        $this->syncItemMatches($item);

        return redirect()
            ->route('admin.items')
            ->with('success', 'Found item created successfully and matching run.');
    }

    public function approve(Item $item): RedirectResponse
    {
        $item->update(['status' => 'active']);

        if ($item->user) {
            $item->user->increment('trust_score');
        }

        $this->syncItemMatches($item);

        return redirect()->back()->with('success', 'Item approved successfully and matching run.');
    }

    public function reject(Request $request, Item $item): RedirectResponse
    {
        $request->validate([
            'reason' => 'nullable|string|max:1000',
            'rejection_reason' => 'nullable|string|max:1000',
        ]);

        $reason = $request->string('reason')->toString() ?: $request->string('rejection_reason')->toString();

        $item->update([
            'status' => 'rejected',
            'verification_reason' => $reason !== '' ? $reason : null,
        ]);

        if ($item->user) {
            $item->user->decrement('trust_score');
        }

        return redirect()->back()->with('success', 'Item rejected successfully.');
    }

    private function syncItemMatches(Item $item): void
    {
        $matches = $this->matcher->findMatches($item);
        $this->matcher->saveMatches($item, $matches);
    }
}
