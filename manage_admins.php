<?php
require 'auth_session.php';

// =============================================================
// AUTO-UPDATE DB: Add permissions column if missing (Safety Check)
// =============================================================
$check_col = mysqli_query($conn, "SHOW COLUMNS FROM admins LIKE 'permissions'");
if (mysqli_num_rows($check_col) == 0) {
    mysqli_query($conn, "ALTER TABLE admins ADD COLUMN permissions VARCHAR(50) DEFAULT 'Full'");
}
// =============================================================

if (isset($_POST['btn_create_admin'])) {
    $u = $_POST['new_username']; $p = $_POST['new_password'];
    $creator = $_SESSION['admin_user'];
    
    // --- PERMISSION LOGIC ---
    // Directly capture the radio button value (defaults to 'Full' if missing)
    $perms = isset($_POST['perm_type']) ? $_POST['perm_type'] : 'Full';
    // ------------------------

    $check = mysqli_query($conn, "SELECT * FROM admins WHERE username = '$u'");
    if(mysqli_num_rows($check) > 0) { 
        $_SESSION['sys_msg'] = "Username taken!"; $_SESSION['sys_msg_color'] = "red";
    } else {
        mysqli_query($conn, "INSERT INTO admins (username, password, created_by, permissions) VALUES ('$u', '$p', '$creator', '$perms')");
        $_SESSION['sys_msg'] = "New Admin Created ($perms Permission)!"; $_SESSION['sys_msg_color'] = "green";
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
            <th>Permission</th>
            <th>Created By</th>
            <th>Action</th>
        </tr>
        <?php
        $res = mysqli_query($conn, "SELECT * FROM admins");
        while ($row = mysqli_fetch_assoc($res)) {
            $is_self = ($row['username'] === $_SESSION['admin_user']);
            $perm_display = isset($row['permissions']) ? $row['permissions'] : 'Full';
            
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
            echo "<td><span style='background:#e3f2fd; color:#1565c0; padding:2px 6px; border-radius:4px; font-size:11px; font-weight:bold;'>" . htmlspecialchars($perm_display) . "</span></td>";
            echo "<td>" . (isset($row['created_by']) ? htmlspecialchars($row['created_by']) : '-') . "</td>";
            
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
            
            <div style="margin-top: 15px; text-align: left; background: #fff; border: 1px solid #eee; padding: 15px; border-radius: 4px;">
                <p style="margin: 0 0 10px 0; font-size: 14px; color: #555; font-weight: bold;">Select Permission Level:</p>
                
                <div style="background: #f9f9f9; padding: 10px; border-left: 3px solid var(--primary); border-radius: 0 4px 4px 0;">
                    <label style="display: block; margin-bottom: 10px; font-size: 13px; cursor: pointer;">
                        <input type="radio" name="perm_type" value="Full" checked style="width: auto; margin-right: 8px; accent-color: var(--primary);"> 
                        <strong>Full Permission</strong> 
                        <div style="color: #777; margin-left: 24px; font-size: 11px;">Can manage admins, edit forms, and delete data.</div>
                    </label>
                    
                    <label style="display: block; font-size: 13px; cursor: pointer;">
                        <input type="radio" name="perm_type" value="Partial" style="width: auto; margin-right: 8px; accent-color: var(--primary);"> 
                        <strong>Partial Permission</strong>
                        <div style="color: #777; margin-left: 24px; font-size: 11px;">View only access. Cannot delete data or manage admins.</div>
                    </label>
                </div>
            </div>
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