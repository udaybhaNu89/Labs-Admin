<?php
require 'auth_session.php';

if (isset($_POST['btn_create_admin'])) {
    $u = $_POST['new_username']; $p = $_POST['new_password'];
    $creator = $_SESSION['admin_user'];
    $check = mysqli_query($conn, "SELECT * FROM admins WHERE username = '$u'");
    if(mysqli_num_rows($check) > 0) { 
        $_SESSION['sys_msg'] = "Username taken!"; $_SESSION['sys_msg_color'] = "red";
    } else {
        mysqli_query($conn, "INSERT INTO admins (username, password, created_by) VALUES ('$u', '$p', '$creator')");
        $_SESSION['sys_msg'] = "New Admin Created!"; $_SESSION['sys_msg_color'] = "green";
    }
    header("Location: manage_admins.php?view=list"); exit(); 
}

if (isset($_GET['delete_admin'])) {
    $id = intval($_GET['delete_admin']);
    $current_user = $_SESSION['admin_user'];
    $target_query = mysqli_query($conn, "SELECT username FROM admins WHERE id = $id");
    $target_data = mysqli_fetch_assoc($target_query);
    if ($target_data && $target_data['username'] === $current_user) {
        $_SESSION['sys_msg'] = "You cannot delete yourself!"; $_SESSION['sys_msg_color'] = "red";
    } else {
        mysqli_query($conn, "DELETE FROM admins WHERE id = $id");
        $_SESSION['sys_msg'] = "Admin Deleted Successfully"; $_SESSION['sys_msg_color'] = "green";
    }
    header("Location: manage_admins.php?view=list"); exit();
}

// Redirect to Change Password Page Logic (Navigation Handler)
if (isset($_POST['init_change_pass'])) {
    $_SESSION['edit_admin_id'] = $_POST['target_admin_id'];
    header("Location: change_password.php"); exit();
}

include 'header.php';
$view = $_GET['view'] ?? 'menu';
?>

<?php if ($view == 'menu'): ?>
    <div class="admin-card">
        <h2 style="color:var(--primary); margin-top:0;">Manage Admins</h2>
        <p style="color:#666; margin-bottom:20px; font-size:14px;">Select an action:</p>
        <a href="manage_admins.php?view=list" class="btn-block">View Admins</a>
        <a href="manage_admins.php?view=create" class="btn-block secondary">Create New Admin</a>
    </div>

<?php elseif ($view == 'list'): ?>
    <h1>Existing Admins</h1><hr>
    <table>
        <tr>
            <th>Admin Username</th>
            <th>Current Password</th>
            <th>Created By</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>
        <?php
        $res = mysqli_query($conn, "SELECT * FROM admins");
        while ($row = mysqli_fetch_assoc($res)) {
            $is_self = ($row['username'] === $_SESSION['admin_user']);
            
            echo "<tr>";
            echo "<td>" . $row['username'] . "</td>";
            echo "<td><span class='password-text masked'>********</span><span class='password-text real' style='display:none;'>" . $row['password'] . "</span><button class='btn-show' onclick='togglePass(this)'>Show</button></td>";
            echo "<td>" . (isset($row['created_by']) ? $row['created_by'] : '-') . "</td>";
            echo "<td>" . (isset($row['created_at']) ? $row['created_at'] : '-') . "</td>";
            
            echo "<td>";
            echo "<form method='POST' style='display:inline;'>";
            echo "<input type='hidden' name='target_admin_id' value='".$row['id']."'>";
            echo "<button type='submit' name='init_change_pass' class='btn-toggle' style='font-size:11px; padding:5px 10px; text-decoration:none; border:none; cursor:pointer;'>Change Password</button>";
            echo "</form> ";
            
            if ($is_self) { echo "<span class='btn-delete disabled' style='opacity:0.5; cursor:not-allowed; font-size:11px; padding:5px 10px;'>Delete</span>"; } 
            else { echo "<a href='manage_admins.php?delete_admin=".$row['id']."' class='btn-delete' style='font-size:11px; padding:5px 10px; text-decoration:none;' onclick='return confirm(\"Are you sure?\");'>Delete</a>"; }
            echo "</td></tr>";
        }
        ?>
    </table>
    <p style="text-align:center; margin-top:20px;">
        <a href="manage_admins.php?view=menu" class="btn-outline">&larr; Back to Menu</a>
    </p>

<?php elseif ($view == 'create'): ?>
    <div class="admin-card">
        <h2 style="color:var(--primary);">Create New Admin</h2>
        <form method="POST">
            <input type="text" name="new_username" placeholder="New Username" required>
            <input type="password" name="new_password" placeholder="New Password" required>
            <input type="submit" name="btn_create_admin" value="Create Admin" class="btn-block" style="margin-top:15px;">
        </form>
        <p style="text-align:center; margin-top:15px;">
            <a href="manage_admins.php?view=menu" class="btn-outline">&larr; Back to Menu</a>
        </p>
    </div>
<?php endif; ?>

</div>
</body>
</html>