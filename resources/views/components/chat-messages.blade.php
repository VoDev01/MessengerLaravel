<div id="message-box">
    @forelse ($messages as $message)
        <?php $time = substr(explode(' ', $message->created_at)[1], 0, 5); ?>
        @if ($message->sender_id === $currentUser->id)
            <div class="self-message" data-message-timestamp="{{ $message->created_at }}">
                <p>{{ $message->sender->name }}</p>
                <p>{{ $message->text }}</p>
                <p>{{ $time }}</p>
            </div>
        @else
            <div class="foreign-message" data-message-timestamp="{{ $message->created_at }}">
                <p>{{ $message->sender->name }}</p>
                <p>{{ $message->text }}</p>
                <p>{{ $time }}</p>
            </div>
        @endif
    @empty
        <span>В этом чате пока что нет сообщений</span>
    @endforelse
</div>
