<?php

namespace App\View\Components;

use App\Models\Chat;
use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ChatForm extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public Chat $chat, public User $currentUser)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.chat-form', ['chat' => $this->chat, 'currentUser' => $this->currentUser]);
    }
}
