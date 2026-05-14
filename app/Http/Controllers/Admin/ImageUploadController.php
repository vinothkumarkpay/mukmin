<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ImageUploadController extends Controller
{
    /**
     * Handle image uploads from the TinyMCE rich-text editor.
     *
     * TinyMCE expects a JSON response with a "location" key pointing
     * to the publicly accessible URL of the uploaded file.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:4096'],
        ]);

        $path = $request->file('file')->store('uploads', 'public');

        return response()->json([
            'location' => asset('storage/' . $path),
        ]);
    }
}
