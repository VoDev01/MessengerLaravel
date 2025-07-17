<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\UserAuthLoginRequest;
use App\Http\Requests\User\Auth\UserAuthRegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{
    public function login()
    {
        return view('user.auth.login');
    }

    public function postLogin(UserAuthLoginRequest $request)
    {
        $validated = $request->validated();
        $user = User::where('email', $validated['email'])->get()->first();
        if (Hash::check($validated['password'], $user->password))
        {
            return redirect()->route('home');
        }
        else
        {
            return redirect()->back()->withErrors(['password' => 'Неверный пароль']);
        }
    }

    public function register()
    {
        return view('user.auth.register');
    }

    public function postRegister(UserAuthRegisterRequest $request)
    {
        $validated = $request->validated();
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password']
        ]);
        return redirect()->route('home');
    }
}
