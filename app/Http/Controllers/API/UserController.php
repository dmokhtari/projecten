<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\UserRegistrationRequest;
use App\Imports\UserImport;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Log;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('admin:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('roles')->get();
        return response()->json(['status' => 'success', 'data' => $users], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRegistrationRequest $request)
    {
        $user = new User();
        $user->fill($request->only(['forename', 'surname', 'email', 'class', 'end_date_study']));
        $user->password = Hash::make(Str::random(10));
        $user->remember_token = Str::random(10);
        $user->save();

        $user->roles()->attach($request->role);

        return response()->json(['status' => 'success', 'data' => 'Gebruiker aangemaakt!'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRegistrationRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->only(['forename', 'surname', 'email', 'class', 'end_date_study']));

        $user->roles()->sync($request->role);

        return response()->json(['status' => 'success', 'data' => 'Gebruiker gewijzigd!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user  = User::findOrFail($id);
        $user->roles()->sync([]);
        $user->delete();

        return response()->json(['status' => 'success', 'data' => 'Gebruiker verwijderd!'], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,csv,xls,tsv|max:100000'
        ]);
        try {
            Excel::import(new UserImport(), $request->file('excel_file'));
            return response()->json(['status' => 'success', 'data' => 'Bestand sucesvol geupload!'], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'data' => $e], 500);
        }
    }

}