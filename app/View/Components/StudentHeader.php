<?php

namespace App\View\Components;
// use auth;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class StudentHeader extends Component
{
    public $title;
    public $trustScore;

    public function __construct($title = '', $trustScore = null)
    {
        $this->title = $title;
        $this->trustScore = $trustScore ?? (Auth::user()->trust_score ?? 0);
    }

    public function render()
    {
        return view('components.student-header');
    }
}
