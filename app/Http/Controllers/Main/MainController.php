<?php

namespace App\Http\Controllers\Main;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use App\Models\User;

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
    public function dashboard()
    {
        $user =  User::where('id',auth()->user()->id)->with('wallet')->first();
        return view('dashboard',compact('user'));
    }
    
}
