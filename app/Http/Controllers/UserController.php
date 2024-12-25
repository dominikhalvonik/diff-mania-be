<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // TODO: REMOVE IN PRODUCTION
    public function getUserData(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Check if the user is authenticated
        if ($user) {
            // Return the user's data
            return response()->json($user);
        } else {
            // Return an unauthorized response
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}
