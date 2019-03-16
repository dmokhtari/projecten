<?php

namespace App\Http\Controllers\API;

use App\Models\Module;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Storage;

class ModuleController extends Controller
{
    /**
     * ModuleController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('admin:api')->except('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modules = Module::all();
        return response()->json(['status' => 'success', 'data' => $modules], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:200',
            'subtitle' => 'nullable|string|max:200',
            'background_image' => 'nullable|image',
            'background_color' => 'nullable|string|max:200'
        ]);
        $image_path = null;
        $image_name = null;
        if($request->hasFile('background_image')) {
            $image_name = $request->file('background_image')->getClientOriginalName();
            $image_path = $request->file('background_image')->store('img/modules');
        }
        Module::create([
            'user_id' => Auth::guard('api')->user()->id,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'image_name' => $image_path,
            'image_path' => $image_name,
            'background_color' => $request->background_color
        ]);

        return response()->json(['status' => 'success', 'data' => "{$request->title} is aangemaakt!"], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $module = Module::findOrFail($id);

        return response()->json(['status' => 'success', 'data' => $module], 200);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|string|max:200',
            'subtitle' => 'nullable|string|max:200',
            'background_image' => 'nullable|image',
            'background_color' => 'nullable|string|max:200'
        ]);

        $module = Module::findOrFail($id);
        $module->fill($request->only(['title', 'subtitle', 'background_color']));

        if($request->hasFile('background_image')) {
            $module->image_name = $request->file('background_image')->getClientOriginalName();
            $module->image_path = $request->file('background_image')->store('img/modules');
        }
        $module->save();

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
        $module = Module::findOrFail($id);
        if($module->image_path) Storage::delete($module->image_path);
        $module->delete();
        return response()->json(['status' => 'success', 'data' => "{$module->title} is verwijderd!"], 200);
    }
}
