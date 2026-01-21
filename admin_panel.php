<?php
session_start();
include 'db.php';

// =================================================================
// 1. AUTHENTICATION & LOGOUT
// =================================================================

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: admin_panel.php"); exit();
}

if (!isset($_SESSION['admin_user'])) {
    header("Location: admin_login.php"); exit();
}

// =================================================================
// 2. CONTEXT SWITCHING
// =================================================================

$current_view = $_GET['view'] ?? 'dashboard';
$return_view = $_REQUEST['return_view'] ?? $current_view;

if ($return_view == 'storage_management' || $return_view == 'storage_form') {
    $meta_table = "storage_sections"; 
    $data_table = "storage_unit";     
} else {
    $meta_table = "dynamic_sections"; 
    $data_table = "complaints";       
}

// =================================================================
// 3. ADMIN ACTIONS
// =================================================================

$sys_msg = "";
$sys_msg_color = "green";
if (isset($_SESSION['sys_msg'])) {
    $sys_msg = $_SESSION['sys_msg'];
    $sys_msg_color = $_SESSION['sys_msg_color'];
    unset($_SESSION['sys_msg']); unset($_SESSION['sys_msg_color']);
}

// --- SECURE NAVIGATION HANDLER ---
if (isset($_POST['init_change_pass'])) {
    $_SESSION['edit_admin_id'] = $_POST['target_admin_id'];
    header("Location: admin_panel.php?view=change_password");
    exit();
}

// --- DASHBOARD ACTIONS ---
if (isset($_GET['delete_complaint'])) {
    $id = intval($_GET['delete_complaint']);
    mysqli_query($conn, "DELETE FROM complaints WHERE id = $id");
    $_SESSION['sys_msg'] = "Complaint Deleted"; $_SESSION['sys_msg_color'] = "green";
    header("Location: admin_panel.php?view=dashboard"); exit();
}
if (isset($_GET['mark_complete'])) {
    $id = intval($_GET['mark_complete']);
    mysqli_query($conn, "UPDATE complaints SET status = 'Completed' WHERE id = $id");
    $_SESSION['sys_msg'] = "Status Updated to Completed"; $_SESSION['sys_msg_color'] = "green";
    header("Location: admin_panel.php?view=dashboard"); exit();
}

// --- STORAGE ACTIONS ---
if (isset($_GET['delete_storage'])) {
    $id = intval($_GET['delete_storage']);
    mysqli_query($conn, "DELETE FROM storage_unit WHERE id = $id");
    $_SESSION['sys_msg'] = "Log Entry Deleted"; $_SESSION['sys_msg_color'] = "green";
    header("Location: admin_panel.php?view=storage_dashboard"); exit();
}

if (isset($_POST['submit_storage_form'])) {
    $cols = ""; $vals = "";
    $res = mysqli_query($conn, "SELECT column_name FROM storage_sections ORDER BY display_order ASC");
    if(mysqli_num_rows($res) > 0) {
        while ($sec = mysqli_fetch_assoc($res)) {
            $col = $sec['column_name'];
            $val = "";
            if (isset($_POST[$col])) { $val = mysqli_real_escape_string($conn, $_POST[$col]); }
            $cols .= ", `$col`";
            $vals .= ", '$val'";
        }
        $sql = "INSERT INTO storage_unit (status $cols) VALUES ('Logged' $vals)";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['sys_msg'] = "Storage Log Added Successfully"; $_SESSION['sys_msg_color'] = "green";
        } else {
            $_SESSION['sys_msg'] = "Error: " . mysqli_error($conn); $_SESSION['sys_msg_color'] = "red";
        }
    } else {
        $_SESSION['sys_msg'] = "Error: No sections defined."; $_SESSION['sys_msg_color'] = "red";
    }
    header("Location: admin_panel.php?view=storage_form"); exit();
}

// --- CONFIG / STORAGE SECTION ACTIONS ---

// 1. Move Section
if (isset($_GET['move_section']) && isset($_GET['dir'])) {
    $id = intval($_GET['move_section']);
    $dir = $_GET['dir'];
    $curr = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id, display_order FROM $meta_table WHERE id = $id"));
    $curr_order = $curr['display_order'];
    
    if ($dir == 'up') {
        $target = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id, display_order FROM $meta_table WHERE display_order < $curr_order ORDER BY display_order DESC LIMIT 1"));
    } else {
        $target = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id, display_order FROM $meta_table WHERE display_order > $curr_order ORDER BY display_order ASC LIMIT 1"));
    }
    
    if ($target) {
        $t_id = $target['id']; $t_order = $target['display_order'];
        mysqli_query($conn, "UPDATE $meta_table SET display_order = $t_order WHERE id = $id");
        mysqli_query($conn, "UPDATE $meta_table SET display_order = $curr_order WHERE id = $t_id");
    }
    header("Location: admin_panel.php?view=$return_view"); exit();
}

// 2. Create Section
if (isset($_POST['create_new_section'])) {
    $title = trim($_POST['section_title']); 
    $title_safe = mysqli_real_escape_string($conn, $title);
    $type = $_POST['input_type']; 
    $is_unique = isset($_POST['is_unique']) ? 1 : 0; 
    
    $check = mysqli_query($conn, "SELECT id FROM $meta_table WHERE section_title = '$title_safe'");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['sys_msg'] = "$title already exists in this section list"; 
        $_SESSION['sys_msg_color'] = "red";
    } else {
        $clean_name = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $title));
        $clean_name = trim($clean_name, '_');
        $col = $clean_name; 
        $tbl = $clean_name; 
        
        $col_check = mysqli_query($conn, "SHOW COLUMNS FROM `$data_table` LIKE '$col'");
        $tbl_check = mysqli_query($conn, "SHOW TABLES LIKE '$tbl'");

        if(mysqli_num_rows($col_check) > 0 || mysqli_num_rows($tbl_check) > 0) {
             $_SESSION['sys_msg'] = "Error: Name '$clean_name' is already used by a column or another section."; 
             $_SESSION['sys_msg_color'] = "red";
             header("Location: admin_panel.php?view=$return_view"); exit();
        }
        
        $max = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MAX(display_order) as m FROM $meta_table")); 
        $next = $max['m'] + 1;
        
        mysqli_query($conn, "INSERT INTO $meta_table (section_title, column_name, input_type, display_order, is_unique) VALUES ('$title_safe', '$col', '$type', $next, $is_unique)");
        
        if ($return_view == 'config') {
            if ($is_unique != 1) { 
                mysqli_query($conn, "CREATE TABLE `$tbl` (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100), UNIQUE(name))"); 
            } else { 
                mysqli_query($conn, "CREATE TABLE `$tbl` (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100))"); 
            }
            mysqli_query($conn, "ALTER TABLE `$data_table` ADD COLUMN `$col` VARCHAR(255)");
        } else {
            if ($type == 'numeric') { mysqli_query($conn, "ALTER TABLE `$data_table` ADD COLUMN `$col` INT DEFAULT 0"); }
            elseif ($type == 'date') { mysqli_query($conn, "ALTER TABLE `$data_table` ADD COLUMN `$col` DATE"); } 
            else { mysqli_query($conn, "ALTER TABLE `$data_table` ADD COLUMN `$col` VARCHAR(255)"); }
        }
        $_SESSION['sys_msg'] = "Section Created Successfully"; $_SESSION['sys_msg_color'] = "green";
    }
    header("Location: admin_panel.php?view=$return_view"); exit();
}

// 3. Rename Section
if (isset($_POST['rename_section'])) {
    $id = $_POST['target_id']; 
    $new_name = trim($_POST['new_section_name']);
    $new_name_safe = mysqli_real_escape_string($conn, $new_name);
    
    $old_row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT section_title FROM $meta_table WHERE id = $id"));
    $old_name = $old_row['section_title'];

    $check = mysqli_query($conn, "SELECT id FROM $meta_table WHERE section_title = '$new_name_safe' AND id != $id");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['sys_msg'] = "$old_name can't be renamed as $new_name, because $new_name already exists"; 
        $_SESSION['sys_msg_color'] = "red";
    } else {
        $curr_query = mysqli_query($conn, "SELECT column_name, input_type FROM $meta_table WHERE id = $id");
        $curr_row = mysqli_fetch_assoc($curr_query);
        $old_col = $curr_row['column_name'];
        $input_type = $curr_row['input_type'];
        $new_col = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $new_name));
        $new_col = trim($new_col, '_');
        $sql_type = "VARCHAR(255)";
        if ($input_type == 'numeric') { $sql_type = "INT DEFAULT 0"; }
        elseif ($input_type == 'date') { $sql_type = "DATE"; }

        if ($old_col != $new_col) {
            mysqli_query($conn, "ALTER TABLE `$data_table` CHANGE `$old_col` `$new_col` $sql_type");
            if ($return_view == 'config') {
                mysqli_query($conn, "RENAME TABLE `$old_col` TO `$new_col`");
            }
        }
        mysqli_query($conn, "UPDATE $meta_table SET section_title = '$new_name_safe', column_name = '$new_col' WHERE id = $id");
        $_SESSION['sys_msg'] = "Section Renamed Successfully"; $_SESSION['sys_msg_color'] = "green";
    }
    header("Location: admin_panel.php?view=$return_view"); exit();
}

// 4. Remove Section
if (isset($_GET['remove_section'])) {
    $id = $_GET['remove_section'];
    $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM $meta_table WHERE id = $id"));
    if ($data) {
        $col = $data['column_name']; $tbl = $col; 
        if($return_view == 'config') { mysqli_query($conn, "DROP TABLE IF EXISTS `$tbl`"); }
        try { mysqli_query($conn, "ALTER TABLE `$data_table` DROP COLUMN `$col`"); } catch (Exception $e) {}
        mysqli_query($conn, "DELETE FROM $meta_table WHERE id = $id"); 
    }
    $_SESSION['sys_msg'] = "Section Removed"; $_SESSION['sys_msg_color'] = "red";
    header("Location: admin_panel.php?view=$return_view"); exit();
}

// 5. Add Option
if (isset($_POST['add_option']) && $return_view == 'config') {
    $target_col = $_POST['target_col']; $tbl = $target_col; 
    $raw_input = trim($_POST['new_val']); $items = [];
    
    $sec_query = mysqli_query($conn, "SELECT section_title, is_unique FROM $meta_table WHERE column_name = '$target_col'");
    $sec_info = mysqli_fetch_assoc($sec_query);
    $section_name = $sec_info['section_title'];
    $enforce_unique = ($sec_info['is_unique'] == 0); 

    if (preg_match('/^([a-zA-Z0-9\.-]*?)(\d+)-(\d+)$/', $raw_input, $matches)) {
        $prefix = $matches[1]; $start = (int)$matches[2]; $end = (int)$matches[3];
        if ($start <= $end) { for ($i = $start; $i <= $end; $i++) { $items[] = $prefix . $i; } }
    } else { $items[] = $raw_input; }
    
    $tbl_check = mysqli_query($conn, "SHOW TABLES LIKE '$tbl'");
    if (mysqli_num_rows($tbl_check) == 0) {
        $_SESSION['sys_msg'] = "Error: Table missing."; $_SESSION['sys_msg_color'] = "red";
        header("Location: admin_panel.php?view=$return_view"); exit();
    }

    $duplicates_found = [];
    foreach ($items as $val) {
        $val_safe = mysqli_real_escape_string($conn, $val);
        if ($enforce_unique) {
            $check = mysqli_query($conn, "SELECT id FROM `$tbl` WHERE name = '$val_safe'");
            if (mysqli_num_rows($check) > 0) {
                $duplicates_found[] = $val;
            } else {
                mysqli_query($conn, "INSERT INTO `$tbl` (name) VALUES ('$val_safe')");
            }
        } else {
            mysqli_query($conn, "INSERT INTO `$tbl` (name) VALUES ('$val_safe')");
        }
    }

    if (!empty($duplicates_found)) {
        $dup_list = implode(", ", $duplicates_found);
        $_SESSION['sys_msg'] = "$section_name takes unique values only, and the value(s) '$dup_list' already exists";
        $_SESSION['sys_msg_color'] = "red";
    } else {
        $_SESSION['sys_msg'] = "Items Added Successfully"; 
        $_SESSION['sys_msg_color'] = "green";
    }
    header("Location: admin_panel.php?view=$return_view"); exit(); 
}

if (isset($_GET['del_opt_id']) && $return_view == 'config') {
    $col = $_GET['target']; $tbl = $col; $id = $_GET['del_opt_id'];
    mysqli_query($conn, "DELETE FROM `$tbl` WHERE id=$id");
    header("Location: admin_panel.php?view=$return_view"); exit();
}

// --- MANAGE ADMINS ACTIONS ---

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
    header("Location: admin_panel.php?view=create_admin&sub_view=list"); exit(); 
}

if (isset($_POST['btn_update_password'])) {
    if (isset($_SESSION['edit_admin_id'])) {
        $id = intval($_SESSION['edit_admin_id']);
        $new_p = $_POST['new_password'];
        mysqli_query($conn, "UPDATE admins SET password = '$new_p' WHERE id = $id");
        $_SESSION['sys_msg'] = "Password Updated!"; $_SESSION['sys_msg_color'] = "green";
        unset($_SESSION['edit_admin_id']);
        header("Location: admin_panel.php?view=create_admin&sub_view=list"); exit();
    } else {
        header("Location: admin_panel.php?view=create_admin"); exit();
    }
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
    header("Location: admin_panel.php?view=create_admin&sub_view=list"); exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <style>
        :root { --primary: #009688; --primary-dark: #00796b; --bg-body: #f4f6f9; --text-color: #333; --danger: #e74c3c; --success: #27ae60; --card-shadow: 0 4px 15px rgba(0,0,0,0.1); --border-radius: 8px; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: var(--bg-body); color: var(--text-color); }
        a { text-decoration: none; }
        .navbar { background-color: #34495e; padding: 0 20px; display: flex; align-items: center; justify-content: space-between; height: 60px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .nav-links a { float: left; display: block; color: white; text-align: center; padding: 20px 16px; font-size: 14px; font-weight: 500; transition: background 0.3s; }
        .nav-links a:hover, .nav-links a.active { background-color: #2c3e50; border-bottom: 3px solid var(--primary); }
        .logout-btn { color: #ff6b6b !important; border: 1px solid #ff6b6b; border-radius: 4px; padding: 6px 15px !important; line-height: normal; font-weight: bold; transition: 0.3s; }
        .logout-btn:hover { background: #ff6b6b; color: white !important; }
        .container { padding: 10px; max-width: 87.5%; margin: 0 auto; }
        h1 { color: var(--primary); font-weight: 300; margin-bottom: 20px; }
        hr { border: 0; height: 1px; background: #ddd; margin-bottom: 30px; }
        .column, .priority-box, .create-section-box, .admin-card, .form-card { background: white; padding: 25px; margin-bottom: 20px; border-radius: var(--border-radius); box-shadow: var(--card-shadow); border: 1px solid #eaeaea; width: 100%; box-sizing: border-box; }
        .admin-card { max-width: 350px; margin: 50px auto; padding: 40px; text-align: center; }
        
        /* STANDARD INPUTS (For all except date) */
        input[type="text"], input[type="password"], input[type="number"], select, textarea { width: 100%; padding: 12px; margin: 8px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; background-color: #fafafa; font-size: 14px; transition: border-color 0.3s; }
        input:focus, select:focus, textarea:focus { border-color: var(--primary); background-color: #fff; outline: none; }
        
        /* GOOGLE FORM STYLE DATE INPUT */
        .google-date-wrapper { background-color: #f5f5f5; border-bottom: 1px solid #80868b; border-radius: 4px 4px 0 0; margin-bottom: 15px; transition: background 0.3s, border-color 0.3s; }
        .google-date-wrapper:hover { background-color: #eceff1; }
        .google-date-wrapper:focus-within { background-color: #e3f2fd; border-bottom: 2px solid var(--primary); }
        .google-date-input { width: 100%; border: none; background: transparent; padding: 12px 12px; font-size: 14px; outline: none; color: #333; font-family: inherit; box-sizing: border-box; height: 45px; }

        label { display: flex; align-items: center; gap: 10px; font-size: 14px; color: #555; margin: 10px 0; justify-content: center; }
        input[type="checkbox"] { width: 18px; height: 18px; accent-color: var(--primary); cursor: pointer; }
        button, input[type="submit"], .btn-toggle, .btn-add, .btn-cancel, .btn-delete, .btn-safety-toggle, .btn-edit-trigger, .btn-show { border: none; border-radius: 4px; cursor: pointer; font-size: 13px; font-weight: 600; padding: 10px 15px; transition: background 0.3s; }
        .btn-block { display: block; width: 100%; padding: 12px 0; margin-bottom: 15px; background-color: var(--primary); color: white; text-decoration: none; border-radius: 4px; font-weight: bold; text-align: center; box-sizing: border-box; }
        .btn-block:hover { background-color: var(--primary-dark); }
        .btn-block.secondary { background-color: #34495e; }
        .btn-block.secondary:hover { background-color: #2c3e50; }
        .btn-toggle, .btn-add, .btn-edit-trigger { background-color: var(--primary); color: white; }
        .btn-toggle:hover, .btn-add:hover, .btn-edit-trigger:hover { background-color: var(--primary-dark); }
        .btn-cancel { background-color: #95a5a6; color: white; }
        .btn-delete, .btn-remove { background-color: var(--danger); color: white; padding: 6px 12px; border-radius: 4px; }
        .btn-delete:hover, .btn-remove:hover { background-color: #c0392b; }
        .btn-remove { font-size: 11px; padding: 4px 8px; margin-left: 10px; }
        .btn-show { background-color: #3498db; color: white; padding: 4px 8px; font-size: 11px; margin-left: 5px; }
        .btn-show:hover { background-color: #2980b9; }
        .btn-back { display: inline-block; padding: 8px 15px; border: 1px solid #ccc; background-color: white; color: #555; border-radius: 4px; font-size: 13px; font-weight: 600; transition: all 0.3s; text-decoration: none; }
        .btn-back:hover { border-color: var(--primary); color: var(--primary); background-color: #f0fdfc; }
        .btn-arrow { background: none; border: none; font-size: 18px; color: #7f8c8d; padding: 0 10px; cursor: pointer; }
        .btn-arrow:hover { color: var(--primary); }
        .button-group { display: flex; gap: 10px; margin-top: 10px; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: var(--border-radius); overflow: hidden; box-shadow: var(--card-shadow); }
        th { background-color: var(--primary); color: white; padding: 15px; font-weight: 500; text-transform: uppercase; font-size: 13px; }
        td { padding: 12px 15px; border-bottom: 1px solid #eee; text-align: center; color: #555; }
        tr:last-child td { border-bottom: none; }
        tr:hover { background-color: #f9f9f9; }
        .col-status { width: 180px; min-width: 180px; white-space: nowrap; }
        .status-pending { color: #e67e22; font-weight: bold; background: #fff3e0; padding: 4px 8px; border-radius: 4px; font-size: 12px; }
        .status-completed { color: var(--success); font-weight: bold; background: #e8f5e9; padding: 4px 8px; border-radius: 4px; font-size: 12px; }
        .btn-complete { background-color: var(--success); color: white; padding: 5px 10px; border-radius: 4px; font-size: 12px; margin-left: 5px; transition: 0.2s; text-decoration: none; display: inline-block;}
        .btn-complete:hover { background-color: #219150; }
        .header-row { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #f0f0f0; padding-bottom: 15px; margin-bottom: 15px; }
        .priority-row { display: flex; justify-content: space-between; align-items: center; background: #f9f9f9; padding: 10px 15px; margin-bottom: 8px; border: 1px solid #eee; border-radius: 4px; }
        .btn-up, .btn-down { background-color: #95a5a6; color: white; padding: 4px 10px; border-radius: 4px; margin-left: 2px; font-size: 12px; }
        .btn-disabled { background-color: #e0e0e0; color: #fff; padding: 4px 10px; border-radius: 4px; pointer-events: none; }
        .action-bar { background-color: #f8f9fa; padding: 15px; border-radius: 6px; margin-bottom: 15px; border: 1px solid #eee; display: flex; flex-direction: column; gap: 10px; }
        .rename-box { display: none; background: #fffbe6; padding: 10px; border: 1px solid #e6dbb9; margin-bottom: 5px; }
        .remove-wrapper { display: flex; gap: 10px; }
        .btn-action-remove { flex: 3; background-color: #e74c3c; color: white; padding: 10px; text-align: center; border-radius: 4px; font-size: 13px; cursor: pointer; }
        .btn-action-remove.disabled { background-color: #e0e0e0; color: #aaa; pointer-events: none; }
        .btn-safety-toggle { background-color: #34495e; color: white; flex: 1; }
        ul { padding: 0; margin: 0; list-style: none; }
        li { background: white; border-bottom: 1px solid #f0f0f0; padding: 10px 5px; display: flex; justify-content: space-between; align-items: center; font-size: 14px; color: #555; }
        .password-text { font-family: monospace; background: #eee; padding: 2px 6px; border-radius: 4px; color: #333; }
        .btn-outline { display: inline-block; padding: 8px 16px; border: 1px solid #ccc; background-color: white; color: #555; text-decoration: none; border-radius: 4px; font-size: 14px; font-weight: 500; transition: all 0.3s ease; }
        .btn-outline:hover { border-color: var(--primary); color: var(--primary); background-color: #f9f9f9; }
    </style>
    <script>
        function toggle(id) { var x=document.getElementById(id); x.style.display=(x.style.display==="none")?"block":"none"; }
        function toggleFlex(id) { var x=document.getElementById(id); x.style.display=(x.style.display==="none")?"flex":"none"; }
        function toggleSafety(remId, togId) {
            var r = document.getElementById(remId); var t = document.getElementById(togId);
            if (t.innerHTML === "ENABLE") { t.innerHTML = "DISABLE"; r.classList.remove("disabled"); r.style.pointerEvents="auto"; r.style.backgroundColor="#e74c3c"; } 
            else { t.innerHTML = "ENABLE"; r.classList.add("disabled"); r.style.pointerEvents="none"; r.style.backgroundColor="#999"; }
        }
        function toggleList(listId, btn) {
            var list = document.getElementById(listId);
            if (list.style.display === "none") { list.style.display = "block"; btn.innerHTML = "&#9660;"; } 
            else { list.style.display = "none"; btn.innerHTML = "&#9654;"; }
        }
        function validateStorageForm() {
            const inputs = document.querySelectorAll('.storage-input');
            const btn = document.getElementById('btnSubmitStorage');
            if (!btn) return;
            if (inputs.length === 0) { btn.disabled = true; btn.style.backgroundColor = '#ccc'; btn.style.cursor = 'not-allowed'; return; }
            let allFilled = true;
            inputs.forEach(input => { if (input.value.trim() === "") { allFilled = false; } });
            if (allFilled) { btn.disabled = false; btn.style.backgroundColor = 'var(--primary)'; btn.style.cursor = 'pointer'; } 
            else { btn.disabled = true; btn.style.backgroundColor = '#ccc'; btn.style.cursor = 'not-allowed'; }
        }
        function togglePass(btn) {
            var parent = btn.parentElement;
            var masked = parent.querySelector('.masked');
            var real = parent.querySelector('.real');
            if (real.style.display === 'none') { real.style.display = 'inline'; masked.style.display = 'none'; btn.innerHTML = 'Hide'; } 
            else { real.style.display = 'none'; masked.style.display = 'inline'; btn.innerHTML = 'Show'; }
        }
        document.addEventListener("DOMContentLoaded", function() {
            const inputs = document.querySelectorAll('.storage-input');
            inputs.forEach(input => { input.addEventListener('input', validateStorageForm); });
            validateStorageForm();
        });
    </script>
</head>
<body>

    <div class="navbar">
        <div class="nav-links">
            <a href="admin_panel.php?view=dashboard" class="<?php echo ($current_view=='dashboard')?'active':''; ?>">View Complaints</a>
            <a href="admin_panel.php?view=config" class="<?php echo ($current_view=='config')?'active':''; ?>">Manage Complaint Page Options</a>
            <a href="admin_panel.php?view=storage_unit" class="<?php echo ($current_view=='storage_unit' || $current_view=='storage_management' || $current_view=='storage_dashboard' || $current_view=='storage_form')?'active':''; ?>">Storage Hub</a>
            <a href="admin_panel.php?view=create_admin" class="<?php echo ($current_view=='create_admin')?'active':''; ?>">Manage Admins</a>
        </div>
        <div class="nav-links">
            <a href="admin_panel.php?action=logout" class="logout-btn">Logout</a>
        </div>
    </div>

    <div class="container">
    
        <?php if($sys_msg != ""): ?>
            <div style="text-align:center; margin-bottom:20px; padding:12px; border-radius:6px; 
                        background-color: <?php echo ($sys_msg_color=='red')?'#fce4ec':'#e8f5e9'; ?>; 
                        color: <?php echo ($sys_msg_color=='red')?'#c62828':'#2e7d32'; ?>; border:1px solid transparent;">
                <strong><?php echo $sys_msg; ?></strong>
            </div>
        <?php endif; ?>

        <?php if ($current_view == 'dashboard'): ?>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h1><strong>Admin Dashboard</strong></h1>
                <h3 style="color:#777; font-weight:400;">Welcome, <?php echo $_SESSION['admin_user']; ?>!</h3>
            </div>
            <?php
            $sections = [];
            $res = mysqli_query($conn, "SELECT * FROM dynamic_sections ORDER BY display_order ASC");
            while ($row = mysqli_fetch_assoc($res)) { $sections[] = $row; }
            $sql = "SELECT * FROM complaints ORDER BY id DESC";
            $result = mysqli_query($conn, $sql);
            $num_complaints = mysqli_num_rows($result);
            ?>
            <?php if ($num_complaints > 0): ?>
                <table>
                    <tr>
                        <?php foreach ($sections as $sec) { echo "<th>" . $sec['section_title'] . "</th>"; } ?>
                        <th>Other Details</th><th class="col-status">Status</th><th>Date</th><th>Action</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <?php foreach ($sections as $sec) {
                                $col = $sec['column_name'];
                                $val = (!empty($row[$col])) ? $row[$col] : "-";
                                echo "<td>" . $val . "</td>";
                            } ?>
                            <td><?php echo (!empty($row['other_details'])) ? $row['other_details'] : "-"; ?></td>
                            <td class='col-status'>
                                <?php if($row['status'] == 'Pending'): ?>
                                    <span class='status-pending'>Pending</span>
                                    <a href='admin_panel.php?mark_complete=<?php echo $row['id']; ?>' class='btn-complete' title='Mark as Completed'>✅</a>
                                <?php else: ?>
                                    <span class='status-completed'>Completed</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $row['created_at']; ?></td>
                            <td><a href='admin_panel.php?delete_complaint=<?php echo $row['id']; ?>' class='btn-delete' onclick='return confirm("Are you sure?");'>Delete</a></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <div style="text-align:center; padding:40px; border:1px solid #eee; border-radius:8px; background:#fff; color:#777;">
                    <h3>No Complaints Found</h3>
                </div>
            <?php endif; ?>

        <?php elseif ($current_view == 'config'): ?>
            <h1><strong>Manage Complaint Page Options</strong></h1>
            <hr>
            <div class="create-section-box">
                <h3 style="margin-top:0;">Need a new category?</h3>
                <button class="btn-toggle" onclick="toggle('new_sec_form_config')">+ Create New Section</button>
                <div id="new_sec_form_config" style="display:none; margin-top:15px; width: 70%; margin-left: auto; margin-right: auto;">
                    <form method="POST">
                        <input type="hidden" name="return_view" value="config">
                        <input type="text" name="section_title" placeholder="Name (e.g. Room Number)" required>
                        <select name="input_type">
                            <option value="dropdown">Dropdown</option>
                            <option value="checkbox">Checkbox</option>
                        </select>
                        <label style="justify-content:center;"><input type="checkbox" name="is_unique" value="1"> Allow Duplicate Values</label>
                        <div class="button-group">
                            <input type="submit" name="create_new_section" value="Create" class="btn-add">
                            <button type="button" class="btn-cancel" onclick="toggle('new_sec_form_config')">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="priority-box">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                    <h3 style="margin:0;">Section Priority / Reorder</h3>
                    <button class="btn-arrow" onclick="toggleList('prio_list_config', this)">&#9654;</button>
                </div>
                <div id="prio_list_config" style="display:none;">
                    <?php
                    $all_secs = [];
                    $res = mysqli_query($conn, "SELECT * FROM dynamic_sections ORDER BY display_order ASC");
                    while($row = mysqli_fetch_assoc($res)) { $all_secs[] = $row; }
                    foreach($all_secs as $index => $sec) {
                        $is_first = ($index === 0); $is_last = ($index === (count($all_secs) - 1));
                        $up_class = $is_first ? "btn-disabled" : "btn-up";
                        $down_class = $is_last ? "btn-disabled" : "btn-down";
                        echo "<div class='priority-row'><strong>" . $sec['section_title'] . "</strong><div>";
                        echo "<a href='admin_panel.php?move_section=".$sec['id']."&dir=up&return_view=$current_view' class='$up_class'>&uarr;</a>";
                        echo "<a href='admin_panel.php?move_section=".$sec['id']."&dir=down&return_view=$current_view' class='$down_class'>&darr;</a>";
                        echo "</div></div>";
                    }
                    ?>
                </div>
            </div>
            <div class="row">
                <?php foreach($all_secs as $sec) {
                    $title = $sec['section_title']; $col = $sec['column_name']; 
                    $sid = $sec['id']; $table = $col; 
                    $act_id="act_$sid"; $ren_id="ren_$sid"; $add_id="add_$sid"; $list_id="list_$sid"; 
                ?>
                    <div class="column">
                        <div class="header-row">
                            <div style="display:flex; align-items:center;">
                                <h3><?php echo $title; ?></h3>
                                <button class="btn-arrow" onclick="toggleList('<?php echo $list_id; ?>', this)">&#9654;</button>
                            </div>
                            <button class="btn-edit-trigger" onclick="toggleFlex('<?php echo $act_id; ?>')">Edit Options</button>
                        </div>
                        <div id="<?php echo $act_id; ?>" class="action-bar" style="display:none;">
                            <button class="btn-toggle" style="background-color:#ff9800;" onclick="toggle('<?php echo $ren_id; ?>')">Edit Label</button>
                            <div id="<?php echo $ren_id; ?>" class="rename-box">
                                <form method="POST">
                                    <input type="hidden" name="return_view" value="config">
                                    <input type="hidden" name="target_id" value="<?php echo $sid; ?>">
                                    <input type="text" name="new_section_name" value="<?php echo $title; ?>" required>
                                    <div class="button-group">
                                        <input type="submit" name="rename_section" value="Save Name" class="btn-add">
                                    </div>
                                </form>
                            </div>
                            <div class="remove-wrapper">
                                <a href="admin_panel.php?remove_section=<?php echo $sid; ?>&return_view=config" id="rem_<?php echo $sid; ?>" class="btn-action-remove disabled" onclick="return confirm('Delete entire section?');">Remove Section</a>
                                <button id="saf_<?php echo $sid; ?>" class="btn-safety-toggle" onclick="toggleSafety('rem_<?php echo $sid; ?>', this.id)">ENABLE</button>
                            </div>
                            <button class="btn-toggle" onclick="toggle('<?php echo $add_id; ?>')">+ Add New Item</button>
                        </div>
                        <div id="<?php echo $add_id; ?>" style="display:none; margin-top:10px; background:#f9f9f9; padding:15px; border-radius:8px; border:1px solid #eee;">
                            <form method="POST">
                                <input type="hidden" name="return_view" value="config">
                                <input type="hidden" name="target_col" value="<?php echo $col; ?>">
                                <input type="text" name="new_val" placeholder="Name or Range (e.g. PC1-5)" required>
                                <div class="button-group">
                                    <input type="submit" name="add_option" value="Add" class="btn-add">
                                </div>
                            </form>
                        </div>
                        <ul id="<?php echo $list_id; ?>" style="display:none;">
                            <?php
                            if(mysqli_query($conn, "SHOW TABLES LIKE '$table'")) {
                                $items = [];
                                $res = mysqli_query($conn, "SELECT * FROM `$table`");
                                while ($row = mysqli_fetch_assoc($res)) { $items[] = $row; }
                                usort($items, function($a, $b) { return strnatcasecmp($a['name'], $b['name']); });
                                foreach ($items as $opt) {
                                    echo "<li><span>" . $opt['name'] . "</span><a href='admin_panel.php?del_opt_id=".$opt['id']."&target=".$col."&return_view=config' class='btn-remove'>Remove</a></li>";
                                }
                            }
                            ?>
                        </ul>
                    </div>
                <?php } ?>
            </div>

        <?php elseif ($current_view == 'storage_unit'): ?>
            <div class="admin-card">
                <h2 style="color:var(--primary); margin-top:0;">Storage Hub</h2>
                <p style="color:#666; margin-bottom:20px; font-size:14px;">Select an action:</p>
                <a href="admin_panel.php?view=storage_form" class="btn-block">Storage Unit Form</a>
                <a href="admin_panel.php?view=storage_management" class="btn-block secondary">Form Management</a>
                <a href="admin_panel.php?view=storage_dashboard" class="btn-block">View Storage Data</a>
            </div>

        <?php elseif ($current_view == 'storage_form'): ?>
            <div class="create-section-box" style="max-width:600px; margin:0 auto; text-align:left;">
                <h2 style="margin-top:0; color:var(--primary); text-align:center;">Add Storage Item</h2>
                <hr>
                <form method="POST" id="storageForm">
                    <?php
                    $res = mysqli_query($conn, "SELECT * FROM storage_sections ORDER BY display_order ASC");
                    $has_sections = (mysqli_num_rows($res) > 0);
                    if ($has_sections) {
                        while ($sec = mysqli_fetch_assoc($res)) {
                            $col = $sec['column_name'];
                            $type = $sec['input_type'];
                            echo "<label style='display:block; font-weight:bold; margin-bottom:5px; justify-content:left;'>".$sec['section_title']."</label>";
                            if ($type == 'numeric') { 
                                echo "<input type='number' name='$col' class='storage-input' required>"; 
                            } elseif ($type == 'date') { 
                                // GOOGLE FORM STYLE DATE INPUT
                                echo "<div class='google-date-wrapper'>";
                                echo "<input type='date' name='$col' class='google-date-input' required>"; 
                                echo "</div>";
                            } else { 
                                echo "<input type='text' name='$col' class='storage-input' required>"; 
                            }
                        }
                    } else { echo "<p style='text-align:center; color:#e74c3c;'>No input sections defined. Go to Form Management to add fields.</p>"; }
                    ?>
                    <div style="text-align:center; margin-top:20px;">
                        <input type="submit" id="btnSubmitStorage" name="submit_storage_form" value="Save Log Entry" class="btn-add" style="width:100%; padding:12px;" disabled>
                    </div>
                </form>
                <p style="text-align:center; margin-top:15px;">
                    <a href="admin_panel.php?view=storage_unit" class="btn-outline">&larr; Back to Hub</a>
                </p>
            </div>

        <?php elseif ($current_view == 'storage_management'): ?>
            <h1><strong>Storage Management</strong></h1>
            <p><a href="admin_panel.php?view=storage_unit" class="btn-outline">&larr; Back to Hub</a></p>
            <hr>
            <div class="create-section-box">
                <h3 style="margin-top:0;">Need a new input field?</h3>
                <button class="btn-toggle" onclick="toggle('new_sec_form_st')">+ Create New Section</button>
                <div id="new_sec_form_st" style="display:none; margin-top:15px; width: 70%; margin-left: auto; margin-right: auto;">
                    <form method="POST">
                        <input type="hidden" name="return_view" value="storage_management">
                        <input type="text" name="section_title" placeholder="Name (e.g. Quantity)" required>
                        <select name="input_type">
                            <option value="alphanumeric">Alphanumeric (Text)</option>
                            <option value="numeric">Numeric Only</option>
                            <option value="date">Date</option>
                        </select>
                        <div class="button-group">
                            <input type="submit" name="create_new_section" value="Create Field" class="btn-add">
                            <button type="button" class="btn-cancel" onclick="toggle('new_sec_form_st')">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="priority-box">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                    <h3 style="margin:0;">Section Priority / Reorder</h3>
                    <button class="btn-arrow" onclick="toggleList('prio_list_storage', this)">&#9654;</button>
                </div>
                <div id="prio_list_storage" style="display:none;">
                    <?php 
                    $all_secs = [];
                    $res = mysqli_query($conn, "SELECT * FROM storage_sections ORDER BY display_order ASC");
                    while($row = mysqli_fetch_assoc($res)) { $all_secs[] = $row; }
                    foreach($all_secs as $sec) {
                        $title = $sec['section_title']; $sid = $sec['id'];
                        $is_first = ($sec === reset($all_secs));
                        $is_last = ($sec === end($all_secs));
                        $up_class = $is_first ? "btn-disabled" : "btn-up";
                        $down_class = $is_last ? "btn-disabled" : "btn-down";
                        echo "<div class='priority-row'><strong>" . $sec['section_title'] . "</strong><div>";
                        echo "<a href='admin_panel.php?move_section=".$sec['id']."&dir=up&return_view=storage_management' class='$up_class'>&uarr;</a>";
                        echo "<a href='admin_panel.php?move_section=".$sec['id']."&dir=down&return_view=storage_management' class='$down_class'>&darr;</a>";
                        echo "</div></div>";
                    }
                    ?>
                </div>
            </div>
            <div class="row">
                <?php foreach($all_secs as $sec) {
                    $title = $sec['section_title']; $sid = $sec['id'];
                    $act_id="act_st_$sid"; $ren_id="ren_st_$sid"; 
                ?>
                    <div class="column">
                        <div class="header-row">
                            <h3><?php echo $title; ?> <span style="font-size:12px; color:#999;">(<?php echo ucfirst($sec['input_type']); ?>)</span></h3>
                            <button class="btn-edit-trigger" onclick="toggleFlex('<?php echo $act_id; ?>')">Edit</button>
                        </div>
                        <div id="<?php echo $act_id; ?>" class="action-bar" style="display:none;">
                            <button class="btn-toggle" style="background-color:#ff9800;" onclick="toggle('<?php echo $ren_id; ?>')">Edit Label</button>
                            <div id="<?php echo $ren_id; ?>" class="rename-box">
                                <form method="POST">
                                    <input type="hidden" name="return_view" value="storage_management">
                                    <input type="hidden" name="target_id" value="<?php echo $sid; ?>">
                                    <input type="text" name="new_section_name" value="<?php echo $title; ?>" required>
                                    <div class="button-group">
                                        <input type="submit" name="rename_section" value="Save Name" class="btn-add">
                                    </div>
                                </form>
                            </div>
                            <div class="remove-wrapper">
                                <a href="admin_panel.php?remove_section=<?php echo $sid; ?>&return_view=storage_management" id="rem_st_<?php echo $sid; ?>" class="btn-action-remove disabled" onclick="return confirm('Remove this field? Data in this column will be lost.');">Remove Field</a>
                                <button id="saf_st_<?php echo $sid; ?>" class="btn-safety-toggle" onclick="toggleSafety('rem_st_<?php echo $sid; ?>', this.id)">ENABLE</button>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

        <?php elseif ($current_view == 'storage_dashboard'): ?>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h1><strong>Storage Logs</strong></h1>
                <p><a href="admin_panel.php?view=storage_unit" class="btn-outline">&larr; Back to Hub</a></p>
            </div>
            <?php
            $sections = [];
            $res = mysqli_query($conn, "SELECT * FROM storage_sections ORDER BY display_order ASC");
            while ($row = mysqli_fetch_assoc($res)) { $sections[] = $row; }
            $sql = "SELECT * FROM storage_unit ORDER BY created_at DESC";
            $result = mysqli_query($conn, $sql);
            $num_records = mysqli_num_rows($result);
            ?>
            <?php if ($num_records > 0): ?>
                <table>
                    <tr>
                        <?php foreach ($sections as $sec) { echo "<th>" . $sec['section_title'] . "</th>"; } ?>
                        <th>Date Logged</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <?php foreach ($sections as $sec) {
                                $col = $sec['column_name'];
                                $val = (!empty($row[$col])) ? $row[$col] : "-";
                                echo "<td>" . $val . "</td>";
                            } ?>
                            <td><?php echo $row['created_at']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <div style="text-align:center; padding:40px; border:1px solid #eee; border-radius:8px; background:#fff; color:#777;">
                    <h3>No Records Found</h3>
                </div>
            <?php endif; ?>

        <?php elseif ($current_view == 'change_password'): 
            if (isset($_SESSION['edit_admin_id'])) {
                $id = intval($_SESSION['edit_admin_id']);
                $admin_query = mysqli_query($conn, "SELECT username FROM admins WHERE id=$id");
                $admin_data = mysqli_fetch_assoc($admin_query); 
            } else {
                echo "<script>location.href='admin_panel.php?view=create_admin&sub_view=list';</script>"; exit();
            }
        ?>
            <div class="admin-card">
                <h2 style="color:var(--primary);">Change Password</h2>
                <p style="color:#666;">For User: <strong><?php echo $admin_data['username']; ?></strong></p>
                <form method="POST">
                    <input type="password" name="new_password" placeholder="Enter New Password" required>
                    <input type="submit" name="btn_update_password" value="Update Password" class="btn-block" style="margin-top:15px;">
                </form>
                <p style="text-align:center; margin-top:15px;">
                    <a href="admin_panel.php?view=create_admin&sub_view=list" class="btn-outline">&larr; Cancel</a>
                </p>
            </div>

        <?php elseif ($current_view == 'create_admin'): ?>
            <?php $sub_view = $_GET['sub_view'] ?? 'menu'; if ($sub_view == 'menu'): ?>
                <div class="admin-card">
                    <h2 style="color:var(--primary); margin-top:0;">Manage Admins</h2>
                    <p style="color:#666; margin-bottom:20px; font-size:14px;">Select an action:</p>
                    <a href="admin_panel.php?view=create_admin&sub_view=list" class="btn-block">View Admins</a>
                    <a href="admin_panel.php?view=create_admin&sub_view=create" class="btn-block secondary">Create New Admin</a>
                </div>
            <?php elseif ($sub_view == 'list'): ?>
                <h1><strong>Existing Admins</strong></h1><hr>
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
                        
                        // SECURE EDIT BUTTON (FORM POST)
                        echo "<form method='POST' style='display:inline;'>";
                        echo "<input type='hidden' name='target_admin_id' value='".$row['id']."'>";
                        echo "<button type='submit' name='init_change_pass' class='btn-toggle' style='font-size:11px; padding:5px 10px; text-decoration:none; border:none; cursor:pointer;'>Change Password</button>";
                        echo "</form> ";
                        
                        if ($is_self) { echo "<span class='btn-delete disabled' style='opacity:0.5; cursor:not-allowed; font-size:11px; padding:5px 10px;'>Delete</span>"; } 
                        else { echo "<a href='admin_panel.php?delete_admin=".$row['id']."' class='btn-delete' style='font-size:11px; padding:5px 10px; text-decoration:none;' onclick='return confirm(\"Are you sure?\");'>Delete</a>"; }
                        
                        echo "</td></tr>";
                    }
                    ?>
                </table>
                <p style="text-align:center; margin-top:20px;">
                    <a href="admin_panel.php?view=create_admin&sub_view=menu" class="btn-outline">&larr; Back to Menu</a>
                </p>
            <?php elseif ($sub_view == 'create'): ?>
                <div class="admin-card">
                    <h2 style="color:var(--primary);">Create New Admin</h2>
                    <form method="POST">
                        <input type="text" name="new_username" placeholder="New Username" required>
                        <input type="password" name="new_password" placeholder="New Password" required>
                        <input type="submit" name="btn_create_admin" value="Create Admin" class="btn-block" style="margin-top:15px;">
                    </form>
                    <p style="text-align:center; margin-top:15px;">
                        <a href="admin_panel.php?view=create_admin&sub_view=menu" class="btn-outline">&larr; Back to Menu</a>
                    </p>
                </div>
            <?php endif; ?>

        <?php endif; ?>

    </div>
</body>
</html>