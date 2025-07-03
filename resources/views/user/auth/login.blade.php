<x-auth-layout>
    <x-slot name="title">Login</x-slot>
    <form action="login" method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" id="email" />
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" id="password" />
        </div>
    </form>
</x-auth-layout>
