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
use App\Models\Ban;
use Illuminate\Support\Facades\Hash;


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

        if (Hash::check($request->password, $user->password) && $user->is_admin) {
            Auth::login($user);
            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'Invalid credentials or not an admin.');
    }

    public function dashboard()
    {
        $totalUsers = User::count();
        $totalBannedUsers = Ban::count();
        $recentRegistrations = User::orderBy('created_at', 'desc')->take(10)->get();
        $activeUsers = User::whereHas('userAttributes', function ($query) {
            $query->where('user_attribute_definition_id', User::LAST_LOGIN_DATE)
                ->where('value', '>=', Carbon::now()->subDay()->toDateTimeString());
        })->count();
        $totalBoosters = UserBooster::sum('quantity');

        $dailyRegistrations = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy('date');

        $dates = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $dates->put($date, $dailyRegistrations->get($date, (object) ['date' => $date, 'count' => 0]));
        }

        $dailyRegistrations = $dates->values();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalBannedUsers',
            'recentRegistrations',
            'activeUsers',
            'totalBoosters',
            'dailyRegistrations'
        ));
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

    public function banUser($userId)
    {
        $user = User::findOrFail($userId);
        return view('admin.ban_user', compact('user'));
    }

    public function storeBan(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        Ban::create([
            'user_id' => $userId,
            'banned_by' => Auth::id(),
            'reason' => $request->input('reason'),
            'banned_at' => Carbon::now(),
        ]);

        return redirect()->route('admin.users')->with('success', 'User banned successfully.');
    }

    public function bannedUsers()
    {
        $bannedUsers = Ban::with('user')->get();
        return view('admin.banned_users', compact('bannedUsers'));
    }

    public function unbanUser($userId)
    {
        $ban = Ban::where('user_id', $userId)->firstOrFail();
        $ban->delete();

        return redirect()->route('admin.banned_users')->with('success', 'User unbanned successfully.');
    }
}
