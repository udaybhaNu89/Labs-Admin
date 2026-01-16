<?php
session_start();
include 'db.php';

// =================================================================
// 1. AUTHENTICATION & LOGOUT LOGIC
// =================================================================

// Handle Logout
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: admin_panel.php"); exit();
}

// Handle Login POST
$login_error = "";
if (isset($_POST['btn_login'])) {
    $u = $_POST['username']; $p = $_POST['password'];
    $sql = "SELECT * FROM admins WHERE username = '$u' AND password = '$p'";
    if (mysqli_num_rows(mysqli_query($conn, $sql)) === 1) {
        $_SESSION['admin_user'] = $u; 
        header("Location: admin_panel.php"); exit();
    } else { $login_error = "Invalid Username or Password"; }
}

// IF NOT LOGGED IN -> RENDER LOGIN PAGE AND EXIT
if (!isset($_SESSION['admin_user'])) {
?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Admin Login</title>
        <style>
            body { background-color: #f4f6f9; font-family: sans-serif; }
            /* Centered Login Box Style */
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
    exit(); // Stop script execution here so dashboard doesn't load
}

// =================================================================
// 2. ADMIN ACTIONS (HANDLE POST/GET BEFORE HTML)
// =================================================================

$current_view = $_GET['view'] ?? 'dashboard';

// --- DASHBOARD ACTIONS ---
if (isset($_GET['delete_complaint'])) {
    $id = $_GET['delete_complaint'];
    mysqli_query($conn, "DELETE FROM complaints WHERE id = $id");
    header("Location: admin_panel.php?view=dashboard"); exit();
}

// --- CONFIG ACTIONS ---
// 1. Reorder
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
    $title = $_POST['section_title']; $type = $_POST['input_type']; 
    $col = "dyn_" . strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $title)) . "_" . rand(100,999); 
    $tbl = "opts_" . $col;
    $max = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MAX(display_order) as m FROM dynamic_sections")); $next = $max['m'] + 1;
    mysqli_query($conn, "INSERT INTO dynamic_sections (section_title, column_name, input_type, display_order) VALUES ('$title', '$col', '$type', $next)");
    mysqli_query($conn, "CREATE TABLE $tbl (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100))");
    mysqli_query($conn, "ALTER TABLE complaints ADD COLUMN $col VARCHAR(255)");
    header("Location: admin_panel.php?view=config"); exit();
}
// 3. Rename
if (isset($_POST['rename_section'])) {
    $id = $_POST['target_id']; $name = $_POST['new_section_name'];
    mysqli_query($conn, "UPDATE dynamic_sections SET section_title = '$name' WHERE id = $id");
    header("Location: admin_panel.php?view=config"); exit();
}
// 4. Remove Section
if (isset($_GET['remove_section'])) {
    $id = $_GET['remove_section'];
    $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM dynamic_sections WHERE id = $id"));
    if ($data) {
        $col = $data['column_name']; $tbl = "opts_" . $col;
        mysqli_query($conn, "DROP TABLE IF EXISTS $tbl"); 
        mysqli_query($conn, "ALTER TABLE complaints DROP COLUMN $col"); 
        mysqli_query($conn, "DELETE FROM dynamic_sections WHERE id = $id"); 
    }
    header("Location: admin_panel.php?view=config"); exit();
}
// 5. Add/Remove Options
if (isset($_POST['add_option'])) {
    $tbl = "opts_" . $_POST['target_col']; $val = $_POST['new_val'];
    mysqli_query($conn, "INSERT INTO $tbl (name) VALUES ('$val')");
}
if (isset($_GET['del_opt_id'])) {
    $tbl = "opts_" . $_GET['target']; $id = $_GET['del_opt_id'];
    mysqli_query($conn, "DELETE FROM $tbl WHERE id=$id");
    header("Location: admin_panel.php?view=config"); exit();
}

// --- CREATE ADMIN ACTIONS ---
$create_msg = ""; $create_msg_color = "green";
if (isset($_POST['btn_create_admin'])) {
    $u = $_POST['new_username']; $p = $_POST['new_password'];
    if(!empty($u) && !empty($p)){
        $check = mysqli_query($conn, "SELECT * FROM admins WHERE username = '$u'");
        if(mysqli_num_rows($check) > 0) { 
            $create_msg = "Username already taken!"; $create_msg_color = "red"; 
        } else {
            mysqli_query($conn, "INSERT INTO admins (username, password) VALUES ('$u', '$p')");
            $create_msg = "New Admin Created Successfully!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <style>
        /* GLOBAL STYLES */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f4f6f9; }
        
        /* NAVIGATION BAR */
        .navbar { background-color: #34495e; overflow: hidden; padding: 0 20px; display: flex; align-items: center; justify-content: space-between; height: 60px; }
        .nav-links a { float: left; display: block; color: white; text-align: center; padding: 20px 16px; text-decoration: none; font-size: 14px; font-weight: bold; }
        .nav-links a:hover, .nav-links a.active { background-color: #2c3e50; border-bottom: 3px solid #3498db; }
        .logout-btn { color: #e74c3c !important; border: 1px solid #e74c3c; border-radius: 4px; padding: 8px 15px !important; line-height: normal; }
        .logout-btn:hover { background: #e74c3c; color: white !important; }

        /* CONTAINER */
        .container { padding: 20px; max-width: 1200px; margin: 0 auto; }
        
        /* --- DASHBOARD STYLES (Centered Table) --- */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: center; vertical-align: middle; }
        th { background-color: #27ae60; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .btn-delete { background-color: #e74c3c; color: white; text-decoration: none; padding: 6px 12px; border-radius: 4px; font-size: 13px; font-weight: bold; }
        .btn-delete:hover { background-color: #c0392b; }

        /* --- CONFIG STYLES (Classic UI + Priority Box) --- */
        /* Priority Box (Modern Yellow) */
        .priority-box { background: #fff8e1; border: 1px solid #ffe082; padding: 15px; margin: 20px auto; width: 95%; border-radius: 8px; }
        .priority-row { display: flex; justify-content: space-between; align-items: center; background: white; padding: 10px 15px; margin-bottom: 5px; border: 1px solid #eee; border-radius: 4px; }
        .btn-up, .btn-down { background-color: #3498db; color: white; padding: 5px 12px; border-radius: 4px; font-weight: bold; margin-left: 2px; text-decoration: none; }
        .btn-disabled { background-color: #bdc3c7; color: #fff; padding: 5px 12px; border-radius: 4px; cursor: default; pointer-events: none; text-decoration: none; }

        /* Classic Fieldset Style */
        fieldset.classic-section { border: 2px groove #ddd !important; margin: 0 auto 25px auto !important; padding: 15px !important; width: 95% !important; background-color: #fcfcfc; }
        legend { font-weight: bold; font-size: 1.1em; padding: 0 10px; color: #444; }

        /* Classic Controls */
        .classic-controls { background-color: #eaeaea; border: 1px solid #999; padding: 10px; margin-bottom: 15px; display: flex; gap: 10px; align-items: center; }
        .btn-classic { border: 1px solid #555; background-color: #ddd; color: black; padding: 5px 10px; font-size: 12px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-classic:hover { background-color: #ccc; }
        .rename-inline { display: none; padding: 5px; border: 1px dotted #555; background: #fff; margin-left: 10px; }
        
        /* Safety Wrapper */
        .safety-wrapper { display: flex; border: 1px solid #999; margin-left: auto; }
        .btn-classic-remove { background-color: #a00; color: white; border: none; padding: 6px 12px; cursor: pointer; font-size: 11px; text-decoration: none; display: inline-block;}
        .btn-classic-remove.disabled { background-color: #999; cursor: not-allowed; pointer-events: none; }
        .btn-classic-toggle { background-color: #444; color: white; border: none; padding: 6px 10px; cursor: pointer; font-size: 11px; font-weight: bold; }

        /* Classic List */
        .classic-list { border: 1px solid #999; background: #fff; padding: 0; margin: 0; list-style: none; }
        .classic-list li { border-bottom: 1px solid #ccc; padding: 5px 10px; display: flex; justify-content: space-between; }
        .classic-list li:last-child { border-bottom: none; }
        .classic-list li:nth-child(even) { background-color: #f9f9f9; }

        /* Create Section (Classic) */
        .create-classic { border: 1px solid #444; background-color: #eee; padding: 15px; width: 95%; margin: 20px auto; }

        /* --- CREATE ADMIN STYLE (Centered Box) --- */
        .admin-box { width: 300px; margin: 50px auto; padding: 25px; background: white; border: 1px solid #ddd; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .admin-box input[type="text"], .admin-box input[type="password"] { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .btn-toggle { width: 100%; padding: 10px; background-color: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; font-weight: bold; }
        .btn-toggle:hover { background-color: #2980b9; }

    </style>
    <script>
        function toggle(id) { var x=document.getElementById(id); x.style.display=(x.style.display==="none")?"inline-block":"none"; }
        function toggleSafety(remId, togId) {
            var r = document.getElementById(remId); var t = document.getElementById(togId);
            if (t.innerHTML === "ENABLE") { t.innerHTML = "DISABLE"; r.classList.remove("disabled"); r.style.pointerEvents="auto"; r.style.backgroundColor="#d00"; } 
            else { t.innerHTML = "ENABLE"; r.classList.add("disabled"); r.style.pointerEvents="none"; r.style.backgroundColor="#999"; }
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

        <?php if ($current_view == 'dashboard'): ?>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <h1 style="color:#2c3e50;">Admin Dashboard</h1>
                <h3 style="color:#7f8c8d;">Welcome, <?php echo $_SESSION['admin_user']; ?>!</h3>
            </div>
            
            <?php
            // Fetch Headers
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

            <div class="create-classic">
                <h3>Create New Section</h3>
                <form method="POST">
                    Name: <input type="text" name="section_title" required style="width:200px; padding: 5px;">
                    Type: 
                    <select name="input_type" style="width:150px; padding: 5px;">
                        <option value="dropdown">Dropdown</option>
                        <option value="checkbox">Checkbox</option>
                    </select>
                    <input type="submit" name="create_new_section" value="Create" class="btn-classic">
                </form>
            </div>

            <?php foreach($all_secs as $sec) {
                $title = $sec['section_title']; $col = $sec['column_name']; $sid = $sec['id'];
                $table = "opts_" . $col;
                $ren_id="ren_$sid"; $rem_id="rem_$sid"; $saf_id="saf_$sid";
            ?>
                <fieldset class="classic-section">
                    <legend><?php echo $title; ?></legend>
                    
                    <div class="classic-controls">
                        <button class="btn-classic" onclick="toggle('<?php echo $ren_id; ?>')">Edit Label</button>
                        <div id="<?php echo $ren_id; ?>" class="rename-inline">
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="target_id" value="<?php echo $sid; ?>">
                                <input type="text" name="new_section_name" value="<?php echo $title; ?>" size="15">
                                <input type="submit" name="rename_section" value="Save" class="btn-classic">
                            </form>
                        </div>

                        <form method="POST" style="display:flex; gap:5px; margin-left:10px;">
                            <input type="hidden" name="target_col" value="<?php echo $col; ?>">
                            <input type="text" name="new_val" placeholder="New Item Name" required size="20" style="padding: 2px;">
                            <input type="submit" name="add_option" value="+ Add" class="btn-classic">
                        </form>

                        <div class="safety-wrapper">
                            <a href="admin_panel.php?view=config&remove_section=<?php echo $sid; ?>" 
                               id="<?php echo $rem_id; ?>" 
                               class="btn-classic-remove disabled" 
                               onclick="return confirm('Delete entirely?');">
                               REMOVE SECTION
                            </a>
                            <button id="<?php echo $saf_id; ?>" class="btn-classic-toggle" onclick="toggleSafety('<?php echo $rem_id; ?>', '<?php echo $saf_id; ?>')">ENABLE</button>
                        </div>
                    </div>

                    <ul class="classic-list">
                        <?php
                        if(mysqli_query($conn, "SHOW TABLES LIKE '$table'")) {
                            $opts = mysqli_query($conn, "SELECT * FROM $table");
                            if(mysqli_num_rows($opts) > 0) {
                                while ($opt = mysqli_fetch_assoc($opts)) {
                                    echo "<li>";
                                    echo "<span>" . $opt['name'] . "</span>";
                                    echo "<a href='admin_panel.php?view=config&del_opt_id=".$opt['id']."&target=".$col."' style='color:red; font-size:12px; text-decoration:none;'>[Delete]</a>";
                                    echo "</li>";
                                }
                            } else { echo "<li style='color:#777; font-style:italic;'>No items found. Add one above.</li>"; }
                        }
                        ?>
                    </ul>
                </fieldset>
            <?php } ?>

        <?php elseif ($current_view == 'create_admin'): ?>
            <div class="admin-box">
                <h2 style="text-align:center; margin-top:0; color:#333;">Create New Admin</h2>
                <?php if($create_msg != ""): ?>
                    <p style="text-align:center; color:<?php echo $create_msg_color; ?>; font-weight:bold; font-size:14px;">
                        <?php echo $create_msg; ?>
                    </p>
                <?php endif; ?>

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