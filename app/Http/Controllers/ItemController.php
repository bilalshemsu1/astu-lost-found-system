<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        // Logic to handle posting a lost item
    }

    public function postFoundItem(Request $request)
    {
        // Logic to handle posting a found item
    }
}
