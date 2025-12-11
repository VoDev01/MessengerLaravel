<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\View;
use Illuminate\View\Component;

class ApiLayout extends Component
{
    
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.api-layout');
    }
}