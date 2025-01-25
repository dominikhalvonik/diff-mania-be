@extends('admin.layout')

@section('content')
<div class="container">
  <h1>Edit User Attributes for {{ $user->name }}</h1>

  <form action="{{ route('admin.update_user_attributes', $user->id) }}" method="POST">
    @csrf
    @method('PUT')

    <table class="table">
      <thead>
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

    <button type="submit" class="btn btn-primary">Save Changes</button>
  </form>
</div>
@endsection