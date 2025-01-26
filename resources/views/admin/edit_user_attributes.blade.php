@extends('admin.layout')

@section('title', 'Edit User Attributes')

@section('header', 'Edit User Attributes')

@section('content')

<div class="container mx-auto px-4 py-8">
  <h1 class="text-3xl font-bold mb-6">Edit User Attributes for {{ $user->name }}</h1>

  <form action="{{ route('admin.update_user_attributes', $user->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="overflow-x-auto">
      <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
        <thead class="bg-gray-800 text-white">
          <tr>
            <th class="py-3 px-6 text-left">Attribute Name</th>
            <th class="py-3 px-6 text-left">Description</th>
            <th class="py-3 px-6 text-left">Value</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($definitions as $definition)
          @php
          $attribute = $attributes->firstWhere('user_attribute_definition_id', $definition->id);
          @endphp
          <tr class="border-b hover:bg-gray-100">
            <td class="py-3 px-6">{{ $definition->name }}</td>
            <td class="py-3 px-6">{{ $definition->description }}</td>
            <td class="py-3 px-6">
              <input type="text" name="attributes[{{ $attribute->id }}]" value="{{ $attribute->value }}" class="form-control border rounded w-full py-2 px-3">
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
@endsection