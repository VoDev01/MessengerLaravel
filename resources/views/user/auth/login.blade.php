<x-auth-layout>
    <x-slot name="title">Login</x-slot>
    <h1>Войдите в аккаунт</h1>
    <form action="postLogin" method="POST">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" id="email" />
        </div>
        <x-error field="email" />
        <div class="mb-3">
            <label for="password" class="form-label">Пароль</label>
            <input type="password" class="form-control" name="password" id="password" />
        </div>
        <x-error field="password" />
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Войти</button>
            <a class="btn btn-secondary" href="/register">Зарегестрироваться</a>
        </div>
    </form>
</x-auth-layout>