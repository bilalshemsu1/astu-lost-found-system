<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function showLostForm()
    {
        return view('student.post-lost');
    }

    public function showFoundForm()
    {
        // Logic to retrieve and display form
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
