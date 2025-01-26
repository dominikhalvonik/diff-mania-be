<!DOCTYPE html>
<html>

<head>
  <title>Admin Login</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-to-r from-purple-600 to-blue-500 flex justify-center items-center h-screen">
  <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md text-center">
    <h2 class="mb-6 text-2xl font-semibold text-gray-800">Admin Login</h2>
    @if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
      {{ session('error') }}
    </div>
    @endif
    <form method="POST" action="{{ route('admin.login.submit') }}">
      @csrf
      <div class="mb-4 text-left">
        <label class="block text-gray-700 mb-2">Email:</label>
        <input type="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>
      <div class="mb-6 text-left">
        <label class="block text-gray-700 mb-2">Password:</label>
        <input type="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>
      <button type="submit" class="w-full py-2 bg-blue-500 text-white rounded hover:bg-blue-700 transition duration-300">Login</button>
    </form>
  </div>
</body>

</html>