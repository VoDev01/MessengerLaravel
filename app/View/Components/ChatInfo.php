<?php

namespace App\View\Components;

use App\Models\Chat;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ChatInfo extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public Chat $chat)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.chat-info', ['chat' => $this->chat]);
    }
}
