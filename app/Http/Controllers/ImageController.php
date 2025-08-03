<?php

namespace App\Http\Controllers;

use App\Models\GeneratedImage;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImageController extends Controller
{
    public function download(GeneratedImage $image): StreamedResponse
    {
        if (!Storage::disk('public')->exists($image->image_path)) {
            abort(404, 'Image not found');
        }

        return Storage::disk('public')->download($image->image_path, $image->filename);
    }

    public function view(GeneratedImage $image): Response
    {
        if (!Storage::disk('public')->exists($image->image_path)) {
            abort(404, 'Image not found');
        }

        $content = Storage::disk('public')->get($image->image_path);
        $mimeType = Storage::disk('public')->mimeType($image->image_path);

        return response($content, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $image->filename . '"',
        ]);
    }
}
