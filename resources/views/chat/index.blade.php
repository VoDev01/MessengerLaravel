<x-chats-layout :$currentUser>
    <x-slot name="title">{{ $chat->name }}</x-slot>
    <x-chat-info :$chat></x-chat-info>
    <x-chat-messages :$messages :$currentUser></x-chat-messages>
    <x-chat-form :$currentUser :$chat :$userIsInChat></x-chat-form>
</x-chats-layout>