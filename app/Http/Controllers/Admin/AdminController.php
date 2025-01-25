<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->is_admin) {
            Auth::login($user);
            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'Invalid credentials or not an admin.');
    }

    public function dashboard()
    {
        $totalUsers = User::count();

        $dailyRegistrations = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return view('admin.dashboard', compact('totalUsers', 'dailyRegistrations'));
    }

    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->filled('nickname')) {
            $query->where('nickname', 'like', '%' . $request->nickname . '%');
        }

        if ($request->filled('user_id')) {
            $query->where('id', $request->user_id);
        }

        $users = $query->paginate(10);

        return view('admin.users', compact('users'));
    }
}
