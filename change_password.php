<?php
require 'auth_session.php';

// Check if session ID is set
if (!isset($_SESSION['edit_admin_id'])) {
    header("Location: manage_admins.php?view=list"); 
    exit();
}

$id = intval($_SESSION['edit_admin_id']);
$admin_query = mysqli_query($conn, "SELECT username FROM admins WHERE id=$id");
$admin_data = mysqli_fetch_assoc($admin_query); 

if (isset($_POST['btn_update_password'])) {
    $new_p = $_POST['new_password'];
    mysqli_query($conn, "UPDATE admins SET password = '$new_p' WHERE id = $id");
    $_SESSION['sys_msg'] = "Password Updated!"; $_SESSION['sys_msg_color'] = "green";
    unset($_SESSION['edit_admin_id']);
    header("Location: manage_admins.php?view=list"); exit();
}

include 'header.php';
?>

<div class="admin-card">
    <h2 style="color:var(--primary);">Change Password</h2>
    <p style="color:#666;">For User: <strong><?php echo $admin_data['username']; ?></strong></p>
    <form method="POST">
        <input type="password" name="new_password" placeholder="Enter New Password" required>
        <input type="submit" name="btn_update_password" value="Update Password" class="btn-block" style="margin-top:15px;">
    </form>
    <p style="text-align:center; margin-top:15px;">
        <a href="manage_admins.php?view=list" class="btn-outline">&larr; Cancel</a>
    </p>
</div>

</div>
</body>
</html>
