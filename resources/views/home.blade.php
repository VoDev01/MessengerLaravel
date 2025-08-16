<x-chats-layout :$currentUser>
    <x-slot name="title">Группы</x-slot>
    <h1 class="mb-3">Группы</h1>
    @foreach ($chats as $chat)
        <a class="mb-3 d-flex justify-content-start" href="/chat/{{$chat->link_name}}" >
            <div>
                <img src="{{ $chat->logo }}" alt="Лого группы" />
            </div>
            <div class="mx-3">
                <p>{{ $chat->name }}</p>
                <p>{{ $chat->users->count() }} пользователей</p>
            </div>
        </a>
    @endforeach
</x-chats-layout>
