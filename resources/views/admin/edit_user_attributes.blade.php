@extends('admin.layout')

@section('title', 'Edit User Attributes')

@section('header', 'Edit User Attributes')

@section('content')

<div class="container">
  <h1 class="my-4">Edit User Attributes for {{ $user->name }}</h1>

  <form action="{{ route('admin.update_user_attributes', $user->id) }}" method="POST">
    @csrf
    @method('PUT')

    <table class="table table-striped table-bordered">
      <thead class="thead-dark">
        <tr>
          <th>Attribute Name</th>
          <th>Description</th>
          <th>Value</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($definitions as $definition)
        @php
        $attribute = $attributes->firstWhere('user_attribute_definition_id', $definition->id);
        @endphp
        <tr>
          <td>{{ $definition->name }}</td>
          <td>{{ $definition->description }}</td>
          <td>
            <input type="text" name="attributes[{{ $attribute->id }}]" value="{{ $attribute->value }}" class="form-control">
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <div class="d-flex justify-content-between mt-4">
      <button type="submit" class="btn btn-primary">Save Changes</button>
      <a href="{{ route('admin.users') }}" class="btn btn-secondary">Back</a>
    </div>
  </form>
</div>
@endsection