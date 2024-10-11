<?php
require "config.php";

// Redirect to login if not logged in
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit();
}
// Get the user role from the session
$role = $_SESSION["role"];
$user_id = $_SESSION["user_id"];

?>

<h1>Welcome to Admin Dashboard</h1>


<h1>Hello, <?php echo $role; ?>!</h1>