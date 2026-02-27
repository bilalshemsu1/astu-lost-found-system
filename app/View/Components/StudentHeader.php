<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StudentHeader extends Component
{
    public $title;
    public $trustScore;

    public function __construct($title = '', $trustScore = 0)
    {
        $this->title = $title;
        $this->trustScore = $trustScore;
    }

    public function render()
    {
        return view('components.student-header');
    }
}

