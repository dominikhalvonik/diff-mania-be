<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title')</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  @yield('styles')
  @yield('scripts')
</head>

<body class="font-roboto bg-gray-100 flex flex-col min-h-screen">
  <header class="bg-gray-800 text-white p-5 text-center">
    <h1 class="text-4xl">@yield('header')</h1>
  </header>
  <nav class="bg-gray-700 p-3">
    <ul class="flex justify-center space-x-6">
      <li><a href="{{ route('admin.dashboard') }}" class="text-white hover:text-gray-300">Dashboard</a></li>
      <li><a href="{{ route('admin.users') }}" class="text-white hover:text-gray-300">Users</a></li>
      <li><a href="{{ route('admin.banned_users') }}" class="text-white hover:text-gray-300">Banned Users</a></li>
    </ul>
  </nav>
  <div class="container mx-auto p-5 flex-1">
    <div class="bg-white shadow-md rounded-lg p-5 mb-5">
      @yield('content')
    </div>
  </div>
  <footer class="bg-gray-800 text-white text-center p-3 mt-auto">
    &copy; {{ date('Y') }} Makači s.r.o. All rights reserved.
  </footer>
  @yield('chart-scripts')
</body>

</html>