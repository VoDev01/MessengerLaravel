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
        <a class="mb-3 d-flex justify-content-start chat text-decoration-none text-dark" href="{{ $chatLink }}"
            data-chat-id="{{ $chat->id }}" data-chat-visibility="{{ $chat->visibility }}"
            data-chat-link-name={{ $chat->link_name }} data-chat-type={{ $chat->type }}>
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
                    <p class="user-online" data-user-link="{{ $otherUser->link_name }}">
                        {{ $otherUser->online ? 'В сети' : 'Не в сети' }}</p>
                </div>
            @endif
            @if ($unreadMessagesCount)
                @foreach ($unreadMessagesCount as $count)
                    @if ($count->chat_id === $chat->id)
                        <div class="d-flex justify-content-end align-items-center" style="flex: 1;">
                            <p class="unread-messages-count">
                                {{ $count->unread_messages_count > 99 ? '99+' : $count->unread_messages_count }}</p>
                        </div>
                        @break
                    @endif
                @endforeach
            @endif
        </a>
    @endforeach
    <script type="module">
        chat.userStatus();
        chat.countSentMessages();
        chat.loadChats();
    </script>
</x-chats-layout>
