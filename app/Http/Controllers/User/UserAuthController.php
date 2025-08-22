<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Events\User\UserStatusChangedEvent;
use App\Http\Requests\User\Auth\UserAuthLoginRequest;
use App\Http\Requests\User\Auth\UserAuthRegisterRequest;

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
            Auth::login($user);
            broadcast(new UserStatusChangedEvent($user, true))->toOthers();
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

        $defaultPfp = true;
        $pfp = 'https://letters.noticeable.io/' . strtoupper(substr($validated['name'], 0, 1)) . rand(0, 19) . '.png';
        
        if (array_key_exists('pfp', $validated))
        {
            $pfp = Storage::putFile("/public/images/user_{$validated['name']}_pfp.png", $validated['pfp']);
            $defaultPfp = false;
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'phone' => $validated['phone'],
            'pfp' => $pfp,
            'default_pfp' => $defaultPfp
        ]);
        return redirect()->route('home');
    }
}
