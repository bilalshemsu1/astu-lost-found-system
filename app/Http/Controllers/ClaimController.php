<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Item;
use App\Models\SimilarityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ClaimController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $status = $request->string('status')->toString();
        $source = $request->string('source')->toString();

        $claimsQuery = Claim::where('user_id', $userId);

        if (in_array($status, ['pending', 'approved', 'rejected'], true)) {
            $claimsQuery->where('status', $status);
        }

        if ($source === 'similarity_match') {
            if (Schema::hasColumn('claims', 'similarity_log_id')) {
                $claimsQuery->whereNotNull('similarity_log_id');
            }
        } elseif ($source === 'direct_ownership_request') {
            if (Schema::hasColumn('claims', 'similarity_log_id')) {
                $claimsQuery->whereNull('similarity_log_id');
            }
        }

        $claims = $claimsQuery
            ->with(['item.user', 'similarityLog.lostItem', 'similarityLog.foundItem', 'claimResponse'])
            ->latest()
            ->paginate(10);

        $baseQuery = Claim::where('user_id', $userId);
        $counts = [
            'total' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'approved' => (clone $baseQuery)->where('status', 'approved')->count(),
            'rejected' => (clone $baseQuery)->where('status', 'rejected')->count(),
            'similarity_match' => Schema::hasColumn('claims', 'similarity_log_id')
                ? (clone $baseQuery)->whereNotNull('similarity_log_id')->count()
                : 0,
            'direct_ownership_request' => Schema::hasColumn('claims', 'similarity_log_id')
                ? (clone $baseQuery)->whereNull('similarity_log_id')->count()
                : 0,
        ];

        return view('student.claim', compact('claims', 'counts'));
    }

    public function create(SimilarityLog $similarityLog)
    {
        return redirect()->route('student.matches');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'similarity_log_id' => 'nullable|exists:similarity_logs,id|required_without:item_id',
            'item_id' => 'nullable|exists:items,id|required_without:similarity_log_id',
            'proof' => 'required|string|min:10',
        ]);

        $userId = Auth::id();

        if (!empty($validated['similarity_log_id'])) {
            return $this->storeSimilarityClaim((int) $validated['similarity_log_id'], $validated['proof'], $userId);
        }

        return $this->storeDirectOwnershipClaim((int) $validated['item_id'], $validated['proof'], $userId);
    }

    private function storeSimilarityClaim(int $similarityLogId, string $proof, int $userId): RedirectResponse
    {
        $similarityLog = SimilarityLog::with(['lostItem', 'foundItem'])->findOrFail($similarityLogId);
        $userItemIds = Item::where('user_id', $userId)->pluck('id');

        $ownsMatchedItem = $userItemIds->contains($similarityLog->lost_item_id)
            || $userItemIds->contains($similarityLog->found_item_id);

        if (!$ownsMatchedItem) {
            abort(403, 'You are not allowed to claim this match.');
        }

        // Only the lost-item owner should claim the found item.
        $isLostOwner = $userItemIds->contains($similarityLog->lost_item_id);
        if (!$isLostOwner) {
            return back()->withErrors([
                'proof' => 'Only the owner of the lost item can submit this claim.',
            ]);
        }

        $itemId = $similarityLog->foundItem?->id;
        if (!$itemId) {
            return back()->withErrors(['proof' => 'Matched item is unavailable.']);
        }

        if ((int) ($similarityLog->foundItem?->user_id ?? 0) === $userId) {
            return back()->withErrors([
                'proof' => 'You cannot claim your own posted found item.',
            ]);
        }

        $existingClaimQuery = Claim::where('user_id', $userId)
            ->where('item_id', $itemId)
            ->where('status', 'pending');

        if (Schema::hasColumn('claims', 'similarity_log_id')) {
            $existingClaimQuery->where('similarity_log_id', $similarityLog->id);
        }

        if ($existingClaimQuery->exists()) {
            return back()->withErrors([
                'proof' => 'You already submitted a pending claim for this match.',
            ]);
        }

        $payload = [
            'item_id' => $itemId,
            'user_id' => $userId,
            'similarity_score' => $similarityLog->similarity_percentage,
            'proof' => $proof,
            'status' => 'pending',
        ];

        if (Schema::hasColumn('claims', 'similarity_log_id')) {
            $payload['similarity_log_id'] = $similarityLog->id;
        }

        if (Schema::hasColumn('claims', 'similarity_details')) {
            $payload['similarity_details'] = [
                'source' => 'similarity_match',
                'total' => $similarityLog->similarity_percentage,
                'title' => $similarityLog->title_match,
                'category' => $similarityLog->category_match,
                'description' => $similarityLog->description_match,
                'location' => $similarityLog->location_match,
                'date' => $similarityLog->date_match,
                'lost_item_id' => $similarityLog->lost_item_id,
                'found_item_id' => $similarityLog->found_item_id,
            ];
        }

        Claim::create($payload);

        return redirect()->route('student.claims')->with('success', 'Claim submitted!');
    }

    private function storeDirectOwnershipClaim(int $itemId, string $proof, int $userId): RedirectResponse
    {
        $item = Item::findOrFail($itemId);

        if ($item->type !== 'found') {
            return back()->withErrors([
                'proof' => 'Ownership request is only available for found items.',
            ]);
        }

        if (!in_array($item->status, ['active', 'pending_verification'], true)) {
            return back()->withErrors([
                'proof' => 'This item is not available for claim.',
            ]);
        }

        if ((int) $item->user_id === $userId) {
            return back()->withErrors([
                'proof' => 'You cannot claim your own posted item.',
            ]);
        }

        $existingClaimQuery = Claim::where('user_id', $userId)
            ->where('item_id', $item->id)
            ->where('status', 'pending');

        if (Schema::hasColumn('claims', 'similarity_log_id')) {
            $existingClaimQuery->whereNull('similarity_log_id');
        }

        if ($existingClaimQuery->exists()) {
            return back()->withErrors([
                'proof' => 'You already have a pending ownership request for this item.',
            ]);
        }

        $payload = [
            'item_id' => $item->id,
            'user_id' => $userId,
            'similarity_score' => 0,
            'proof' => $proof,
            'status' => 'pending',
        ];

        if (Schema::hasColumn('claims', 'similarity_log_id')) {
            $payload['similarity_log_id'] = null;
        }

        if (Schema::hasColumn('claims', 'similarity_details')) {
            $payload['similarity_details'] = [
                'source' => 'direct_ownership_request',
                'item_type' => $item->type,
                'item_category' => $item->category,
                'item_location' => $item->location,
            ];
        }

        Claim::create($payload);

        return redirect()->route('student.claims')->with('success', 'Ownership request submitted for admin review.');
    }
}
