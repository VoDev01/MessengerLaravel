<div>
    @forelse ($messages as $message)
        <div class="message">
            <p>{{ $message->sender->name }}</p>
            <p>{{ $message->text }}</p>
            <p>{{ $message->created_at }}</p>
        </div>
    @empty
        <span>В этом чате пока что нет сообщений</span>
    @endforelse
</div>