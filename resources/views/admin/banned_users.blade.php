@extends('admin.layout')

@section('title', 'Banned Users')

@section('header', 'Banned Users')

@section('content')
<div class="container">
  <h1>Banned Users</h1>

  <table class="table table-striped table-bordered">
    <thead class="thead-dark">
      <tr>
        <th>User Name</th>
        <th>Reason</th>
        <th>Banned At</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($bannedUsers as $ban)
      <tr>
        <td>{{ $ban->user->name }}</td>
        <td>{{ $ban->reason }}</td>
        <td>{{ $ban->banned_at }}</td>
        <td>
          <form action="{{ route('admin.unban_user', $ban->user_id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-success">Unban</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection