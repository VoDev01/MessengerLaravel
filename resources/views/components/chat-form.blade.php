@if ($userIsInChat)
    <form class="chat-form mb-3" action="/chat/{{ $chat->link_name }}/store" method="POST" id="chat-form">
        @csrf
        <div class="d-flex flex-column">
            <textarea class="form-text" name="text" id="text" contenteditable spellcheck="false"></textarea>
        </div>
        <input type="hidden" id="chat-name" value="{{ $chat->link_name }}">
        <input type="hidden" id="sender-name" name="sender-name" value="{{ $currentUser->name }}">
        <input type="hidden" id="sender-id" name="sender-id" value="{{ $currentUser->id }}">
        <button type="submit" class="p-0 border-0" style="background: none;" id="chat-form-send"><i
                class="bi bi-chat-dots"></i></button>
    </form>
@else
    <form class="chat-form mb-3" action="/chat/{{ $chat->link_name }}/join" method="POST" id="join-form">
        @csrf
        <input type="hidden" id="chat-name" value="{{ $chat->link_name }}">
        <input type="hidden" id="chat-visibility" value="{{ $chat->visibility }}">
        <button type="submit" class="btn btn-primary">Войти в группу</button>
    </form>
@endif
