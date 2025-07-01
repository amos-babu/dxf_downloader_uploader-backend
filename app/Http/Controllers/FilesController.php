<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadFileRequest;
use App\Http\Resources\FileDisplayResource;
use App\Http\Resources\FileResource;
use App\Http\Resources\SearchFilesResource;
use App\Models\File;
use App\Services\ImageProcessing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FilesController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $file = File::with(['user' => function ($query) {
            $query->select('id', 'username', 'profile_pic_path');
        }])->select('id', 'title', 'user_id', 'picture_path')->latest()->get();

        return FileResource::collection($file);
    }

    public function displaySimilarFiles($id, ImageProcessing $imageProcess)
    {
        $file = File::select('picture_path')->findOrFail($id);
        $imageProcess->similarImages($file->picture_path);
    }

    // Display single files from the database.
    public function fileDisplay($id)
    {
        $file = File::with('user')
            ->select('id', 'user_id', 'title', 'description', 'picture_path', 'created_at')
            ->findOrFail($id);

        return new FileDisplayResource($file);
    }

    // Upload files to the database.
    public function upload(UploadFileRequest $request)
    {
        $dxfFile = $request->file('dxf_path');
        $dxfFileName = time().'.dxf';
        $dxfPath = $dxfFile->storeAs('dxf_files', $dxfFileName, 'public');

        $imagePath = $request->file('picture_path')->store('image_files', 'public');

        $file = File::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'dxf_path' => $dxfPath,
            'picture_path' => $imagePath,
        ]);

        return response()->json([
            'message' => 'Files Uploded Successfully',
            'files' => $file,
        ], 201);
    }

    // Search dxf files from the database. Return a collection
    public function search(Request $request)
    {
        // search query request from the input
        $query = $request->input('query');

        $result = File::where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->get();

        return SearchFilesResource::collection($result);
    }

    // Download dxf files from the database.
    public function downloadDxf($id)
    {
        $file = File::findOrFail($id);
        $filePath = $file->dxf_path;
        $localPath = storage_path("app/public/{$filePath}");

        if (file_exists($localPath)) {
            return response()->download($localPath);
        }

        return response()->json([
            'message' => 'Dxf file not found!',
        ], 404);
    }

    //  Download image files from the database.
    public function downloadImage($id)
    {
        $file = File::findOrFail($id);
        $filePath = $file->picture_path;
        $localPath = storage_path("app/public/{$filePath}");

        if (file_exists($localPath)) {
            return response()->download($localPath);
        }

        return response()->json([
            'message' => 'Image file not found!',
        ], 404);
    }
}
