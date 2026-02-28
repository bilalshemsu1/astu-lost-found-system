<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HasAdminViewData;
use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\ClaimResponse;
use App\Models\Item;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ClaimsController extends Controller
{
    use HasAdminViewData;

    public function index(Request $request)
    {
        $query = Claim::with(['item.user', 'user', 'similarityLog', 'claimResponse']);
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

    public function review(Claim $claim): View
    {
        $claim->load(['user', 'item.user', 'similarityLog.lostItem.user', 'similarityLog.foundItem.user', 'claimResponse.confirmedByAdmin']);
        $isDirectRequest = !$claim->similarity_log_id;

        return view('admin.claims-review', array_merge([
            'claim' => $claim,
            'isDirectRequest' => $isDirectRequest,
        ], $this->navCounts()));
    }

    public function approve(Request $request, Claim $claim): RedirectResponse
    {
        $request->validate([
            'admin_notes' => 'required|string|min:10|max:1000',
        ]);

        if ($claim->status !== 'pending') {
            return redirect()->back()->with('success', 'Claim already processed.');
        }

        $claim->update([
            'status' => 'approved',
            'admin_decision' => 'approved',
            'admin_notes' => $request->string('admin_notes')->toString(),
        ]);

        $item = $claim->item;
        $allowsDirectHandover = (bool) (
            $item &&
            $item->type === 'found' &&
            $item->return_location_preference === 'direct' &&
            ($item->share_phone || $item->share_telegram)
        );

        ClaimResponse::updateOrCreate(
            ['claim_id' => $claim->id],
            [
                'finder_responded' => true,
                'finder_shares_contact' => $allowsDirectHandover,
                'response_at' => now(),
            ]
        );

        if ($item && $item->status !== 'returned' && $item->status !== 'rejected') {
            $item->update(['status' => 'active']);
        }

        Claim::where('item_id', $claim->item_id)
            ->where('id', '!=', $claim->id)
            ->where('status', 'pending')
            ->update([
                'status' => 'rejected',
                'admin_decision' => 'rejected',
                'admin_notes' => 'Another claim for this item was approved after review.',
            ]);

        return redirect()->back()->with('success', 'Claim approved successfully.');
    }

    public function reject(Request $request, Claim $claim): RedirectResponse
    {
        $request->validate([
            'admin_notes' => 'required|string|min:5|max:1000',
        ]);

        if ($claim->status !== 'pending') {
            return redirect()->back()->with('success', 'Claim already processed.');
        }

        $notes = $request->string('admin_notes')->toString();

        $claim->update([
            'status' => 'rejected',
            'admin_decision' => 'rejected',
            'admin_notes' => $notes,
        ]);

        if ($claim->user) {
            $claim->user->decrement('trust_score');
        }

        return redirect()->back()->with('success', 'Claim rejected successfully.');
    }

    public function confirmHandover(Claim $claim): RedirectResponse
    {
        if ($claim->status !== 'approved') {
            return redirect()->back()->with('success', 'Only approved claims can be confirmed for handover.');
        }

        $response = ClaimResponse::firstOrCreate(
            ['claim_id' => $claim->id],
            [
                'finder_responded' => true,
                'finder_shares_contact' => false,
                'response_at' => now(),
            ]
        );

        if ($response->handover_confirmed_at) {
            return redirect()->back()->with('success', 'Handover was already confirmed.');
        }

        $response->update([
            'handover_confirmed_at' => now(),
            'confirmed_by_admin_id' => Auth::id(),
        ]);

        if ($claim->item && $claim->item->status !== 'returned') {
            $claim->item->update(['status' => 'returned']);

            if ($claim->item->user) {
                $claim->item->user->increment('trust_score');
            }
        }

        if ($claim->user) {
            $claim->user->increment('trust_score');
        }

        return redirect()->back()->with('success', 'Handover confirmed and item marked as returned.');
    }
}
