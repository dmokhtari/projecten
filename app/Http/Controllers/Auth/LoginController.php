<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * @param Request $request
     * @param $user
     * @return \Illuminate\Http\JsonResponse
     */
    protected function authenticated(Request $request, $user)
    {
        return response()->json(['status' => 'success', 'data' => 'LogedIn successfully'], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        auth()->user()->tokens->each(function($token, $key) {
            $token->delete();
        });

        $this->guard()->logout();

        $request->session()->invalidate();

        if($request->ajax()) {
            return response()->json(['status' => 'success', 'data' => 'Uitgelogd!'], 200);
        }
        return redirect()->route('login');
    }
}
