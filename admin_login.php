<?php
// Start a "session" to remember the user is logged in
session_start();

include 'db.php';

$error_msg = "";

// Check if the login button was clicked
if (isset($_POST['btn_login'])) {
    
    // Get the input from the form
    $user_input = $_POST['username'];
    $pass_input = $_POST['password'];

    // 1. Create the SQL command to find the user
    // We check if there is a row with THIS username AND THIS password
    $sql = "SELECT * FROM admins WHERE username = '$user_input' AND password = '$pass_input'";
    
    // 2. Run the command
    $result = mysqli_query($conn, $sql);

    // 3. Check if we found exactly one match
    if (mysqli_num_rows($result) === 1) {
        // Success! Login correct.
        
        // Save the username in the session variable
        $_SESSION['admin_user'] = $user_input;
        
        // Redirect to the dashboard
        header("Location: admin_dashboard.php");
        exit();
        
    } else {
        // Failure! No match found.
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

    <div style="width: 300px; margin: 100px auto; text-align: center; border: 1px solid #ccc; padding: 20px;">
        
        <h2>Admin Login</h2>
        
        <?php 
        if ($error_msg != "") { 
            echo "<p style='color:red;'>$error_msg</p>"; 
        } 
        ?>

        <form method="POST" action="">
            <label>Username:</label><br>
            <input type="text" name="username" required><br><br>

            <label>Password:</label><br>
            <input type="password" name="password" required><br><br>

            <input type="submit" name="btn_login" value="Login">
        </form>

        <br>
        <a href="index.php">Back to Home</a>
    </div>

</body>
</html>