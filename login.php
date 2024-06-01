<?php
session_start();

require_once('database.php');

$db = db_connect();

if (isset($_SESSION['DB_DOWN'])) {
  echo "The database is down.";
  exit;
}

if (isset($_SESSION['authenticated'])) {
  header("Location: index.php");
  exit;
}

if (!isset($_SESSION['failed_attempts'])) {
  $_SESSION['failed_attempts'] = 0;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];


  $statement = $db->prepare("SELECT * FROM users WHERE username = ?");
  $statement->execute([$username]);
  $user = $statement->fetch(PDO::FETCH_ASSOC);

  if ($user && password_verify($password, $user['password'])) {
    $_SESSION['username'] = $username;
    $_SESSION['authenticated'] = 1;
    $_SESSION['failed_attempts'] = 0; 
    header("Location: index.php");
    exit;
  } else {
    $_SESSION['failed_attempts'] += 1;
    echo "Invalid username or password.";
  }
}

if ($_SESSION['failed_attempts'] > 0) {
  echo "This is unsuccessful attempt number " . $_SESSION['failed_attempts'] . ".";
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <h1>Login Form</h1>
    <form action="/login.php" method="post">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username"><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password"><br><br>
        <input type="submit" value="Submit">
    </form>
    <footer><a href= "/Signup.php">Click here to Create account</a></footer>
</body>
</html>