@extends('admin.layout')

@section('title', 'Banned Users')

@section('header', 'Banned Users')

@section('content')
<div class="container mx-auto p-4">
  <h1 class="text-2xl font-bold mb-4">Banned Users</h1>

  <div class="overflow-x-auto">
    <table class="min-w-full bg-white border border-gray-200">
      <thead class="bg-gray-800 text-white">
        <tr>
          <th class="py-2 px-4 border-b">User Name</th>
          <th class="py-2 px-4 border-b">Reason</th>
          <th class="py-2 px-4 border-b">Banned At</th>
          <th class="py-2 px-4 border-b">Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($bannedUsers as $ban)
        <tr class="hover:bg-gray-100">
          <td class="py-2 px-4 border-b">{{ $ban->user->name }}</td>
          <td class="py-2 px-4 border-b">{{ $ban->reason }}</td>
          <td class="py-2 px-4 border-b">{{ $ban->banned_at }}</td>
          <td class="py-2 px-4 border-b">
            <form action="{{ route('admin.unban_user', $ban->user_id) }}" method="POST">
              @csrf
              @method('DELETE')
              <button type="submit" class="bg-green-500 text-white py-1 px-3 rounded hover:bg-green-600">Unban</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection