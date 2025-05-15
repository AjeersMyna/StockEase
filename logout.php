<?php
// logout.php

// Start the session (if it's not already started)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the login page
header("Location: login.php"); // Change "login.php" to your actual login page URL
exit();
?>
