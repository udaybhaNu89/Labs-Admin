<?php
// 1. Find the session
session_start();

// 2. Remove all session variables
session_unset();

// 3. Destroy the session completely
session_destroy();

// 4. Redirect to the login page (or index.php)
header("Location: index.php");
exit();
?>