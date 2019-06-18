<?php

namespace App\Http\Controllers\API;

use App\Models\Documentation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DocumentationController extends Controller
{
    /**
     * DocumentationController constructor.
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
        $docs = Documentation::all();
        return response()->json(['status' => 'success', 'data' => $docs], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:191',
            'text' => 'required'
        ]);

        Documentation::create([
            'user_id' => Auth::user()->id,
            'title' => $request->title,
            'text' => $request->text
        ]);

        return response()->json(['status' => 'success', 'data' => "{$request->title} is toegevoegd!"], 201);
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
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:191',
            'text' => 'nullable'
        ]);

        $doc = Documentation::findOrFail($id);
        $doc->update([
            'user_id' => Auth::user()->id,
            'title' => $request->title,
            'text' => $request->text
        ]);

        return response()->json(['status' => 'success', 'data' => "{$request->title} is gewijzigd!"], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $doc = Documentation::findOrFail($id);
        $doc->delete();

        return response()->json(['status' => 'success', 'data' => "{$doc->title} is verwijderd!"], 200);
    }
}
