<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadFileRequest;
use App\Http\Resources\FileDisplayResource;
use App\Http\Resources\FileResource;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FilesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $file = File::with(['user' => function ($query) {
            $query->select('id', 'username', 'profile_pic_path');
        }])->select('id', 'title', 'user_id', 'picture_path')->get();

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
        $dxfFileName = time() . '.dxf';
        $dxfPath = $dxfFile->storeAs('dxf_files', $dxfFileName, 'public');

        // Store the image file as usual
        $imagePath = $request->file('picture_path')->store('image_files', 'public');

        $file = File::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'dxf_path' => $dxfPath, //relative path
            'picture_path' => $imagePath,
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

        // Construct the full local path to the file in the 'public' disk
        $localPath = storage_path("app/public/{$filePath}");

        // Check if the file exists in the constructed path
        if (file_exists($localPath)) {
            return Storage::download($localPath);
        }

        // Return an error response if the file does not exist
        return response()->json([
            'message' => 'Dxf file not found!',
        ], 404);
    }

    // public function downloadDxf($id)
    // {
    //     $file = File::findOrFail($id);
    //     $filePath = $file->dxf_path;

    //     if (Storage::disk('public')->exists($filePath)) {
    //         return Storage::disk('public')->download($filePath);
    //     }

    //     return response()->json([
    //         'message' => 'Dxf file not found!',
    //     ], 404);
    // }

    /**
     * Download image files from the database.
     */

    public function downloadImage($id)
    {
        $file = File::findOrFail($id);
        $filePath = $file->picture_path;

        // Construct the full local path to the file in the 'public' disk
        $localPath = storage_path("app/public/{$filePath}");

        // Check if the file exists in the constructed path
        if (file_exists($localPath)) {
            return Storage::download($localPath);
        }

        // Return an error response if the file does not exist
        return response()->json([
            'message' => 'Image file not found!',
        ], 404);
    }


    // public function downloadImage($id)
    // {
    //     $file = File::findOrFail($id);
    //     $filePath = $file->picture_path;

    //     if (Storage::disk('public')->exists($filePath)) {
    //         return Storage::disk('public')->download($filePath);
    //     }

    //     return response()->json([
    //         'message' => 'Image file not found!'
    //     ], 404);
    // }
}
