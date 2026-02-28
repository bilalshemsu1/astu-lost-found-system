<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HasAdminViewData;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    use HasAdminViewData;

    public function index(Request $request)
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
                    ->orWhere('phone', 'like', '%' . $search . '%')
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

    public function create()
    {
        return view('admin.users.create', $this->navCounts());
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|string|max:30|min:9',
            'student_id' => 'nullable|string|max:255|unique:users,student_id',
            'telegram_username' => ['nullable', 'string', 'max:32', 'regex:/^@?[A-Za-z0-9_]{5,32}$/'],
            'role' => ['required', Rule::in(['student', 'admin'])],
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'student_id' => $validated['student_id'] ?? null,
            'telegram_username' => $this->normalizeTelegramUsername($validated['telegram_username'] ?? null),
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'trust_score' => 0,
        ]);

        return redirect()->route('admin.users')->with('success', 'User created successfully.');
    }

    private function normalizeTelegramUsername(?string $username): ?string
    {
        if ($username === null) {
            return null;
        }

        $cleaned = ltrim(trim($username), '@');

        return $cleaned !== '' ? $cleaned : null;
    }
}
