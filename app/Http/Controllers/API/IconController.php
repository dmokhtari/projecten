<?php

namespace App\Http\Controllers\API;

use App\Models\Icon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Storage;

class IconController extends Controller
{
    /**
     * IconController constructor.
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
        $icons = Icon::all();
        return response()->json(['status' => 'success', 'data' => $icons], 200);
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
            'title' => 'required|string|max:255',
            'icon' => 'required|image'
        ]);

        $icon_name = $request->file('icon')->getClientOriginalName();
        $icon_type = $request->file('icon')->getMimeType();
        $icon_path = $request->file('icon')->store('img/settings/icons');
        
        Icon::create([
            'title' => $request->title,
            'src' => $icon_path,
            'name' => $icon_name,
            'type' => $icon_type
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
            'title' => 'required|string|max:255',
            'icon' => 'nullable|image'
        ]);
        
        $icon = Icon::findOrFail($id);
        $icon->fill($request->only('title'));
        
        if($request->hasFile('icon')) {
            $icon->name = $request->file('icon')->getClientOriginalName();
            $icon->type = $request->file('icon')->getMimeType();
            $icon->src = $request->file('icon')->store('img/settings/icons');
        }
        
        $icon->save();
        
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
        $icon = Icon::findOrFail($id);
        Storage::delete($icon->src);
        $icon->delete();
        
        return response()->json(['status' => 'success', 'data' => "{$icon->title} is verwijderd!"], 200);
    }
}
