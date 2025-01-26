@extends('admin.layout')

@section('title', 'Ban User')

@section('header', 'Ban User')

@section('content')
<div class="container mx-auto p-4">
  <h1 class="text-2xl font-bold mb-4">Ban User: {{ $user->name }}</h1>

  <form action="{{ route('admin.store_ban', $user->id) }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    @csrf

    <div class="mb-4">
      <label for="reason" class="block text-gray-700 text-sm font-bold mb-2">Reason for Ban</label>
      <textarea name="reason" id="reason" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="3"></textarea>
    </div>

    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Ban User</button>
  </form>
</div>
@endsection