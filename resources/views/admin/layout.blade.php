<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title')</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    header {
      background-color: #333;
      color: #fff;
      padding: 20px;
      text-align: center;
    }

    nav {
      background-color: #444;
      padding: 10px;
    }

    nav ul {
      list-style: none;
      padding: 0;
      margin: 0;
      display: flex;
      justify-content: center;
    }

    nav ul li {
      margin: 0 15px;
    }

    nav ul li a {
      color: #fff;
      text-decoration: none;
      transition: color 0.3s;
    }

    nav ul li a:hover {
      color: #ddd;
    }

    .content {
      flex: 1;
      padding: 20px;
      background-color: #fff;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      margin: 20px;
      border-radius: 8px;
    }

    footer {
      background-color: #333;
      color: #fff;
      text-align: center;
      padding: 10px;
    }
  </style>
  @yield('styles')
  @yield('scripts')
</head>

<body>
  <header>
    <h1>@yield('header')</h1>
  </header>
  <nav>
    <ul>
      <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
      <li><a href="{{ route('admin.users') }}">Users</a></li>
      <li><a href="{{ route('admin.banned_users') }}">Banned Users</a></li>
    </ul>
  </nav>
  <div class="content">
    @yield('content')
  </div>
  <footer>
    &copy; {{ date('Y') }} Makači s.r.o. All rights reserved.
  </footer>
  @yield('chart-scripts')
</body>

</html>