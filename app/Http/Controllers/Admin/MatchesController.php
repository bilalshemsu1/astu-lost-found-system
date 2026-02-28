<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HasAdminViewData;
use App\Http\Controllers\Controller;
use App\Models\SimilarityLog;
use Illuminate\Http\Request;

class MatchesController extends Controller
{
    use HasAdminViewData;

    public function index(Request $request)
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
            'categories' => $this->categories(),
            'totalMatches' => SimilarityLog::count(),
            'highMatches' => SimilarityLog::where('similarity_percentage', '>=', 90)->count(),
            'mediumMatches' => SimilarityLog::whereBetween('similarity_percentage', [80, 89.99])->count(),
            'notifiedMatches' => SimilarityLog::where('notified', true)->count(),
        ], $this->navCounts()));
    }
}
