<div class="mb-5 d-flex justify-content-start">
    <div>
        <img src="{{ $chat->logo }}" alt="Лого группы" />
    </div>
    <div class="mx-3">
        <p>{{ $chat->name }}</p>
        <p>{{ $chat->users->count() }} пользователей</p>
    </div>
</div>