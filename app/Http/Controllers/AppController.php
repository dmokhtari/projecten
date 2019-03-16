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

    public function getDashboard()
    {
        return view('dashboard');
    }

    public function getUserRole(Request $request)
    {
        $user = auth()->guard('api')->user();
        $role = $user->isAdmin() ? 1 : 2;
        $roleArray = ['role' => $role];
        return response()->json(['status' => 'success', 'data' => $roleArray], 200);
    }

    public function getTest()
    {
        return response()->json(['status' => 'success', 'data' => 'hi test'], 200);
    }
}
