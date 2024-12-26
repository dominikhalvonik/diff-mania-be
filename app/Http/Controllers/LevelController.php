<?php

namespace App\Http\Controllers;

use App\Models\LevelImage;
use Illuminate\Http\Request;
use App\Models\Level;
use App\Models\Image;
use Illuminate\Support\Facades\Cache;

class LevelController extends Controller
{
    public function getLevelDataWithImages(Request $request, string $levelId)
    {
        // Fetch the level data and add it to cache
        $level = Cache::remember('level_' . $levelId, 120, function () use ($levelId) {
            return Level::find($levelId);
        });

        $levelImages = LevelImage::where('level_id', $level->id)->pluck('image_id')->all();

        $imageInformations = Image::whereIn('id', $levelImages)->get();

        // Revert the string json_diff to an array
        $imageInformations->each(function ($image) {
            $image->json_diff = json_decode($image->json_diff, true);
        });

        return response()->json($imageInformations);
    }

    public function finishLevel(Request $request, Level $level)
    {
        $user = $request->user();

        $request->validate([
            'score' => 'required|integer|min:0|max:3'
        ]);

        // Check if the achieved score is higher then the score allready achieved
        $currentProgress = $user->playerLevelProgress()->where('level_id', $level->id)->first();

        if ($currentProgress && $currentProgress->progress >= $request->score) {
            return response()->json(['message' => 'Level allready finished with a higher score']);
        }

        $user->playerLevelProgress()->updateOrCreate(
            ['level_id' => $level->id],
            ['progress' => $request->score]
        );

        return response()->json(['message' => 'Level finished']);
    }
}
