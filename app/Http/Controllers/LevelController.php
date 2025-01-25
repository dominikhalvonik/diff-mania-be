<?php

namespace App\Http\Controllers;

use App\Models\LevelImage;
use App\Services\ExperienceService;
use App\Models\LogTable;
use Illuminate\Http\Request;
use App\Models\Level;
use App\Models\Image;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class LevelController extends Controller
{
    public function getLevelDataWithImages(Request $request, string $levelId)
    {
        // Fetch the level data and add it to cache
        $level = Cache::remember('level_' . $levelId, 120, function () use ($levelId) {
            return Level::find($levelId);
        });

        $levelImages = LevelImage::where('level_id', $level->id)->pluck('image_name')->all();

        $imageInformations = Image::whereIn('name', $levelImages)->get();

        // Revert the string json_diff to an array
        $imageInformations->each(function ($image) {
            $image->json_diff = json_decode($image->json_diff, true);
        });

        return response()->json($imageInformations);
    }

    public function winLevel(Request $request, Level $level, ExperienceService $experienceService)
    {
        $user = $request->user();

        $request->validate([
            'stars_collected' => 'required|integer|min:0|max:3'
        ]);

        // Check if the achieved stars_collected is higher then the stars_collected allready achieved
        $currentProgress = $user->userLevelProgress()->where('level_id', $level->id)->first();

        if ($currentProgress && $currentProgress->stars_collected >= $request->stars_collected) {
            return response()->json(['message' => 'Level allready finished with more stars']);
        }

        DB::transaction(function () use ($user, $level, $request) {
            $actualStars = $user->userLevelProgress()->where('level_id', $level->id)->first()->stars_collected ?? 0;
            $actualPoints = $user->userLevelProgress()->where('level_id', $level->id)->first()->points_achieved ?? 0;
            $actualImagesDone = $user->userLevelProgress()->where('level_id', $level->id)->first()->finished_image_names ?? '[]';
            $parsedImagesDone = json_decode($actualImagesDone, true);

            $finishedImages = array_unique(array_merge($parsedImagesDone, $request->images_finished));

            $encodedFinishedImages = json_encode($finishedImages);

            $levelImages = LevelImage::where('level_id', $level->id)->pluck('image_name')->all();
            $isCompleted = count($finishedImages) === count($levelImages);

            $user->userLevelProgress()->updateOrCreate(
                [
                    'user_id' => $user->id,
                    'level_id' => $level->id,
                ],
                [
                    'stars_collected' => $actualStars > $request->stars_collected ? $actualStars : $request->stars_collected,
                    'completed' => $isCompleted,
                    'points_achieved' => $actualPoints > $request->score ? $actualPoints : $request->score,
                    'finished_image_names' => (string) $encodedFinishedImages,
                ]
            );
        });

        // TODO: Find from DB
        $experienceGained = 100;

        LogTable::create([
            'user_id' => $user->id,
            'log_info' => 'Won level ' . $level->id . ' with ' . $request->stars_collected . ' stars' . ' and ' . $request->score . ' points' . ' and ' . count($request->images_finished) . ' images finished' . ' and ' . $experienceGained . ' experience gained',
        ]);

        $result = $experienceService->actualizeExperience($user, $experienceGained);

        return response()->json(['message' => 'Level finished', ...$result]);
    }

    public function lossLevel(Request $request, Level $level)
    {
        // If a player looses a level remove one of his lives (if he has more then 0) and setup the timer for lives regeneration - log it in log table
        $user = $request->user();

        $user->load('userAttributes');

        $lives = $user->userAttributes()->where('user_attribute_definition_id', User::LIVES)->first()->value;
        if ($lives === 0) {
            return response()->json(['message' => 'No lives left', 'lives' => $lives]);
        }

        $lives -= 1;

        $user->userAttributes()->where('user_attribute_definition_id', User::LIVES)->update(['value' => $lives]);

        // Check if the last refill timer is set
        $lastRefillTimer = $user->userAttributes->where('user_attribute_definition_id', User::LAST_REFILL_TIMER)->first();
        if ($lastRefillTimer && $lastRefillTimer->value === 0) {
            $user->userAttributes()->where('user_attribute_definition_id', User::LAST_REFILL_TIMER)->update(['value' => time()]);
        }

        LogTable::create([
            'user_id' => $user->id,
            'log_info' => 'Lost level ' . $level->id
        ]);

        return response()->json(['message' => 'Level lost', 'lives' => $lives]);
    }
}
