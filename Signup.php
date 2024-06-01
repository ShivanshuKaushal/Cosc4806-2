<?php
require_once('database.php');
require_once('user.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $_REQUEST['username'];
    $password = $_REQUEST['password'];
    $confirm_password = $_REQUEST['confirm_password'];
    $db = db_connect();
    $validation_error = [];

    if (empty($username)) {
        $validation_error[] = "Username cannot be empty.";
    }

    if ($password !== $confirm_password) {
        $validation_error[] = "Passwords do not match.";
    }

    if (strlen($password) < 8 || 
        !preg_match('/[A-Z]/', $password) || 
        !preg_match('/[a-z]/', $password) || 
        !preg_match('/[0-9]/', $password) || 
        !preg_match('/[\W_]/', $password)) {
        $validation_error[] = "Password must be at least 8 characters long, contain both uppercase and lowercase letters, at least one number, and at least one special character.";
    }

    if (empty($validation_error)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $statement = $db->prepare("INSERT into users (username, password) VALUES (?, ?)");
        $statement->execute([$username, $hash]);
    
        session_start();
        $_SESSION['username'] = $username;
        $_SESSION['authenticated'] = 1;
        unset($_SESSION['failed_attempts']);

        header("Location: ./login.php");
        exit();
    } else {
        echo '<ul style="color: red;">';
        foreach ($validation_error as $error) {
            echo '<li>' . $error . '</li>';
        }
        echo '</ul>';
    }    
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign-Up Page</title>
</head>
<body>
    <h1>Sign-Up Form</h1>

    <form action="/Signup.php" method="post">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username"><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password"><br>
        <label for="confirm_password">Confirm Password:</label><br>
        <input type="password" id="confirm_password" name="confirm_password"><br><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>
