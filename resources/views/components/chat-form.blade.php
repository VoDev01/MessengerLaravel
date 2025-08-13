<form action="/chat/{{ $chat->link_name }}/store" method="POST" id="chat-form">
    @csrf
    <input class="border-1 border-black" type="text" name="text" id="text">
    <input type="hidden" name="sender-name" value="{{$currentUser->name}}">
    <button type="submit">Отправить</button>
</form>
<?php $shouldInvite = true; ?>
@foreach ($chat->users as $user)
    @if ($user->id === $currentUser->id)
        <?php $shouldInvite = false; ?>
        @break
    @endif
@endforeach
@if ($shouldInvite)
    <form action="/chat/join" method="POST">
        <input type="hidden" id="chat-name" value="{{ $chat->link_name }}">
        <input type="hidden" id="chat-visibility" value="{{ $chat->visibility }}">
        <button type="submit">Войти в группу</button>
    </form>
@endif