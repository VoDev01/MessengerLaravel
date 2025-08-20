<x-chats-layout :$currentUser>
    <x-slot name="title">Группы</x-slot>
    <h1 class="mb-3">Группы</h1>
    @foreach ($chats as $chat)
        <?php
        $chatLink = "/chat/$chat->link_name";
        $direct = false;
        if ($chat->type == 'DIRECT') {
            $chatLink = "/direct/$chat->link_name";
            $direct = true;
        }
        ?>
        <a class="mb-3 d-flex justify-content-start chat" href="{{ $chatLink }}" data-chat-id="{{ $chat->id }}">
            <div>
                <img src="{{ $chat->logo }}" alt="Лого группы" style="border-radius: 50%;" />
            </div>
            <div class="mx-3">
                <p>{{ $chat->name }}</p>
                @if (!$direct)
                    <p>{{ $chat->users->count() }} пользователей</p>
                @endif
            </div>
        </a>
    @endforeach
</x-chats-layout>
