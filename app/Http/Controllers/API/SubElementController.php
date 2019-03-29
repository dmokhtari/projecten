<?php

namespace App\Http\Controllers\API;

use App\Models\Element;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubElementController extends Controller
{
    /**
     * SubElementController constructor.
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
        //
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'element_id' => 'required',
            'icon_id' => 'nullable|exists:icons,id',
            'title' => 'nullable|string|max:255',
            'url' => 'nullable|url|max:255',
            'description' => 'nullable|string',
        ]);
        
        $element = Element::findOrFail($request->element_id);
        
        $element->subelements()->create($request->only(['type', 'title', 'url', 'icon_id', 'description', 'binary']));
        
        return response()->json(['status' => 'success', 'data' => 'Sub Element aangemaakt!'], 200);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
