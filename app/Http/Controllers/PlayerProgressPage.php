<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PlayerProgressPage extends Controller
{
    /**
     * Return the players episodes with associated levels.
     */
    public function index(Request $request)
    {
        // Check if the user has allready some progress
        $user = $request->user();
        $progress = $user->playerLevelProgress()->get();
        $progress = $progress->groupBy('level_id');

        // Get all episodes with associated levels and cache it to redis
        $episodes = Cache::remember('episodes', 120, function () {
            return Episode::with('levels')->get();
        });

        // If the user has no progress data then return the episodes with the associated levels and
        // add an attribute to each level which will be named 'progress' and will have a value of 0.
        if ($progress->isEmpty()) {
            $episodes->each(function ($episode) {
                $episode->levels->each(function ($level) {
                    $level->setAttribute('progress', 0);
                });
            });

            return response()->json($episodes);
        }

        Log::debug($progress);
        // If the user has progress data then return the episodes with the associated
        // levels and add an attribute to each level which will be named 'progress' and will have
        // the value of progress from the PlayerLevelProgress model. If there are no progress data for a level then the progress will be 0.
        $episodes->each(function ($episode) use ($progress) {
            $episode->levels->each(function ($level) use ($progress) {
                $level->setAttribute('progress', $progress->get($level->id)?->first()?->progress ?? 0);
            });
        });

        return response()->json($episodes);

    }
}
