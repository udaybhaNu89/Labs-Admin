<?php
session_start();

// Logout Logic
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: admin_login.php"); 
    exit();
}

// Security Check
if (!isset($_SESSION['admin_user'])) {
    header("Location: admin_login.php"); 
    exit();
}

// Database Connection
include 'db.php';

// Helper for System Messages
$sys_msg = "";
$sys_msg_color = "green";
if (isset($_SESSION['sys_msg'])) {
    $sys_msg = $_SESSION['sys_msg'];
    $sys_msg_color = $_SESSION['sys_msg_color'];
    unset($_SESSION['sys_msg']); 
    unset($_SESSION['sys_msg_color']);
}
?>