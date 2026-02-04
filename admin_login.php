<?php
session_start();
include 'db.php';

$error_msg = "";

if (isset($_POST['btn_login'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM admins WHERE username = '$user' AND password = '$pass'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        $_SESSION['admin_user'] = $user;
        header("Location: dashboard_overview.php");
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
    <style>
        :root {
            --primary: #009688;
            --primary-dark: #00796b;
            --bg-body: #f4f6f9;
            --text-color: #333;
            --danger: #e74c3c;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-body);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: var(--text-color);
        }
        .login-card {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 350px;
            text-align: center;
            border: 1px solid #eaeaea;
        }
        h2 {
            margin-top: 0;
            color: var(--primary);
            font-weight: 300;
            margin-bottom: 25px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            background-color: #fafafa;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        input:focus {
            border-color: var(--primary);
            background-color: #fff;
            outline: none;
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14px;
            cursor: pointer;
            margin-top: 15px;
            transition: background-color 0.3s;
        }
        .btn-login:hover {
            background-color: var(--primary-dark);
        }
        .error {
            color: var(--danger);
            font-size: 14px;
            margin-bottom: 15px;
            background: #fdecea;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #fadbd8;
        }
        .link {
            display: inline-block;
            margin-top: 20px;
            font-size: 13px;
            color: #666;
            text-decoration: none;
        }
        .link:hover { text-decoration: underline; color: var(--primary); }
    </style>
</head>
<body>

    <div class="login-card">
        <h2>Admin Login</h2>
        
        <?php if ($error_msg): ?>
            <div class="error"><?php echo $error_msg; ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Username" required autocomplete="off">
            <input type="password" name="password" placeholder="Password" required autocomplete="off">
            <input type="submit" name="btn_login" value="Login" class="btn-login">
        </form>

        <a href="index.php" class="link">&larr; Back to Home</a>
    </div>

</body>
</html>