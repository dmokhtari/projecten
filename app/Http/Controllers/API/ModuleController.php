<?php

namespace App\Http\Controllers\API;

use App\Models\File;
use App\Models\Module;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Log;
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
        $modules = Module::with('files')->withCount('elements')->get();
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
            'title' => 'required|string|max:191',
            'subtitle' => 'nullable|string|max:191',
            'text' => 'nullable|string|max:65535',
            'file_id' => 'nullable|exists:files,id'
        ]);

        $module = new Module();
        $module->fill($request->only('title', 'subtitle', 'text'));
        $module->user_id = Auth::guard('api')->user()->id;
        $module->save();
        // assign file id
        if($request->file_id) {
            $module->files()->attach($request->file_id);
        }

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
        $module = Module::with(['elements', 'ranking', 'files'])->findOrFail($id);
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
            'title' => 'required|string|max:191',
            'subtitle' => 'nullable|string|max:191',
            'text' => 'nullable|string|max:65535',
            'file_id' => 'nullable|exists:files,id'
        ]);

        $module = Module::findOrFail($id);
        $module->update($request->only(['title', 'subtitle', 'text']));

        $module->files()->sync($request->file_id);

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
        if($module->elements()->exists()) {
            return response()->json(['status' => 'error', 'data' => 'Deze module heeft elementen: verwijder eerst de elmenten!']);
        }

        // delete parent ranking
        $fileId = $module->files()->first()->id;
        $file = File::findOrFail($fileId);
        $file->ranking()->delete();

        // delete own ranking
        $module->ranking()->delete();

        // delete relation with file
        $module->files()->sync([]);

        $module->delete();
        return response()->json(['status' => 'success', 'data' => "{$module->title} is verwijderd!"], 200);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function rank(Request $request, $id)
    {
        $module = Module::findOrFail($id);

        if($module->ranking()->exists()) {
            $module->ranking()->update([
                'ranking' => $request->ranking
            ]);
        } else {
            $module->ranking()->create([
                'type' => 'module',
                'ranking' => $request->ranking
            ]);
        }

        return response()->json(['status' => 'success', 'data' => "{$module->title} is rangschikt!"], 200);
    }
}
