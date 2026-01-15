<?php
session_start();
include 'db.php';
$error_msg = "";

if (isset($_POST['btn_login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE username = '$user' AND password = '$pass'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        $_SESSION['admin_user'] = $user;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error_msg = "Invalid Username or Password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div style="width: 300px; margin: 50px auto; padding: 20px; background: white; border: 1px solid #ddd; border-radius: 5px;">
        <h2 style="text-align:center;">Admin Login</h2>
        
        <?php if ($error_msg) echo "<p style='color:red; text-align:center;'>$error_msg</p>"; ?>

        <form method="POST">
            <label>Username:</label>
            <input type="text" name="username" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <input type="submit" name="btn_login" value="Login" style="width:100%;">
        </form>
        <p style="text-align:center;"><a href="index.php">Back to Home</a></p>
    </div>
</body>
</html>