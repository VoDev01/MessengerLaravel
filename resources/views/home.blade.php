<x-chats-layout :$currentUser>
    <x-slot name="title">Группы</x-slot>
    <h1 class="mb-3">Группы</h1>
    @foreach ($chats as $chat)
        <?php
        $chatLink = "/chat/$chat->link_name";
        $direct = false;
        if ($chat->type == 'DIRECT') {
            $otherUser = $chat->users[0]->id === \Illuminate\Support\Facades\Auth::id() ? $chat->users[1] : $chat->users[0];
            $chatLink = "/direct/$otherUser->link_name";
            $direct = true;
        }
        ?>
        <a class="mb-3 d-flex justify-content-start chat" href="{{ $chatLink }}" data-chat-id="{{ $chat->id }}">
            @if (!$direct)
                <div>
                    <img src="{{ $chat->logo }}" alt="Лого группы" style="border-radius: 50%;" />
                </div>
                <div class="mx-3">
                    <p>{{ $chat->name }}</p>
                    <p>{{ $chat->users->count() }} пользователей</p>
                </div>
            @else
                <div>
                    <img src="{{ $otherUser->pfp }}" alt="Лого группы" style="border-radius: 50%;" />
                </div>
                <div class="mx-3">
                    <p>{{ $otherUser->name }}</p>
                    <p class="user-online" data-user-link="{{$otherUser->link_name}}">{{ $otherUser->online ? 'В сети' : 'Не в сети' }}</p>
                </div>
            @endif
        </a>
    @endforeach
</x-chats-layout>
@vite(['resources/js/chat-load-chats.js', 'resources/js/user-status.js'])
