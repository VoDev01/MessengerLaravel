<div class="mb-5 d-flex justify-content-start">
    <div>
        <img src="{{ $chat->logo }}" style="border-radius: 50%;"  alt="Лого группы" />
    </div>
    <div class="mx-3">
        <p>{{ $chat->name }}</p>
        <p id="user-count">{{ $chat->users->count() }} пользователей</p>
    </div>
</div>