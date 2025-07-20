<x-chats-layout>
    <x-slot name="title">Home</x-slot>
    <div class="row">
        <nav class="col-2">
            <div class="mb-2">
                <div class="row">
                    <div class="col">
                        @if ($user->default_pfp)
                            <img src="{{ $user->pfp }}" alt="Аватарка {{ $user->name }}" style="border-radius: 50%;">
                        @else
                            <img src="{{ asset('$user->pfp') }}" alt="Аватарка {{ $user->name }}"
                                style="border-radius: 50%;">
                        @endif
                    </div>
                    <div class="col">{{ $user->name }}</div>
                </div>
                <button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#profiles"
                    role="button">
                    <i class="bi bi-chevron-down"></i></button>
                <div class="collapse row" id="profiles">

                </div>
                <div>Профиль</div>
                <div>Создать группу</div>
                <div>Настройки</div>
                <div>Контакты</div>
                <div>Звонки</div>
                <div>Избранное</div>
                <div>Выйти</div>
            </div>
        </nav>
        <div class="col-10">
            <p>There will be displayed chats</p>
        </div>
    </div>
</x-chats-layout>
