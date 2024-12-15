<?php
session_start();
require 'koneksi.php';

// Proses Login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['role'] = $user['role'];

        // Redirect berdasarkan role
        if ($user['role'] == 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: user.php");
        }
        exit();
    } else {
        $error = "Email atau PIN salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   
    <title>Login - Konser</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
        }
        .logo {
            margin-bottom: 20px;
        }
        .logo img {
            width: 80px;
        }
        .form-control {
            border-radius: 10px;
            margin-bottom: 15px;
        }
        .btn-primary {
            width: 100%;
            border-radius: 10px;
        }
        .forgot-link, .signup-link {
            font-size: 0.9rem;
            color: #6a11cb;
            text-decoration: none;
        }
        .forgot-link:hover, .signup-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="login-container">
    <div class="logo">
        <img src="https://i.pinimg.com/736x/d8/23/fd/d823fd3829afe6cfbbff0328cbea8a83.jpg" alt="Logo Konser">
    </div>
    <h4>Hi, welcome to KONSER!</h4>
    <p>Please fill up your information to continue and enjoy the features</p>
    

    <form method="POST" action="">
        <input type="email" name="email" class="form-control" placeholder="Email address" required>
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        <a href="#" class="forgot-link">Forgot your PIN?</a>
        <button type="submit" class="btn btn-primary">Sign In</button>
    </form>
</div>
</body>
</html>


