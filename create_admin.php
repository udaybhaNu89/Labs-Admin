<?php
session_start();
include 'db.php';

// Security Check
if (!isset($_SESSION['admin_user'])) { header("Location: admin_login.php"); exit(); }

$msg = "";
$msg_color = "green";

if (isset($_POST['btn_create'])) {
    $u = $_POST['username'];
    $p = $_POST['password'];

    // Simple check to ensure not empty
    if(!empty($u) && !empty($p)){
        // Check if user already exists
        $check = mysqli_query($conn, "SELECT * FROM admins WHERE username = '$u'");
        if(mysqli_num_rows($check) > 0) {
            $msg = "Username already taken!";
            $msg_color = "red";
        } else {
            // Insert new admin
            $sql = "INSERT INTO admins (username, password) VALUES ('$u', '$p')";
            if(mysqli_query($conn, $sql)){
                $msg = "New Admin Created Successfully!";
                $msg_color = "green";
            } else {
                $msg = "Error: " . mysqli_error($conn);
                $msg_color = "red";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create New Admin</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Ensuring inputs fit the box perfectly like the login page */
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box; /* Keeps padding inside width */
        }
        
        .btn-toggle {
            width: 100%;
            padding: 10px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
        }
        .btn-toggle:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body style="background-color: #f4f6f9;">

    <div style="width:300px; margin:80px auto; padding:25px; background:white; border:1px solid #ddd; border-radius:5px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
        
        <h2 style="text-align:center; margin-top:0; color:#333;">Create New Admin</h2>
        
        <?php if($msg != ""): ?>
            <p style="text-align:center; color:<?php echo $msg_color; ?>; font-weight:bold; font-size:14px;">
                <?php echo $msg; ?>
            </p>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="New Username" required>
            <input type="password" name="password" placeholder="New Password" required>
            <input type="submit" name="btn_create" value="Create Admin" class="btn-toggle">
        </form>

        <p style="text-align:center; margin-top:15px; font-size:14px;">
            <a href="admin_dashboard.php" style="text-decoration:none; color:#555;">&larr; Back to Dashboard</a>
        </p>

    </div>

</body>
</html>