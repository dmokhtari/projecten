<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppController extends Controller
{
    public function index()
    {
        if(Auth::check()) {
            return redirect()->route('home');
        }
        return redirect()->route('login');
    }

    public function getHome()
    {
        return view('home');
    }
}
