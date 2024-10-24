<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadFileRequest;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FilesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $file = File::all();
        return response()->json([
            'file' => $file,
        ], 200);
    }

    /**
     * Display single files from the database.
     */
    public function fileDisplay($id)
    {
        // $file = File::findOrFail($id);
        // return response()->json([
        //     'file' => $file,
        // ], 200);

        $file = File::findOrFail($id);
        return response()->json([
            'file' => [
                'id' => $file->id,
                'title' => $file->title,
                'description' => $file->description,
                'dxf_path' => $file->dxf_path,
                'picture_path' => $file->picture_path,
                'created_at' => $file->created_at->diffForHumans(),
            ],
        ], 200);
    }

    /**
     * Upload files to the database.
     */
    public function upload(UploadFileRequest $request)
    {


        $dxfFile = $request->file('dxf_path');
        $dxfFileName = time() . '.dxf'; // Force .dxf extension
        $dxfPath = $dxfFile->storeAs('dxf_files', $dxfFileName, 'public');

        // Store the image file as usual
        $imagePath = $request->file('picture_path')->store('image_files', 'public');

        $file = File::create([
            'title' => $request->title,
            'description' => $request->description,
            'dxf_path' => url(Storage::url($dxfPath)),
            'picture_path' => url(Storage::url($imagePath)),
        ]);

        //Return a json response to the frontend
        return response()->json([
            'message' => 'Files Uploded Successfully',
            'files' => $file
        ], 201);
    }

    public function download($id)
    {
        $file = File::findOrFail($id);
        $filePath = $file->dxf_path;

        if (Storage::exists($filePath)) {
            return Storage::download($filePath);
        }

        return response()->json([
            'message' => 'File not found!',
        ], 404);
    }
}
