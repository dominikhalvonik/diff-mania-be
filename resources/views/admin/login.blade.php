<!DOCTYPE html>
<html>
<head>
  <title>Admin Login</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(to right, #6a11cb, #2575fc);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .login-container {
      background-color: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      width: 500px;
      text-align: center;
    }
    .login-container h2 {
      margin-bottom: 25px;
      color: #333;
      font-size: 24px;
    }
    .login-container label {
      display: block;
      margin-bottom: 8px;
      color: #555;
      text-align: left;
    }
    .login-container input {
      width: 94%;
      padding: 12px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
    }
    .login-container button {
      width: 100%;
      padding: 12px;
      background-color: #007bff;
      border: none;
      border-radius: 5px;
      color: #fff;
      font-size: 18px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    .login-container button:hover {
      background-color: #0056b3;
    }
    .login-container .forgot-password {
      margin-top: 15px;
      display: block;
      color: #007bff;
      text-decoration: none;
      font-size: 14px;
    }
    .login-container .forgot-password:hover {
      text-decoration: underline;
    }
    .alert {
      color: #721c24;
      background-color: #f8d7da;
      border-color: #f5c6cb;
      padding: 10px;
      margin-bottom: 20px;
      border-radius: 5px;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>Admin Login</h2>
    @if (session('error'))
      <div class="alert">
        {{ session('error') }}
      </div>
    @endif
    <form method="POST" action="{{ route('admin.login.submit') }}">
      @csrf
        <label>Email:</label>
        <input type="email" name="email" required>
        <label>Password:</label>
        <input type="password" name="password" required>
      <button type="submit">Login</button>
    </form>
  </div>
</body>
</html>