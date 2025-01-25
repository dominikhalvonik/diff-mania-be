<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\AdminLogTable;
use App\Models\UserAttribute;
use App\Models\UserAttributeDefinition;
use App\Models\Booster;
use App\Models\UserBooster;

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

    public function editUserAttributes($userId)
    {
        $user = User::findOrFail($userId);
        $attributes = UserAttribute::where('user_id', $userId)->get();
        $definitions = UserAttributeDefinition::all();

        return view('admin.edit_user_attributes', compact('user', 'attributes', 'definitions'));
    }

    public function updateUserAttributes(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $attributes = $request->input('attributes');

        foreach ($attributes as $attributeId => $value) {
            $attribute = UserAttribute::findOrFail($attributeId);
            $attribute->value = $value;
            $attribute->save();

            // Log the change
            AdminLogTable::create([
                'user_id' => Auth::id(),
                'log_info' => 'Admin updated user attribute ' . $attributeId . ' for user ' . $user->nickname . ' to ' . $value,
            ]);
        }

        return redirect()->route('admin.users')->with('success', 'User attributes updated successfully.');
    }

    public function editUserBoosters($userId)
    {
        $user = User::findOrFail($userId);
        $boosters = Booster::all();
        $userBoosters = UserBooster::where('user_id', $userId)->get();

        return view('admin.edit_user_boosters', compact('user', 'boosters', 'userBoosters'));
    }

    public function updateUserBoosters(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $boosters = $request->input('boosters');

        foreach ($boosters as $boosterId => $amount) {
            $userBooster = UserBooster::firstOrNew(['user_id' => $userId, 'booster_id' => $boosterId]);
            $userBooster->quantity = $amount;
            $userBooster->save();
        }

        return redirect()->route('admin.users')->with('success', 'User boosters updated successfully.');
    }
}
