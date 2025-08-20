<?php

namespace App\Http\Controllers;

use App\Models\MediaFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaFileController extends Controller
{
    /**
     * Upload a File.
     * 
     * @param \Illuminate\Http\Request $request
     * @return MediaFile
     */
    public function uploadFile(Request $request)
    {
        $request->validate([
            'title' => 'string|sometimes',
            'description' => 'string|required',
            'file' => 'file|required|max:8192',
        ]);

        $file = $request->file('file');
        
        $path = Storage::disk('mediafiles')->putFile($file);

        //If title not present in the request body, take filename withouth extension
        $title = $request->has('title') ? $request->input('title') : pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME);

        $mfile = new MediaFile([
            'title' => $title,
            'description' => $request->input('description'),
            'originalName' => $file->getClientOriginalName(),
            'path' => $path,
            'mimeType' => $file->getMimeType(),
            'extension' => $file->getClientOriginalExtension(),
            'size' => $file->getSize()
        ]);
        $mfile->save();

        return $mfile;
    }


    /**
     * Fetch all File infos.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Collection<int, MediaFile>
     */
    public function getAllFiles(Request $request)
    {
        return MediaFile::all();
    }

    /**
     * Get specific File Info.
     * 
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return MediaFile|\Illuminate\Http\JsonResponse
     */
    public function getFileInfo(Request $request, string $id)
    {
        $fileInfo = MediaFile::find($id); 
        if(!$fileInfo){
            return response()->json([
                "message" => "File not found."
            ],404);
        } 
        return $fileInfo;  
        
    }

    /**
     * Download specific File.
     * 
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function downloadFile(Request $request, string $id)
    {
        $fileInfo = MediaFile::find($id); 
        if(!$fileInfo){
            return response()->json([
                "message" => "File not found."
            ],404);
        }

        if (!Storage::disk('mediafiles')->exists($fileInfo->path)) {
            return response()->json([
                "message" => "Missing file."
            ],404);
            
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('mediafiles');
        return $disk->download($fileInfo->path,$fileInfo->originalName);
        
    }

    /**
     * Delete a File.
     * 
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFile(Request $request, string $id)
    {
        $fileInfo = MediaFile::find($id); 
        if(!$fileInfo){
            return response()->json([
                "message" => "File not found."
            ],404);
        } 

        if (!Storage::disk('mediafiles')->exists($fileInfo->path)) {
            return response()->json([
                "message" => "Missing file."
            ],404);    
        }

        Storage::disk('mediafiles')->delete($fileInfo->path);
        $delete_status = $fileInfo->delete();
        if($delete_status){
            return response()->json([
                "message" => "File deleted successfully."
            ],204); 
        }else{
            return response()->json([
                "message" => "Failed to delete file."
            ],500); 
        }
    }
}
