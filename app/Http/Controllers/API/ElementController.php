<?php

namespace App\Http\Controllers\API;

use App\Models\Element;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Log;

class ElementController extends Controller
{
    /**
     * ElementController constructor.
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
        $elements = Element::withCount('subelements')->get();
        return response()->json(['status' => 'success', 'data' => $elements], 200);
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
            'subtitle' => 'nullable|string|max:255',
            'module_id' => 'nullable|integer|exists:modules,id'
        ]);

        Element::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'user_id' => Auth::guard('api')->user()->id,
            'module_id' => $request->module_id
        ]);

        return response()->json(['status' => 'success', 'data' => 'Element aangemaakt!'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $element = Element::with('subelements')->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $element], 200);
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
            'subtitle' => 'nullable|string|max:255',
            'module_id' => 'nullable|integer|exists:modules,id'
        ]);

        $element = Element::findOrFail($id);
        $element->update($request->only(['title', 'subtitle', 'module_id']));

        return response()->json(['status' => 'success', 'data' => 'Element gewijzigd!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $element = Element::findOrFail($id);
        // throw error if element has subelements
        if($element->subElements()->exists()) {
            return response()->json(['status' => 'error', 'data' => 'Dit element has subelementen: verwijder eerst de subelementen!']);
        }
        
        $element->delete();
        return response()->json(['status' => 'success', 'data' => "{$element->title} is verwijderd!"], 200);
    }
}
