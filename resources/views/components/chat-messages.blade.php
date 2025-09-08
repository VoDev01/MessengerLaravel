<div id="message-box">
    @forelse ($messages as $message)
        <?php $time = substr(explode(' ', $message->created_at)[1], 0, 5); ?>
        @if ($message->sender_id === $currentUser->id)
            <div class="self-message" data-message-timestamp="{{ $message->created_at }}"
                data-message-id="{{ $message->id }}" data-message-status="{{ $message->status }}">
                <p>{{ $message->sender->name }}</p>
                <p id="message-{{$message->id}}">{{ $message->text }}</p>
                <p>{{ $time }}
                    <?php 
                        match ($message->status) {
                            'PROCESSING' =>  call_user_func(function(){ echo '<i class="bi bi-three-dots"></i>'; }),
                            'SENT' =>  call_user_func(function(){ echo '<i class="bi bi-check2"></i>'; }),
                            'DELIVERED' => call_user_func(function(){ echo '<i class="bi bi-check2-all"></i>'; }),
                            'SEEN' => call_user_func(function(){ echo '<i class="bi bi-check2-all text-primary"></i>'; }),
                            'NOT_SENT' =>  call_user_func(function(){ echo '<i class="bi bi-exclamation-circle"></i>'; })
                        }
                    ?>
                </p>
            </div>
        @else
            <div class="foreign-message" data-message-timestamp="{{ $message->created_at }}"
                data-message-id="{{ $message->id }}" data-message-status="{{$message->status}}">
                <p>{{ $message->sender->name }}</p>
                <p>{{ $message->text }}</p>
                <p>{{ $time }}</p>
            </div>
        @endif
    @empty
        <span class="mb-3">В этом чате пока что нет сообщений</span>
    @endforelse
</div>
