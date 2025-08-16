<?php

namespace App\View\Components;

use Closure;
use App\Models\User;
use App\Models\ChatMessage;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;

class ChatMessages extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public Collection $messages, public User $currentUser)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.chat-messages', ['messages' => $this->messages, 'currentUser' => $this->currentUser]);
    }
}
