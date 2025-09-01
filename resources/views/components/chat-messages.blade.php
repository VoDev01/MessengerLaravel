<div id="message-box">
    @forelse ($messages as $message)
        <?php $time = substr(explode(' ', $message->created_at)[1], 0, 5); ?>
        @if ($message->sender_id === $currentUser->id)
            <div class="self-message" data-message-timestamp="{{ $message->created_at }}"
                data-message-id="{{ $message->id }}" data-message-status="{{$message->status}}">
                <p>{{ $message->sender->name }}</p>
                <p>{{ $message->text }}</p>
                <p>{{ $time }} <?php 
                    switch($message->status)
                    {
                        case 'PROCESSING':
                            echo '<i class="bi bi-three-dots"></i>';
                            break;
                        case 'SENT':
                            echo '<i class="bi bi-check2"></i>';
                            break;
                        case 'DELIVERED':
                            echo '<i class="bi bi-check2-all"></i>';
                            break;
                        case 'SEEN':
                            echo '<i class="bi bi-check2-all text-primary"></i>';
                            break;
                        case 'NOT_SENT':
                            echo '<i class="bi bi-exclamation-circle"></i>';
                            break;
                    }
                ?></p>
            </div>
        @else
            <div class="foreign-message" data-message-timestamp="{{ $message->created_at }}"
                data-message-id="{{ $message->id }}" data-message-status="{{ $message->status }}">
                <p>{{ $message->sender->name }}</p>
                <p>{{ $message->text }}</p>
                <p>{{ $time }}</p>
            </div>
        @endif
    @empty
        <span class="mb-3">В этом чате пока что нет сообщений</span>
    @endforelse
</div>
