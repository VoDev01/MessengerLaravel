<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;

class APIController extends Controller
{
    public function index()
    {
        return view('api.index');
    }
}
