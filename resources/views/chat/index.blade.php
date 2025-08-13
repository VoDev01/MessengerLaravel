<x-chats-layout :$currentUser>
    <x-slot name="title">{{$chat->name}}</x-slot>
    <x-chat-messages :$messages></x-chat-messages>
    <x-chat-form :$currentUser :$chat></x-chat-form>
</x-chats-layout>