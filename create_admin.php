<?php
session_start();
if (!isset($_SESSION['admin_user'])) { header("Location: admin_login.php"); exit(); }
include 'db.php';

$msg = "";
if (isset($_POST['btn_create'])) {
    $u = $_POST['new_user'];
    $p = $_POST['new_pass'];
    
    // Check if user exists
    $check = mysqli_query($conn, "SELECT * FROM admins WHERE username='$u'");
    if (mysqli_num_rows($check) > 0) {
        $msg = "Username already exists!";
    } else {
        mysqli_query($conn, "INSERT INTO admins (username, password) VALUES ('$u', '$p')");
        $msg = "New Admin Created Successfully!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Create New Admin</h2>
    <a href="admin_dashboard.php">Back to Dashboard</a>
    
    <?php if ($msg) echo "<p style='color:blue; font-weight:bold;'>$msg</p>"; ?>

    <form method="POST">
        <label>New Username:</label>
        <input type="text" name="new_user" required>

        <label>New Password:</label>
        <input type="password" name="new_pass" required>

        <input type="submit" name="btn_create" value="Create Admin">
    </form>
</body>
</html>