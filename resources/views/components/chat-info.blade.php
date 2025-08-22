<div class="mb-4 d-flex justify-content-start">
    @if ($direct)
        <?php
            $otherUser = $chat->users[0]->id === \Illuminate\Support\Facades\Auth::id() ? $chat->users[1] : $chat->users[0];
        ?>
        <div>
            <img src="{{ $otherUser->pfp }}" style="border-radius: 50%;" alt="Лого группы" />
        </div>
        <div class="mx-3">
            <p>{{ $otherUser->name }}</p>
            <p class="user-online">{{ $otherUser->online ? 'В сети' : 'Не в сети'}}</p>
        </div>
    @else
        <div>
            <img src="{{ $chat->logo }}" style="border-radius: 50%;" alt="Лого группы" />
        </div>
        <div class="mx-3">
            <p>{{ $chat->name }}</p>
            <p id="user-count">{{ $chat->users->count() }} пользователей</p>
        </div>
    @endif
</div>
