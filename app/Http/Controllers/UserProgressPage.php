<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Services\ProgressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UserProgressPage extends Controller
{
    /**
     * Return the users episodes with associated levels.
     */
    public function index(Request $request, ProgressService $progressService)

    {
        // Check if the user has allready some progress
        $user = $request->user();

        $episodes = $progressService->getProgress($user);

        return response()->json($episodes);
    }
}
