<x-chats-layout :$currentUser>
    <x-slot name="title">{{ $chat->name }} Restricted</x-slot>
    <x-chat-info :$chat></x-chat-info>
    <h3 id="restricted" class="mb-3">Вы должны состоять в группе чтобы просматривать или отправлять сообщения</h3>
    <x-chat-form :$currentUser :$chat :$userIsInChat></x-chat-form>
</x-chats-layout>
@vite('resources/js/chat-join.js')