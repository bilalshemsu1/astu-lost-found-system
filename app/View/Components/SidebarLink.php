<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SidebarLink extends Component
{
    public $href;
    public $label;
    public $active;

    public function __construct($href = '#', $label = '', $active = false)
    {
        $this->href = $href;
        $this->label = $label;
        $this->active = $active;
    }

    public function render()
    {
        return view('components.sidebar-link');
    }
}