<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Verify Your Email Address</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }
    .container {
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
      background-color: #ffffff;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    h1 {
      color: #333333;
    }
    p {
      color: #666666;
      line-height: 1.5;
    }
    .button {
      display: inline-block;
      padding: 10px 20px;
      font-size: 16px;
      color: #fff;
      background-color: #007bff;
      text-decoration: none;
      border-radius: 5px;
      margin-top: 20px;
    }
    .footer {
      margin-top: 20px;
      font-size: 12px;
      color: #999999;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Verify Your Email Address</h1>
    <p>Hi {{ $user->name }},</p>
    <p>Thank you for registering with us. Please click the button below to verify your email address:</p>
    <p>
      <a href="{{ $verificationUrl }}" class="button">Verify Email Address</a>
    </p>
    <p>If you did not create an account, no further action is required.</p>
    <p>Regards,<br>{{ config('app.name') }}</p>
    <p class="footer">If you’re having trouble clicking the "Verify Email Address" button, copy and paste the URL below into your web browser: <a href="{{ $verificationUrl }}">{{ $verificationUrl }}</a></p>
  </div>
</body>
</html>