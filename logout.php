<?php
session_start(); // Access the current session

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page or homepage
header('Location: login.php');
exit;
?>