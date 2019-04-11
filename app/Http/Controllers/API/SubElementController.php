<?php

namespace App\Http\Controllers\API;

use App\Models\Element;
use App\Models\SubElement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Log;

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
            'title' => 'nullable|string|max:191',
            'url' => 'nullable|url|max:255',
            'description' => 'nullable|string',
        ]);
        
        $subElement = new SubElement();
        $subElement->fill($request->only('type', 'title', 'url', 'description', 'binary', 'element_id'));
        $subElement->save();
        $subElement->icons()->attach($request->icon_id);

        return response()->json(['status' => 'success', 'data' => 'Sub-element aangemaakt!'], 201);
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
            'type' => 'required|string',
            'element_id' => 'required',
            'title' => 'nullable|string|max:191',
            'url' => 'nullable|url|max:191',
            'description' => 'nullable|string',
        ]);

        $subElement = SubElement::findOrFail($id);
        $subElement->update($request->only('type', 'title', 'url', 'description', 'binary'));
        Log::info(json_encode($request->icon_id));
        $subElement->icons()->sync([$request->icon_id]);

        return response()->json(['status' => 'success', 'data' => 'Sub-element gewijzigd!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $subelement = SubElement::findOrFail($id);
        $subelement->icons()->delete();
        $subelement->delete();
        
        return response()->json(['status' => 'success', 'data' => "Sub-element is verwijderd!"], 200);
    }
}
