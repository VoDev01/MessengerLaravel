<x-auth-layout>
    <x-slot name="title">Register</x-slot>
    <h1>Регистрация</h1>
    <form action="postRegister" class="d-flex flex-column" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="name" class="form-control" name="name" id="name" />
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" id="email" />
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Пароль</label>
            <input type="password" class="form-control" name="password" id="password" />
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Повторите пароль</label>
            <input type="confirm_password" class="form-control" name="confirm_password" id="confirm_password" />
        </div>
        <button class="btn btn-primary">Зарегестрироваться</button>
    </form>
</x-auth-layout>