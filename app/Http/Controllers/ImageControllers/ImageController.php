<?php
namespace App\Http\Controllers\ImageControllers;

use App\Http\Controllers\Controller;
use App\Repositories\ImageRepository;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageController extends Controller{
    protected ImageRepository $imageRepository;

    public function __construct(ImageRepository $imageRepository){
        $this->imageRepository = $imageRepository;
    }

    // ================ GET IMAGES ==============

    public function index(Request $request): JsonResponse{
        $id = $request->route('id', '');
        $name = $request->query('name', '');
        $path = $request->query('path', '');
        $image = $this->imageRepository->get($id, $name, $path);
        if (!$image) {
            return response()->json(['message' => 'Image not found'], status: 404);
        }
        return response()->json($image, 200);
    }

    //================ POST IMAGE ==============

    public function uploadImage(Request $request): JsonResponse{
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $date = now()->format('Y-m-d');

        if (!$request->hasFile('image')) {
            return response()->json(['message' => 'No image file provided'], 400);
        }

        $path = $request->file('image')->store($date, 'public'); 

        return response()->json([
            'message' => 'Image uploaded successfully',
            'path' => Storage::url($path),
            'name' => $request->file('image')->getClientOriginalName(),
        ], 201);
    }

    public function saveImage(Request $request): JsonResponse{
        $request->validate([
            'path' => 'required|string',
            'name' => 'required|string',
            'alt' => 'nullable|string',
        ]);
        
        $image = $this->imageRepository->create([
            'path' => $request['path'],
            'name' => $request['name'],
            'alt' => $request->input('alt', 'Default Alt Text')
        ]);
        return response()->json(['message' => 'Image handled successfully', 'image' => $image], 200);
    }

    //================ DELETE IMAGE ==============

    public function destroy(Request $request): JsonResponse{
        $id = $request->route('id');
        $image = $this->imageRepository->get($id, '', '');
        $data = $image->items();
        if (!$data) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        $path = $data[0]->path;
        $storage = "/storage/";
        $path = Str::replaceFirst($storage, '', $path);
        $check = Storage::disk('public')->delete($path);

        if (!$check) {
            return response()->json(['message' => 'Failed to delete image from storage'], 500);
        }

        $this->imageRepository->delete($id);

        return response()->json(['message' => 'Image deleted successfully'], 200);
    }
}



?>