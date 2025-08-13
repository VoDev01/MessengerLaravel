<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>{{ $title }}</title>
</head>

<body class="container-fluid mt-2">

    <div class="row">
        <nav class="col-2">
            <div class="row">
                <div class="col">
                    @if ($currentUser->default_pfp)
                        <img src="{{ $currentUser->pfp }}" alt="Аватарка {{ $currentUser->name }}"
                            style="border-radius: 50%;">
                    @else
                        <img src="{{ asset('$currentUser->pfp') }}" alt="Аватарка {{ $currentUser->name }}"
                            style="border-radius: 50%;">
                    @endif
                </div>
                <div class="col">{{ $currentUser->name }}</div>
            </div>
            <button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#profiles"
                aria-expanded="false" aria-controls="profiles">
                <i class="bi bi-chevron-down"></i>
            </button>
            <div class="collapse" id="profiles">
                <a href="/new_account">Добавить аккаунт</a>
            </div>
            <div class="d-flex flex-column">
                <a>Профиль</a>
                <a href="/chat/create">Создать группу</a>
                <a>Настройки</a>
                <a>Контакты</a>
                <a>Звонки</a>
                <a>Избранное</a>
                <a href="/logout">Выйти</a>
            </div>
        </nav>
        <div class="col-10 d-flex flex-column align-items-center">
            <div style="width: 500px;">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>

</html>
