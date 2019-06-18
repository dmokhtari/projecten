<?php

namespace App\Http\Controllers\API;

use App\Models\Element;
use App\Models\Ranking;
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
            'url' => 'nullable|url|max:191',
            'description' => 'nullable|string',
            'pdf_file' => 'nullable|mimes:pdf|max:10000'
        ]);

        $iconIds = [];
        if(!empty($request->icon_id)) {
            $iconIds = explode(",", $request->icon_id);
        }
        Log::info($iconIds);
        
        $subElement = new SubElement();
        $subElement->fill($request->only('type', 'title', 'url', 'description', 'binary', 'element_id'));


        if($request->type === 'file') {
            $file_path = null;
            $file_name = null;
            if($request->hasFile('pdf_file')) {
                $file_name = $request->file('pdf_file')->getClientOriginalName();
                $file_path = $request->file('pdf_file')->store('img/files');
            }
            $subElement->url = $file_path;
            $subElement->title = $file_name;
        }

        $subElement->save();
        $subElement->icons()->attach($iconIds);

        return response()->json(['status' => 'success', 'data' => 'Subelement aangemaakt!'], 201);
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
            'pdf_file' => 'nullable|mimes:pdf|max:10000'
        ]);

        // TODO axios with formData not sending array
        $iconIds = [];
        if(!empty($request->icon_id)) {
            $iconIds = explode(",", $request->icon_id);
        }
        Log::info($iconIds);

        $subElement = SubElement::findOrFail($id);
        $subElement->update($request->only('type', 'title', 'url', 'description', 'binary'));

        if($request->type === 'file') {
            $file_path = null;
            $file_name = null;
            if($request->hasFile('pdf_file')) {
                $file_name = $request->file('pdf_file')->getClientOriginalName();
                $file_path = $request->file('pdf_file')->store('img/files');
            }
            $subElement->update([
                'url' => $file_path,
                'title' => $file_name
            ]);
        }
        $subElement->icons()->sync($iconIds);

        return response()->json(['status' => 'success', 'data' => 'Subelement gewijzigd!'], 200);
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

        // delete parent ranking
        $elementId = $subelement->element_id;
        $element = Element::findOrFail($elementId);
        $element->ranking()->delete();

        $subelement->delete();
        
        return response()->json(['status' => 'success', 'data' => "Subelement verwijderd!"], 200);
    }
}
