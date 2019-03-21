<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Auth;
use Cache;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * DashboardController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('admin:api');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsers()
    {
        $total = User::count();
        $data = collect(['total' => $total, 'online' => $this->getOnlineUsers()]);
        return response()->json(['status' => 'success', 'data' => $data], 200);
    }

    /**
     * @return int Total users online
     */
    protected function getOnlineUsers()
    {
        $users = User::all();
        $online = 0;
        foreach($users as $user) {
            if($user->isOnline()) {
                $online++;
            }
        }
        return $online;
    }
}
