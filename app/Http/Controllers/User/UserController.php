<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function home()
    {
        return view('home', ['user' => Auth::user()]);
    }
    public function profile()
    {
        return view('profile');
    }
}
