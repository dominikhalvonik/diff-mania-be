@extends('admin.layout')

@section('title', 'Admin Users')

@section('header', 'Users')

@section('content')
<style>
    form {
        margin-bottom: 20px;
    }

    form div {
        margin-bottom: 10px;
    }

    label {
        display: inline-block;
        width: 100px;
    }

    input[type="text"] {
        padding: 5px;
        width: 200px;
    }

    button {
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #0056b3;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th,
    td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    .pagination {
        margin-top: 20px;
        text-align: center;
    }
</style>

<form method="GET" action="{{ route('admin.users') }}">
    <div>
        <label>Email:</label>
        <input type="text" name="email" value="{{ request('email') }}">
    </div>
    <div>
        <label>Nickname:</label>
        <input type="text" name="nickname" value="{{ request('nickname') }}">
    </div>
    <div>
        <label>User ID:</label>
        <input type="text" name="user_id" value="{{ request('user_id') }}">
    </div>
    <button type="submit">Filter</button>
</form>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Nickname</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->nickname }}</td>
            <td>
                <form method="GET" action="{{ route('admin.edit_user_attributes', ['user' => $user->id]) }}" style="display:inline;">
                    <button type="submit">Edit Attributes</button>
                </form>
                <form method="GET" action="{{ route('admin.edit_user_boosters', ['user' => $user->id]) }}" style="display:inline;">
                    <button type="submit">Edit Boosters</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="pagination">
    {{ $users->links() }}
</div>
@endsection