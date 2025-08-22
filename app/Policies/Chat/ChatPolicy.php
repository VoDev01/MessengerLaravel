<?php

namespace App\Policies\Chat;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class ChatPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Chat $chat): bool
    {
        if (!Auth::check())
            return false;

        foreach ($chat->users as $chatUser)
        {
            if ($chatUser->id === $user->id)
            {
                return true;
            }
        }
        return false;
    }

    public function viewDirect(User $otherUser, User $currentUser, Chat $chat): bool
    {
        if (!Auth::check())
            return false;

        $usersInChat = [];

        foreach($chat->users as $chatUser)
        {
            if($chatUser->id === $otherUser->id || $chatUser->id === $currentUser->id)
            {
                array_push($usersInChat, $chatUser->id);
            }
        }

        var_dump($usersInChat);

        if(count($usersInChat) < 2)
            return false;

        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Chat $chat): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Chat $chat): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Chat $chat): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Chat $chat): bool
    {
        return false;
    }
}
