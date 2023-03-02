<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;

class MainController extends Controller
{
    public function index(): Renderable
    {
        return view('index');
    }
    public function login(): Renderable
    {
        return view('login');
    }
}
