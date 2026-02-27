<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{

    public function index()
    {
        return view('student.items.index');
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
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'item_date' => 'required|date',
            'image' => 'nullable|image|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('items', 'public');
        }

        // Set defaults
        $validated['type'] = 'lost';
        $validated['status'] = 'pending_verification';
        $validated['user_id'] = Auth::id();

        Item::create($validated);

        return redirect()->route('student.items')->with('success', 'Posted!');
    }

    public function postFoundItem(Request $request)
    {
        // Logic to handle posting a found item
    }
}
