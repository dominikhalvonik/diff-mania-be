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
          <table class="table table-bordered table-hover">
            <thead class="thead-dark">
              <tr>
                <th>Booster Name</th>
                <th>Description</th>
                <th>Amount</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($boosters as $booster)
              @php
              $userBooster = $userBoosters->firstWhere('booster_id', $booster->id);
              @endphp
              <tr>
                <td>{{ $booster->name }}</td>
                <td>{{ $booster->description }}</td>
                <td>
                  <div class="input-group">
                    <input type="number" name="boosters[{{ $booster->id }}]" value="{{ $userBooster ? $userBooster->quantity : 0 }}" class="form-control text-center" min="0">
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="form-group mt-3">
          <button type="submit" class="btn btn-success">Save Changes</button>
          <a href="{{ route('admin.users') }}" class="btn btn-secondary">Back</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection