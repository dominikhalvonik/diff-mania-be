<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use function public_path;

class ImageController extends Controller
{
    public function count()
    {
        $path = public_path('images'); // Path to the 'public/images' folder

        // Check if the folder exists
        if (!File::exists($path)) {
            return response()->json(['message' => 'The folder does not exist.', 'count' => 0]);
        }

        // Get all directories in the folder
        $directories = File::directories($path);

        // Count the number of directories
        $folderCount = count($directories);

        return response()->json(['count' => $folderCount]);
    }
}
