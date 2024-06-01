<?php
session_start();
require_once('database.php');
require_once('user.php');
if (!isset($_SESSION['authenticated'])) {
  header("Location: login.php");
}

require_once('user.php');

$user = new User();
$user_list = $user->get_all_users();

echo "<pre>";
print_r($user_list);

?>

<html>
  <head>
    <title>Shinahu  </title>
  </head>

  <body>
    <h1>Assignment 2</h1>
    <p> Welcome, <?=$_SESSION['username'] ?></p> 
    <?php echo "Today is " . date("l, F j, Y") . "."?>

  </body>

  <footer> <a href="/logout.php">Click here to Logout</a> </footer>
</html>