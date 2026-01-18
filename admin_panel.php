<?php
session_start();
include 'db.php';

// =================================================================
// 0. MESSAGE HANDLING
// =================================================================
$sys_msg = "";
$sys_msg_color = "green";

if (isset($_SESSION['sys_msg'])) {
    $sys_msg = $_SESSION['sys_msg'];
    $sys_msg_color = $_SESSION['sys_msg_color'];
    unset($_SESSION['sys_msg']); 
    unset($_SESSION['sys_msg_color']);
}

// =================================================================
// 1. AUTHENTICATION & LOGOUT
// =================================================================

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: admin_panel.php"); exit();
}

$login_error = "";
if (isset($_POST['btn_login'])) {
    $u = $_POST['username']; $p = $_POST['password'];
    $sql = "SELECT * FROM admins WHERE username = '$u' AND password = '$p'";
    if (mysqli_num_rows(mysqli_query($conn, $sql)) === 1) {
        $_SESSION['admin_user'] = $u; 
        header("Location: admin_panel.php"); exit();
    } else { $login_error = "Invalid Username or Password"; }
}

if (!isset($_SESSION['admin_user'])) {
?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Admin Login</title>
        <style>
            body { background-color: #f4f6f9; font-family: sans-serif; }
            .login-box { width: 300px; margin: 100px auto; padding: 25px; background: white; border: 1px solid #ddd; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
            input[type="text"], input[type="password"] { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
            .btn-toggle { width: 100%; padding: 10px; background-color: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; font-weight: bold; }
            .btn-toggle:hover { background-color: #2980b9; }
            .error { color: red; text-align: center; font-size: 14px; margin-bottom: 10px; }
        </style>
    </head>
    <body>
        <div class="login-box">
            <h2 style="text-align:center; margin-top:0; color:#333;">Admin Login</h2>
            <?php if($login_error) echo "<div class='error'>$login_error</div>"; ?>
            <form method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="submit" name="btn_login" value="Login" class="btn-toggle">
            </form>
            <p style="text-align:center; font-size:13px; margin-top:15px;">
                <a href="index.php" style="color:#666; text-decoration:none;">Back to Home</a>
            </p>
        </div>
    </body>
    </html>
<?php
    exit(); 
}

// =================================================================
// 2. ADMIN ACTIONS
// =================================================================

$current_view = $_GET['view'] ?? 'dashboard';

// --- DASHBOARD ACTIONS ---
if (isset($_GET['delete_complaint'])) {
    $id = $_GET['delete_complaint'];
    mysqli_query($conn, "DELETE FROM complaints WHERE id = $id");
    $_SESSION['sys_msg'] = "Complaint Deleted"; $_SESSION['sys_msg_color'] = "green";
    header("Location: admin_panel.php?view=dashboard"); exit();
}

// --- CONFIG ACTIONS ---

// 1. Move Section
if (isset($_GET['move_section']) && isset($_GET['dir'])) {
    $id = $_GET['move_section']; $dir = $_GET['dir'];
    $curr = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id, display_order FROM dynamic_sections WHERE id = $id"));
    $curr_order = $curr['display_order'];
    if ($dir == 'up') {
        $target = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id, display_order FROM dynamic_sections WHERE display_order < $curr_order ORDER BY display_order DESC LIMIT 1"));
    } else {
        $target = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id, display_order FROM dynamic_sections WHERE display_order > $curr_order ORDER BY display_order ASC LIMIT 1"));
    }
    if ($target) {
        $t_id = $target['id']; $t_order = $target['display_order'];
        mysqli_query($conn, "UPDATE dynamic_sections SET display_order = $t_order WHERE id = $id");
        mysqli_query($conn, "UPDATE dynamic_sections SET display_order = $curr_order WHERE id = $t_id");
    }
    header("Location: admin_panel.php?view=config"); exit();
}

// 2. Create Section
if (isset($_POST['create_new_section'])) {
    $title = trim($_POST['section_title']); 
    $title_safe = mysqli_real_escape_string($conn, $title);
    $type = $_POST['input_type']; 
    
    // Checkbox Checked (1) = Allow Duplicates. 
    // Checkbox Empty (0) = Enforce Unique.
    $is_unique = isset($_POST['is_unique']) ? 1 : 0; 

    // CHECK IF SECTION ALREADY EXISTS
    $check = mysqli_query($conn, "SELECT id FROM dynamic_sections WHERE section_title = '$title_safe'");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['sys_msg'] = "$title already exists";
        $_SESSION['sys_msg_color'] = "red";
    } else {
        // Clean name generation
        $clean_name = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $title));
        $clean_name = trim($clean_name, '_');
        
        $col = "dyn_" . $clean_name; 
        $tbl = $clean_name; 

        $col_check = mysqli_query($conn, "SELECT id FROM dynamic_sections WHERE column_name = '$col'");
        if(mysqli_num_rows($col_check) > 0) {
             $_SESSION['sys_msg'] = "Error: A section with a similar internal name ($col) already exists.";
             $_SESSION['sys_msg_color'] = "red";
             header("Location: admin_panel.php?view=config"); exit();
        }

        $max = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MAX(display_order) as m FROM dynamic_sections")); 
        $next = $max['m'] + 1;

        mysqli_query($conn, "INSERT INTO dynamic_sections (section_title, column_name, input_type, display_order, is_unique) VALUES ('$title_safe', '$col', '$type', $next, $is_unique)");
        
        // BACKTICKS FOR TABLE NAME
        if ($is_unique != 1) {
            mysqli_query($conn, "CREATE TABLE `$tbl` (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100), UNIQUE(name))");
        } else {
            mysqli_query($conn, "CREATE TABLE `$tbl` (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100))");
        }
        
        mysqli_query($conn, "ALTER TABLE complaints ADD COLUMN $col VARCHAR(255)");
        $_SESSION['sys_msg'] = "Section Created Successfully"; 
        $_SESSION['sys_msg_color'] = "green";
    }
    
    header("Location: admin_panel.php?view=config"); exit();
}

// 3. Rename Section
if (isset($_POST['rename_section'])) {
    $id = $_POST['target_id']; 
    $new_name = trim($_POST['new_section_name']);
    $new_name_safe = mysqli_real_escape_string($conn, $new_name);
    
    $old_row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT section_title FROM dynamic_sections WHERE id = $id"));
    $old_name = $old_row['section_title'];

    $check = mysqli_query($conn, "SELECT id FROM dynamic_sections WHERE section_title = '$new_name_safe' AND id != $id");
    
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['sys_msg'] = "$old_name can't be renamed as $new_name, because $new_name already exists";
        $_SESSION['sys_msg_color'] = "red";
    } else {
        mysqli_query($conn, "UPDATE dynamic_sections SET section_title = '$new_name_safe' WHERE id = $id");
        $_SESSION['sys_msg'] = "Section Renamed Successfully";
        $_SESSION['sys_msg_color'] = "green";
    }
    
    header("Location: admin_panel.php?view=config"); exit();
}

// 4. Remove Section
if (isset($_GET['remove_section'])) {
    $id = $_GET['remove_section'];
    $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM dynamic_sections WHERE id = $id"));
    if ($data) {
        $col = $data['column_name']; 
        $tbl = substr($col, 4); 
        
        mysqli_query($conn, "DROP TABLE IF EXISTS `$tbl`"); 
        try {
            mysqli_query($conn, "ALTER TABLE complaints DROP COLUMN $col");
        } catch (Exception $e) { /* Ignore if col missing */ }
        
        mysqli_query($conn, "DELETE FROM dynamic_sections WHERE id = $id"); 
    }
    $_SESSION['sys_msg'] = "Section Removed"; $_SESSION['sys_msg_color'] = "red";
    header("Location: admin_panel.php?view=config"); exit();
}

// 5. Add Option
if (isset($_POST['add_option'])) {
    $target_col = $_POST['target_col'];
    $tbl = substr($target_col, 4); 
    
    $raw_input = trim($_POST['new_val']);
    $items_to_add = [];

    $sec_info = mysqli_fetch_assoc(mysqli_query($conn, "SELECT section_title, is_unique FROM dynamic_sections WHERE column_name = '$target_col'"));
    $section_name = $sec_info['section_title'];
    $needs_unique = ($sec_info['is_unique'] != 1); 

    // Range Logic
    if (preg_match('/^([a-zA-Z0-9\.-]*?)(\d+)-(\d+)$/', $raw_input, $matches)) {
        $prefix = $matches[1];
        $start = (int)$matches[2];
        $end = (int)$matches[3];
        if ($start <= $end) {
            for ($i = $start; $i <= $end; $i++) {
                $items_to_add[] = $prefix . $i;
            }
        }
    } else {
        $items_to_add[] = $raw_input;
    }

    $duplicates_found = [];

    // Check table exists
    $tbl_check = mysqli_query($conn, "SHOW TABLES LIKE '$tbl'");
    if (mysqli_num_rows($tbl_check) == 0) {
        $_SESSION['sys_msg'] = "Error: Storage table missing. Please remove and recreate this section.";
        $_SESSION['sys_msg_color'] = "red";
        header("Location: admin_panel.php?view=config"); exit();
    }

    foreach ($items_to_add as $val) {
        $val_safe = mysqli_real_escape_string($conn, $val);
        
        if ($needs_unique) {
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
        $dup_string = implode(", ", $duplicates_found);
        $_SESSION['sys_msg'] = "$section_name takes unique values only, and the value(s) '$dup_string' is already exists";
        $_SESSION['sys_msg_color'] = "red";
    } else {
        $_SESSION['sys_msg'] = "Items Added Successfully"; 
        $_SESSION['sys_msg_color'] = "green";
    }

    header("Location: admin_panel.php?view=config"); exit(); 
}

// 6. Delete Option
if (isset($_GET['del_opt_id'])) {
    $col = $_GET['target']; 
    $tbl = substr($col, 4); 
    
    $id = $_GET['del_opt_id'];
    mysqli_query($conn, "DELETE FROM `$tbl` WHERE id=$id");
    header("Location: admin_panel.php?view=config"); exit();
}

// --- CREATE ADMIN ACTIONS ---
if (isset($_POST['btn_create_admin'])) {
    $u = $_POST['new_username']; $p = $_POST['new_password'];
    if(!empty($u) && !empty($p)){
        $check = mysqli_query($conn, "SELECT * FROM admins WHERE username = '$u'");
        if(mysqli_num_rows($check) > 0) { 
            $_SESSION['sys_msg'] = "Username taken!"; $_SESSION['sys_msg_color'] = "red";
        } else {
            mysqli_query($conn, "INSERT INTO admins (username, password) VALUES ('$u', '$p')");
            $_SESSION['sys_msg'] = "New Admin Created!"; $_SESSION['sys_msg_color'] = "green";
        }
    }
    header("Location: admin_panel.php?view=create_admin"); exit(); 
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <style>
        /* GLOBAL STYLES */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f4f6f9; }
        
        /* NAVBAR */
        .navbar { background-color: #34495e; overflow: hidden; padding: 0 20px; display: flex; align-items: center; justify-content: space-between; height: 60px; }
        .nav-links a { float: left; display: block; color: white; text-align: center; padding: 20px 16px; text-decoration: none; font-size: 14px; font-weight: bold; }
        .nav-links a:hover, .nav-links a.active { background-color: #2c3e50; border-bottom: 3px solid #3498db; }
        .logout-btn { color: #e74c3c !important; border: 1px solid #e74c3c; border-radius: 4px; padding: 8px 15px !important; line-height: normal; }
        .logout-btn:hover { background: #e74c3c; color: white !important; }

        /* CONTAINER */
        .container { padding: 20px; max-width: 1200px; margin: 0 auto; }
        
        /* DASHBOARD TABLES */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: center; vertical-align: middle; }
        th { background-color: #27ae60; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .btn-delete { background-color: #e74c3c; color: white; text-decoration: none; padding: 6px 12px; border-radius: 4px; font-size: 13px; font-weight: bold; }
        .btn-delete:hover { background-color: #c0392b; }

        /* CONFIG STYLES (Vertical) */
        .column { float: none; width: 95%; margin: 15px auto; padding: 15px; background: white; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; } 
        .row:after { content: ""; display: table; clear: both; } 
        .header-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 2px solid #f2f2f2; padding-bottom: 10px; } 
        .header-row h3 { margin: 0; font-size: 1.1em; color: #333; } 
        
        .btn-toggle { background-color: #008CBA; color: white; border: none; padding: 6px 12px; font-size: 13px; cursor: pointer; border-radius: 3px; } 
        .button-group { display: flex; gap: 5px; margin-top: 5px; } 
        .btn-add { flex: 1; background-color: #28a745; color: white; border: none; padding: 8px; cursor: pointer; border-radius: 3px; } 
        .btn-cancel { flex: 1; background-color: #6c757d; color: white; border: none; padding: 8px; cursor: pointer; border-radius: 3px; } 
        
        ul { padding: 0; margin-top: 10px; list-style-type: none; } 
        li { background: #fff; border-bottom: 1px solid #eee; padding: 8px 5px; display: flex; justify-content: space-between; align-items: center; } 
        .btn-remove { background-color: #ff4d4d; color: white; text-decoration: none; font-size: 11px; padding: 4px 10px; border-radius: 3px; border: 1px solid #cc0000; font-family: sans-serif; } 
        
        .create-section-box { background: #e3f2fd; padding: 20px; border: 2px dashed #2196F3; margin-bottom: 20px; text-align: center; } 
        
        /* Priority & Action Bar */
        .priority-box { background: #fff8e1; border: 1px solid #ffe082; padding: 15px; margin: 20px auto; width: 95%; border-radius: 8px; }
        .priority-row { display: flex; justify-content: space-between; align-items: center; background: white; padding: 10px 15px; margin-bottom: 5px; border: 1px solid #eee; border-radius: 4px; }
        .btn-up, .btn-down { background-color: #3498db; color: white; padding: 5px 12px; border-radius: 4px; font-weight: bold; margin-left: 2px; text-decoration: none; }
        .btn-disabled { background-color: #bdc3c7; color: #fff; padding: 5px 12px; border-radius: 4px; cursor: default; pointer-events: none; text-decoration: none; }
        
        .action-bar { background-color: #f9f9f9; padding: 10px; margin-bottom: 15px; border: 1px solid #eee; display: flex; flex-direction: column; gap: 8px; }
        .rename-box { display:none; background: #fffbe6; padding: 10px; border: 1px solid #e6dbb9; margin-bottom: 5px; }

        .remove-wrapper { display: flex; gap: 5px; }
        .btn-action-remove { flex: 3; background-color: #ff4d4d; color: white; border: 1px solid #cc0000; padding: 8px; font-size: 12px; cursor: pointer; text-align: center; text-decoration: none; border-radius: 3px; }
        .btn-action-remove.disabled { background-color: #e0e0e0; border-color: #ccc; color: #999; pointer-events: none; }
        .btn-safety-toggle { flex: 1; background-color: #555; color: white; border: none; padding: 8px; font-size: 11px; font-weight: bold; cursor: pointer; border-radius: 3px; }
        .btn-edit-trigger { background-color: #777; color: white; padding: 5px 10px; font-size: 11px; border-radius: 3px; cursor: pointer; border: none; }

        /* ARROW BUTTON STYLE */
        .btn-arrow { background:none; border:none; font-size:16px; cursor:pointer; color:#555; transition: transform 0.3s; margin-left: 10px; }
        .btn-arrow:hover { color: #008CBA; }

        /* CREATE ADMIN */
        .admin-box { width: 300px; margin: 50px auto; padding: 25px; background: white; border: 1px solid #ddd; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .admin-box input[type="text"], .admin-box input[type="password"] { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }

    </style>
    <script>
        function toggle(id) { var x=document.getElementById(id); x.style.display=(x.style.display==="none")?"block":"none"; }
        function toggleFlex(id) { var x=document.getElementById(id); x.style.display=(x.style.display==="none")?"flex":"none"; }
        function toggleSafety(remId, togId) {
            var r = document.getElementById(remId); var t = document.getElementById(togId);
            if (t.innerHTML === "ENABLE") { t.innerHTML = "DISABLE"; r.classList.remove("disabled"); r.style.pointerEvents="auto"; r.style.backgroundColor="#d00"; } 
            else { t.innerHTML = "ENABLE"; r.classList.add("disabled"); r.style.pointerEvents="none"; r.style.backgroundColor="#999"; }
        }
        
        // TOGGLE LIST VISIBILITY
        function toggleList(listId, btn) {
            var list = document.getElementById(listId);
            if (list.style.display === "none") {
                list.style.display = "block";
                btn.innerHTML = "&#9660;"; // Down Arrow
            } else {
                list.style.display = "none";
                btn.innerHTML = "&#9654;"; // Right Arrow
            }
        }
    </script>
</head>
<body>

    <div class="navbar">
        <div class="nav-links">
            <a href="admin_panel.php?view=dashboard" class="<?php echo ($current_view=='dashboard')?'active':''; ?>">View Complaints</a>
            <a href="admin_panel.php?view=config" class="<?php echo ($current_view=='config')?'active':''; ?>">Manage Options</a>
            <a href="admin_panel.php?view=create_admin" class="<?php echo ($current_view=='create_admin')?'active':''; ?>">New Admin</a>
        </div>
        <div class="nav-links">
            <a href="admin_panel.php?action=logout" class="logout-btn">Logout</a>
        </div>
    </div>

    <div class="container">
    
        <?php if($sys_msg != ""): ?>
            <div style="text-align:center; margin-bottom:20px; padding:10px; border-radius:4px; 
                        background-color: <?php echo ($sys_msg_color=='red')?'#f8d7da':'#d4edda'; ?>; 
                        color: <?php echo ($sys_msg_color=='red')?'#721c24':'#155724'; ?>; border:1px solid transparent;">
                <strong><?php echo $sys_msg; ?></strong>
            </div>
        <?php endif; ?>

        <?php if ($current_view == 'dashboard'): ?>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <h1 style="color:#2c3e50;">Admin Dashboard</h1>
                <h3 style="color:#7f8c8d;">Welcome, <?php echo $_SESSION['admin_user']; ?>!</h3>
            </div>
            
            <?php
            $sections = [];
            $res = mysqli_query($conn, "SELECT * FROM dynamic_sections ORDER BY display_order ASC");
            while ($row = mysqli_fetch_assoc($res)) { $sections[] = $row; }
            ?>

            <table>
                <tr>
                    <?php foreach ($sections as $sec) { echo "<th>" . $sec['section_title'] . "</th>"; } ?>
                    <th>Other Details</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
                <?php
                $sql = "SELECT * FROM complaints ORDER BY id DESC";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        foreach ($sections as $sec) {
                            $col = $sec['column_name'];
                            $val = (!empty($row[$col])) ? $row[$col] : "-";
                            echo "<td>" . $val . "</td>";
                        }
                        $other = (!empty($row['other_details'])) ? $row['other_details'] : "-";
                        echo "<td>" . $other . "</td>";
                        echo "<td>" . $row['status'] . "</td>";
                        echo "<td>" . $row['created_at'] . "</td>";
                        echo "<td><a href='admin_panel.php?delete_complaint=" . $row['id'] . "' class='btn-delete' onclick='return confirm(\"Are you sure?\");'>Delete</a></td>";
                        echo "</tr>";
                    }
                } else { 
                    $colspan = count($sections) + 4; 
                    echo "<tr><td colspan='$colspan' style='color:#777;'>No complaints found.</td></tr>"; 
                }
                ?>
            </table>

        <?php elseif ($current_view == 'config'): ?>
            <h1 style="color:#333;">Manage Complaint Page Options</h1>
            <hr>

            <div class="priority-box">
                <h3>Section Priority / Reorder</h3>
                <?php
                $all_secs = [];
                $res = mysqli_query($conn, "SELECT * FROM dynamic_sections ORDER BY display_order ASC");
                while($row = mysqli_fetch_assoc($res)) { $all_secs[] = $row; }
                $total = count($all_secs);
                
                foreach($all_secs as $index => $sec) {
                    $is_first = ($index === 0);
                    $is_last = ($index === ($total - 1));
                    $up_class = $is_first ? "btn-disabled" : "btn-up";
                    $down_class = $is_last ? "btn-disabled" : "btn-down";
                    
                    echo "<div class='priority-row'><strong>" . $sec['section_title'] . "</strong><div>";
                    echo "<a href='admin_panel.php?view=config&move_section=".$sec['id']."&dir=up' class='$up_class'>&uarr;</a>";
                    echo "<a href='admin_panel.php?view=config&move_section=".$sec['id']."&dir=down' class='$down_class'>&darr;</a>";
                    echo "</div></div>";
                }
                ?>
            </div>

            <div class="create-section-box">
                <h3>Need a new category?</h3>
                <button class="btn-toggle" style="width:auto; padding:8px 20px;" onclick="toggle('new_sec_form')">+ Create New Section</button>
                <div id="new_sec_form" style="display:none; margin-top:15px; width: 60%; margin: 15px auto;">
                    <form method="POST">
                        <input type="text" name="section_title" placeholder="Name (e.g. Room Number)" required style="padding:5px; width:70%;">
                        <select name="input_type" style="padding:5px;">
                            <option value="dropdown">Dropdown</option>
                            <option value="checkbox">Checkbox</option>
                        </select>
                        <br><br>
                        <label style="font-size:14px; color:#333;">
                            <input type="checkbox" name="is_unique" value="1"> Allow Duplicate Values
                        </label>
                        
                        <div class="button-group">
                            <input type="submit" name="create_new_section" value="Create" class="btn-add">
                            <button type="button" class="btn-cancel" onclick="toggle('new_sec_form')">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <?php foreach($all_secs as $sec) {
                    $title = $sec['section_title']; $col = $sec['column_name']; $sid = $sec['id'];
                    $table = substr($col, 4); // Deriving table name
                    $act_id="act_$sid"; $ren_id="ren_$sid"; $add_id="add_$sid"; $rem_id="rem_$sid"; $saf_id="saf_$sid";
                    $list_id="list_$sid"; 
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
                                    <input type="hidden" name="target_id" value="<?php echo $sid; ?>">
                                    <input type="text" name="new_section_name" value="<?php echo $title; ?>" required style="padding:5px; width:100%; box-sizing:border-box;">
                                    <div class="button-group">
                                        <input type="submit" name="rename_section" value="Save Name" class="btn-add">
                                        <button type="button" class="btn-cancel" onclick="toggle('<?php echo $ren_id; ?>')">Cancel</button>
                                    </div>
                                </form>
                            </div>

                            <div class="remove-wrapper">
                                <a href="admin_panel.php?view=config&remove_section=<?php echo $sid; ?>" 
                                   id="<?php echo $rem_id; ?>" 
                                   class="btn-action-remove disabled" 
                                   onclick="return confirm('WARNING: Delete entire section?');">
                                   Remove Section
                                </a>
                                <button id="<?php echo $saf_id; ?>" class="btn-safety-toggle" onclick="toggleSafety('<?php echo $rem_id; ?>', '<?php echo $saf_id; ?>')">ENABLE</button>
                            </div>
                            <button class="btn-toggle" onclick="toggle('<?php echo $add_id; ?>')">+ Add New Item</button>
                        </div>

                        <div id="<?php echo $add_id; ?>" style="display:none; margin-top:10px; background:#f9f9f9; padding:10px; border:1px solid #eee;">
                            <form method="POST">
                                <input type="hidden" name="target_col" value="<?php echo $col; ?>">
                                <input type="text" name="new_val" placeholder="Name or Range (e.g. PC1-5)" required style="width:100%; padding:5px; box-sizing:border-box;">
                                <div class="button-group">
                                    <input type="submit" name="add_option" value="Add" class="btn-add">
                                    <button type="button" class="btn-cancel" onclick="toggle('<?php echo $add_id; ?>')">Cancel</button>
                                </div>
                            </form>
                        </div>

                        <ul id="<?php echo $list_id; ?>" style="display:none;">
                            <?php
                            $check_table = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
                            if(mysqli_num_rows($check_table) > 0) {
                                // Natural Sorting Fix
                                $items = [];
                                $res = mysqli_query($conn, "SELECT * FROM `$table`");
                                while ($row = mysqli_fetch_assoc($res)) {
                                    $items[] = $row;
                                }
                                usort($items, function($a, $b) {
                                    return strnatcasecmp($a['name'], $b['name']);
                                });

                                foreach ($items as $opt) {
                                    echo "<li>";
                                    echo "<span>" . $opt['name'] . "</span>";
                                    echo "<a href='admin_panel.php?view=config&del_opt_id=".$opt['id']."&target=".$col."' class='btn-remove'>Remove</a>";
                                    echo "</li>";
                                }
                            } else {
                                echo "<li style='color:red;'>Error: Storage table '$table' not found.</li>";
                            }
                            ?>
                        </ul>
                    </div>
                <?php } ?>
            </div>

        <?php elseif ($current_view == 'create_admin'): ?>
            <div class="admin-box">
                <h2 style="text-align:center; margin-top:0; color:#333;">Create New Admin</h2>
                <form method="POST">
                    <input type="text" name="new_username" placeholder="New Username" required>
                    <input type="password" name="new_password" placeholder="New Password" required>
                    <input type="submit" name="btn_create_admin" value="Create Admin" class="btn-toggle">
                </form>
                <p style="text-align:center; margin-top:15px; font-size:14px;">
                    <a href="admin_panel.php?view=dashboard" style="text-decoration:none; color:#555;">&larr; Back to Dashboard</a>
                </p>
            </div>
        <?php endif; ?>

    </div>
</body>
</html>