@extends('admin.layout')

@section('title', 'Edit User Boosters')

@section('header', 'Edit User Boosters')

@section('content')
<div class="container mt-5">
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <h1 class="h3">Edit Boosters for {{ $user->name }}</h1>
    </div>

    <div class="card-body">
      <form action="{{ route('admin.update_user_boosters', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="table-responsive">
          <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-gray-800 text-white">
              <tr>
                <th class="py-3 px-6 text-left">Booster Name</th>
                <th class="py-3 px-6 text-left">Description</th>
                <th class="py-3 px-6 text-left">Amount</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($boosters as $booster)
              @php
              $userBooster = $userBoosters->firstWhere('booster_id', $booster->id);
              @endphp
              <tr class="border-b hover:bg-gray-100">
                <td class="py-3 px-6">{{ $booster->name }}</td>
                <td class="py-3 px-6">{{ $booster->description }}</td>
                <td class="py-3 px-6">
                  <div class="input-group">
                    <input type="number" name="boosters[{{ $booster->id }}]" value="{{ $userBooster ? $userBooster->quantity : 0 }}" class="form-control text-center" min="0">
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="flex justify-between mt-6">
          <button type="submit" class="bg-blue-500 text-white py-2 px-6 rounded hover:bg-blue-700">Save Changes</button>
          <a href="{{ route('admin.users') }}" class="bg-gray-500 text-white py-2 px-6 rounded hover:bg-gray-700">Back</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection