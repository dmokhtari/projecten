<?php

namespace App\Http\Controllers\API;

use App\Models\File;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Storage;

class FileController extends Controller
{
    /**
     * FileController constructor.
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
        $files = File::all();
        return response()->json(['status' => 'success', 'data' => $files], 200);
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
            'background_color' => 'nullable|string|max:255',
            'background_image' => 'nullable|image',
        ]);
        $image_path = null;
        $image_name = null;
        if($request->hasFile('background_image')) {
            $image_name = $request->file('background_image')->getClientOriginalName();
            $image_path = $request->file('background_image')->store('img/files');
        }
        File::create([
            'user_id' => Auth::guard('api')->user()->id,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'background_name' => $image_path,
            'background_path' => $image_name,
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
        $file = File::findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $file], 200);
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
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'background_image' => 'nullable|image',
            'background_color' => 'nullable|string|max:255'
        ]);

        $file = File::findOrFail($id);
        $file->fill($request->only(['title', 'subtitle', 'background_color']));

        if($request->hasFile('background_image')) {
            $file->background_name = $request->file('background_image')->getClientOriginalName();
            $file->background_path = $request->file('background_image')->store('img/files');
        }
        $file->save();

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
        $file = File::findOrFail($id);
        if($file->background_path) Storage::delete($file->background_path);
        $file->delete();
        return response()->json(['status' => 'success', 'data' => "{$file->title} is verwijderd!"], 200);
    }
}
