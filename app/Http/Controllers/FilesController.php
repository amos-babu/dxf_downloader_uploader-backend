<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadFileRequest;
use App\Http\Resources\FileDisplayResource;
use App\Http\Resources\FileResource;
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
        $file = File::select('id', 'title', 'picture_path')
            ->get();
        return FileResource::collection($file);
    }

    /**
     * Display single files from the database.
     */
    public function fileDisplay($id)
    {
        $file = File::select('id', 'title', 'description', 'picture_path', 'created_at')
            ->findOrFail($id);

        return new FileDisplayResource($file);
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
            'user_id' => auth()->id(),
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

    /**
     * Search dxf files from the database.
     */

    public function search(Request $request)
    {
        $search = $request->input('search');
        $result = File::where('title', 'like', '%' . $search . '%')
            ->orWhere('description', 'like', '%' . $search . '%')
            ->get();

        return response()->json([
            'results' => $result
        ]);
    }

    /**
     * Download dxf files from the database.
     */

    public function downloadDxf($id)
    {
        $file = File::findOrFail($id);
        $filePath = $file->dxf_path;

        if (Storage::exists($filePath)) {
            // return Storage::download($filePath);
            return response()->download($filePath);
        }

        return response()->json([
            'message' => 'File not found!',
        ], 404);
    }

    /**
     * Download image files from the database.
     */

    public function downloadImage($id)
    {
        $file = File::findOrFail($id);
        $filePath = $file->dxf_path;

        if (Storage::exists($filePath)) {
            // return Storage::download($filePath);
            return response()->download($filePath);
        }

        return response()->json([
            'message' => 'File not found!',
        ], 404);
    }
}
