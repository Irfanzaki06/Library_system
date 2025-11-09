<?php
session_start();
require 'conn.php';

if($_POST){
    $username = $_POST['username'];
    $password = $_POST['password'];

    
    if(empty($username) || empty($password)) {
        echo "<script>alert('You must fill in all fields');</script>";
    }
    elseif(strlen($username) < 5 || strlen($password) < 8) {
        echo "<script>alert('Username must be at least 5 characters and password must be at least 8 characters');</script>";
    }
    else {
      
        $result = login($username,$password);

        if($result) {
            $_SESSION['username'] = $username;
            header("Location: Menu.php");
            echo "<script>alert('Login Successful')</script>";
            exit();
        } 
        else {
            echo "<script>alert('Login Failed')</script>";
        }
    }
}
?>


<html>
<head>
  <title>Library Login</title>
  <style>
    body {
      background-image: url('librarybg.gif');
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center;
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .login-box {
      background: rgba(255, 255, 255, 0.9);
      padding: 30px;
      border-radius: 10px;
      text-align: center;
    }
    input {
      margin: 10px;
      padding: 10px;
      width: 200px;
    }
    button {
      padding: 10px 20px;
      background-color: #4285F4;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    button:hover {
      background-color: #306acb;
    }
  </style>
</head>
<body>
  <div class="login-box">
    <h2>Welcome To Library System</h2>
    <form method="POST">
      <input type="text" name="username" placeholder="Enter Username" required><br>
      <input type="password" name="password" placeholder="Enter Password" required><br>
      <button type="submit">Login</button>
    </form>
  </div>
</body>
</html>
