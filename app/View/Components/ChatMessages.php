<?php

namespace App\View\Components;

use App\Models\ChatMessage;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class ChatMessages extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public Collection $messages)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.chat-messages', ['messages' => $this->messages]);
    }
}
