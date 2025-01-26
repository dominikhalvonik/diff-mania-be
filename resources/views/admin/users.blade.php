@extends('admin.layout')

@section('title', 'Admin Users')

@section('header', 'Users')

@section('content')
<form method="GET" action="{{ route('admin.users') }}" class="mb-6">
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
        <input type="text" name="email" value="{{ request('email') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Nickname:</label>
        <input type="text" name="nickname" value="{{ request('nickname') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">User ID:</label>
        <input type="text" name="user_id" value="{{ request('user_id') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>
    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Filter</button>
</form>
<table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
    <thead class="bg-gray-800 text-white">
        <tr>
            <th class="py-3 px-6 text-left">ID</th>
            <th class="py-3 px-6 text-left">Email</th>
            <th class="py-3 px-6 text-left">Nickname</th>
            <th class="py-3 px-6 text-left">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
        <tr>
            <td class="py-2 px-4 border-b border-gray-200">{{ $user->id }}</td>
            <td class="py-2 px-4 border-b border-gray-200">{{ $user->email }}</td>
            <td class="py-2 px-4 border-b border-gray-200">{{ $user->nickname }}</td>
            <td class="py-2 px-4 border-b border-gray-200">
                <form method="GET" action="{{ route('admin.edit_user_attributes', ['user' => $user->id]) }}" class="inline">
                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline">Edit Attributes</button>
                </form>
                <form method="GET" action="{{ route('admin.edit_user_boosters', ['user' => $user->id]) }}" class="inline">
                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline">Edit Boosters</button>
                </form>
                <form method="GET" action="{{ route('admin.ban_user', ['user' => $user->id]) }}" class="inline">
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline">Ban</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="mt-6">
    {{ $users->links() }}
</div>
@endsection