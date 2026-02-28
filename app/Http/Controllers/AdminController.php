<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Item;
use App\Models\SimilarityLog;
use App\Models\User;
use App\Services\ItemMatcher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    public function dashboard()
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

    public function pendingItems(Request $request)
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

    public function items(Request $request)
    {
        $query = Item::with('user');

        if (in_array($request->string('type')->toString(), ['lost', 'found'], true)) {
            $query->where('type', $request->string('type')->toString());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        if ($request->filled('category')) {
            $query->where('category', $request->string('category')->toString());
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
            'totalItems' => Item::count(),
            'lostItems' => Item::where('type', 'lost')->count(),
            'foundItems' => Item::where('type', 'found')->count(),
            'returnedItems' => Item::where('status', 'returned')->count(),
            'activeItems' => Item::where('status', 'active')->count(),
        ], $this->navCounts()));
    }

    public function matches(Request $request)
    {
        $query = SimilarityLog::with(['lostItem.user', 'foundItem.user']);

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->whereHas('lostItem', function ($itemQuery) use ($search) {
                    $itemQuery->where('title', 'like', '%' . $search . '%');
                })->orWhereHas('foundItem', function ($itemQuery) use ($search) {
                    $itemQuery->where('title', 'like', '%' . $search . '%');
                });
            });
        }

        if ($request->filled('min_similarity')) {
            $query->where('similarity_percentage', '>=', (float) $request->input('min_similarity'));
        }

        if (in_array($request->string('notified')->toString(), ['yes', 'no'], true)) {
            $query->where('notified', $request->string('notified')->toString() === 'yes');
        }

        $matches = $query->latest()->paginate(10)->withQueryString();

        return view('admin.matches', array_merge([
            'matches' => $matches,
            'totalMatches' => SimilarityLog::count(),
            'highMatches' => SimilarityLog::where('similarity_percentage', '>=', 90)->count(),
            'mediumMatches' => SimilarityLog::whereBetween('similarity_percentage', [80, 89.99])->count(),
            'notifiedMatches' => SimilarityLog::where('notified', true)->count(),
        ], $this->navCounts()));
    }

    public function claims(Request $request)
    {
        $query = Claim::with(['item.user', 'user', 'similarityLog']);
        $hasSimilarityLogId = Schema::hasColumn('claims', 'similarity_log_id');

        if (in_array($request->string('status')->toString(), ['pending', 'approved', 'rejected'], true)) {
            $query->where('status', $request->string('status')->toString());
        }

        if ($hasSimilarityLogId) {
            if ($request->string('source')->toString() === 'similarity_match') {
                $query->whereNotNull('similarity_log_id');
            } elseif ($request->string('source')->toString() === 'direct_ownership_request') {
                $query->whereNull('similarity_log_id');
            }
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('proof', 'like', '%' . $search . '%')
                    ->orWhereHas('item', function ($itemQuery) use ($search) {
                        $itemQuery->where('title', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $claims = $query->latest()->paginate(10)->withQueryString();

        return view('admin.claims', array_merge([
            'claims' => $claims,
            'claimsTotal' => Claim::count(),
            'claimsPending' => Claim::where('status', 'pending')->count(),
            'claimsApproved' => Claim::where('status', 'approved')->count(),
            'claimsRejected' => Claim::where('status', 'rejected')->count(),
            'similarityClaims' => $hasSimilarityLogId ? Claim::whereNotNull('similarity_log_id')->count() : 0,
            'directClaims' => $hasSimilarityLogId ? Claim::whereNull('similarity_log_id')->count() : 0,
            'hasSimilarityLogId' => $hasSimilarityLogId,
        ], $this->navCounts()));
    }

    public function users(Request $request)
    {
        $query = User::query()->withCount([
            'items',
            'items as lost_items_count' => static fn ($q) => $q->where('type', 'lost'),
            'items as found_items_count' => static fn ($q) => $q->where('type', 'found'),
            'items as returned_items_count' => static fn ($q) => $q->where('status', 'returned'),
        ]);

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('student_id', 'like', '%' . $search . '%');
            });
        }

        if (in_array($request->string('role')->toString(), ['student', 'admin'], true)) {
            $query->where('role', $request->string('role')->toString());
        }

        $sort = $request->string('sort')->toString();
        if ($sort === 'trust_desc') {
            $query->orderByDesc('trust_score');
        } elseif ($sort === 'trust_asc') {
            $query->orderBy('trust_score');
        } elseif ($sort === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $users = $query->paginate(12)->withQueryString();

        return view('admin.users.index', array_merge([
            'users' => $users,
            'totalUsers' => User::count(),
            'studentUsers' => User::where('role', 'student')->count(),
            'adminUsers' => User::where('role', 'admin')->count(),
            'activeTodayUsers' => User::whereDate('updated_at', now()->toDateString())->count(),
        ], $this->navCounts()));
    }

    public function createUser()
    {
        return view('admin.users.create', $this->navCounts());
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
        ], $this->navCounts()));
    }

    public function reports()
    {
        return $this->statistics();
    }

    public function approveItem(Request $request, Item $item): RedirectResponse
    {
        $item->update(['status' => 'active']);

        if ($item->user) {
            $item->user->increment('trust_score');
        }

        /** @var ItemMatcher $matcher */
        $matcher = app(ItemMatcher::class);
        $matches = $matcher->findMatches($item);
        $matcher->saveMatches($item, $matches);

        return redirect()->back()->with('success', 'Item approved successfully and matching run.');
    }

    public function rejectItem(Request $request, Item $item): RedirectResponse
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

    private function navCounts(): array
    {
        return [
            'pendingCount' => Item::where('status', 'pending_verification')->count(),
            'pendingClaimsCount' => Claim::where('status', 'pending')->count(),
        ];
    }
}

