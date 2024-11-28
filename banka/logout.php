<?php
session_start();

// Unset the user's login status
unset($_SESSION["logged_in"]);

// Destroy the session
session_destroy();

// Redirect to the login page
header("Location: ../login.php");
exit;
?>