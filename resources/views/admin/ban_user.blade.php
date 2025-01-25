@extends('admin.layout')

@section('title', 'Ban User')

@section('header', 'Ban User')

@section('content')
<div class="container">
  <h1>Ban User: {{ $user->name }}</h1>

  <form action="{{ route('admin.store_ban', $user->id) }}" method="POST">
    @csrf

    <div class="form-group">
      <label for="reason">Reason for Ban</label>
      <textarea name="reason" id="reason" class="form-control" rows="3"></textarea>
    </div>

    <button type="submit" class="btn btn-danger">Ban User</button>
  </form>
</div>
@endsection