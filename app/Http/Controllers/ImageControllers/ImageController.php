<?php
namespace App\Http\Controllers\ImageControllers;

use App\Http\Controllers\Controller;
use App\Repositories\ImageRepository;
use App\Models\News;
use App\Models\Images;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Pail\ValueObjects\Origin\Console;

class ImageController extends Controller{
    protected ImageRepository $imageRepository;

    public function __construct(ImageRepository $imageRepository){
        $this->imageRepository = $imageRepository;
    }

    public function uploadTempImage(Request $request)
    {
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if (!$request->hasFile('image')) {
            return response()->json(['message' => 'No image file provided'], 400);
        }

        $path = $request->file('image')->store('temp', 'public'); 

        return response()->json([
            'message' => 'Image uploaded successfully',
            'imagePath' => Storage::url($path),
            'imageName' => $request->file('image')->getClientOriginalName(),
        ], 201);
    }

    public function tempImageHandle(Request $request){
        $request->validate([
            'imagePath' => 'required|string',
            'imageName' => 'required|string',
            'newsId' => 'required|string',
            'imageAlt' => 'nullable|string',
        ]);
        
        $this->imageRepository->addImage([
            'newsId' => $request['newsId'],
            'imagePath' => $request['imagePath'],
            'imageName' => $request['imageName'],
            'imageAlt' => $request->input('imageAlt', 'Default Alt Text')
        ]);
        return response()->json(['message' => 'Image handled successfully'], 200);
    }

    public function uploadImages(Request $request, $newsId)
    {
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $news = News::find($newsId);
        if (!$news) {
            return response()->json(['message' => 'News not found'], 404);
        }

        if (!$request->hasFile('image')) {
            return response()->json(['message' => 'No image file provided'], 400);
        }

        $path = $request->file('image')->store("{$newsId}", 'public'); 

        $this->imageRepository->addImage([
            'newsId' => $newsId,
            'imagePath' => $path,
            'imageName' => $request->file('image')->getClientOriginalName(),
            'imageAlt' => $request->input('imageAlt', 'Default Alt Text')
        ]);

        return response()->json([
            'message' => 'Image uploaded successfully',
            'imagePath' => Storage::url($path),
            'imageName' => $request->file('image')->getClientOriginalName(),
        ], 201);
    }

    public function getImage($imageId)
    {
        $image = $this->imageRepository->getImageById($imageId);
        if (!$image) {
            return response()->json(['message' => 'Image not found'], 404);
        }
        return response()->json($image, 200);
    }

    public function getImagesId($newsId)
    {
        $images = $this->imageRepository->getImagesByNewsId($newsId);
        if ($images->isEmpty()) {
            return response()->json(['message' => 'No images found for this news'], 404);
        }
        return response()->json($images, 200);
    }
}



?>