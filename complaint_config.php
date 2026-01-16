<?php
session_start();
include 'db.php';
if (!isset($_SESSION['admin_user'])) { header("Location: admin_login.php"); exit(); }

// 1. REORDER LOGIC
if (isset($_GET['move_section']) && isset($_GET['dir'])) {
    $id = $_GET['move_section'];
    $dir = $_GET['dir'];
    
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
    header("Location: complaint_config.php"); exit();
}

// 2. CREATE NEW SECTION
if (isset($_POST['create_new_section'])) {
    $title = $_POST['section_title']; $type = $_POST['input_type']; 
    $col = "dyn_" . strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $title)) . "_" . rand(100,999); 
    $tbl = "opts_" . $col;
    
    $max = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MAX(display_order) as m FROM dynamic_sections"));
    $next = $max['m'] + 1;

    mysqli_query($conn, "INSERT INTO dynamic_sections (section_title, column_name, input_type, display_order) VALUES ('$title', '$col', '$type', $next)");
    mysqli_query($conn, "CREATE TABLE $tbl (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100))");
    mysqli_query($conn, "ALTER TABLE complaints ADD COLUMN $col VARCHAR(255)");
    header("Location: complaint_config.php"); exit();
}

// 3. RENAME
if (isset($_POST['rename_section'])) {
    $id = $_POST['target_id']; $name = $_POST['new_section_name'];
    mysqli_query($conn, "UPDATE dynamic_sections SET section_title = '$name' WHERE id = $id");
    header("Location: complaint_config.php"); exit();
}

// 4. REMOVE
if (isset($_GET['remove_section'])) {
    $id = $_GET['remove_section'];
    $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM dynamic_sections WHERE id = $id"));
    if ($data) {
        $col = $data['column_name']; $tbl = "opts_" . $col;
        mysqli_query($conn, "DROP TABLE IF EXISTS $tbl"); 
        mysqli_query($conn, "ALTER TABLE complaints DROP COLUMN $col"); 
        mysqli_query($conn, "DELETE FROM dynamic_sections WHERE id = $id"); 
    }
    header("Location: complaint_config.php"); exit();
}

// 5. ADD/REMOVE OPTIONS
if (isset($_POST['add_option'])) {
    $tbl = "opts_" . $_POST['target_col']; $val = $_POST['new_val'];
    mysqli_query($conn, "INSERT INTO $tbl (name) VALUES ('$val')");
}
if (isset($_GET['del_opt_id'])) {
    $tbl = "opts_" . $_GET['target']; $id = $_GET['del_opt_id'];
    mysqli_query($conn, "DELETE FROM $tbl WHERE id=$id");
    header("Location: complaint_config.php"); exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Options</title>
    <link rel="stylesheet" href="style.css"> 
    <style>
        /* ============================
           1. USER PROVIDED CLASSIC STYLES
           ============================ */
        .column { 
            /* Overridden slightly to ensure Vertical Layout for Priority Sort */
            float: none; 
            width: 95%; /* Full width for vertical stack */
            margin: 15px auto; 
            
            /* Classic look from snippet */
            padding: 15px; 
            background: white; 
            border: 1px solid #ddd; 
            border-radius: 5px; 
            box-sizing: border-box; 
        }
        
        .row:after { content: ""; display: table; clear: both; }
        
        .header-row { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 15px; 
            border-bottom: 2px solid #f2f2f2; 
            padding-bottom: 10px; 
        }
        .header-row h3 { margin: 0; color: #333; }
        
        .btn-toggle { background-color: #008CBA; color: white; border: none; padding: 6px 12px; font-size: 13px; cursor: pointer; border-radius: 3px; }
        .btn-toggle:hover { background-color: #007bb5; }

        .button-group { display: flex; gap: 5px; margin-top: 5px; }
        .btn-add { flex: 1; background-color: #28a745; color: white; border: none; padding: 8px; cursor: pointer; border-radius: 3px; }
        .btn-cancel { flex: 1; background-color: #6c757d; color: white; border: none; padding: 8px; cursor: pointer; border-radius: 3px; }
        
        ul { padding: 0; margin-top: 10px; list-style-type: none; }
        li { background: #fff; border-bottom: 1px solid #eee; padding: 8px 5px; display: flex; justify-content: space-between; align-items: center; }
        
        .btn-remove { background-color: #ff4d4d; color: white; text-decoration: none; font-size: 11px; padding: 4px 10px; border-radius: 3px; border: 1px solid #cc0000; font-family: sans-serif; }
        
        /* New Section Box */
        .create-section-box { background: #e3f2fd; padding: 20px; border: 2px dashed #2196F3; margin-bottom: 20px; text-align: center; }

        /* ============================
           2. ADDITIONAL HELPERS
           ============================ */
        
        /* Priority Box (Modern Style Kept as Requested) */
        .priority-box { background: #fff8e1; border: 1px solid #ffe082; padding: 15px; margin: 20px auto; width: 95%; border-radius: 8px; }
        .priority-row { display: flex; justify-content: space-between; align-items: center; background: white; padding: 10px 15px; margin-bottom: 5px; border: 1px solid #eee; border-radius: 4px; }
        .btn-up, .btn-down { background-color: #3498db; color: white; padding: 5px 12px; border-radius: 4px; font-weight: bold; margin-left: 2px; text-decoration: none; }
        .btn-disabled { background-color: #bdc3c7; color: #fff; padding: 5px 12px; border-radius: 4px; cursor: default; pointer-events: none; text-decoration: none; }

        /* Hidden Action Bar */
        .action-bar { background-color: #f9f9f9; padding: 10px; margin-bottom: 15px; border: 1px solid #eee; display: flex; flex-direction: column; gap: 8px; }

        /* Rename Box */
        .rename-box { display:none; background: #fffbe6; padding: 10px; border: 1px solid #e6dbb9; margin-bottom: 5px; }

        /* Safety Toggle Wrapper */
        .remove-wrapper { display: flex; gap: 5px; }
        
        /* Styles for the "Action Buttons" to match classic look */
        .btn-action-remove {
            flex: 3;
            background-color: #ff4d4d; 
            color: white; 
            border: 1px solid #cc0000; 
            padding: 8px; 
            font-size: 12px; 
            cursor: pointer; 
            text-align: center; 
            text-decoration: none;
            border-radius: 3px;
        }
        .btn-action-remove.disabled { background-color: #e0e0e0; border-color: #ccc; color: #999; pointer-events: none; }
        
        .btn-safety-toggle {
            flex: 1;
            background-color: #555;
            color: white;
            border: none;
            padding: 8px;
            font-size: 11px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 3px;
        }
        
        .btn-classic-edit { background-color: #777; color: white; padding: 5px 10px; font-size: 11px; border-radius: 3px; cursor: pointer; border: none; }

    </style>
    <script>
        function toggle(id) { var x=document.getElementById(id); x.style.display=(x.style.display==="none")?"block":"none"; }
        function toggleFlex(id) { var x=document.getElementById(id); x.style.display=(x.style.display==="none")?"flex":"none"; }
        function toggleSafety(remId, togId) {
            var r = document.getElementById(remId); var t = document.getElementById(togId);
            if (t.innerHTML === "ENABLE") { t.innerHTML = "DISABLE"; r.classList.remove("disabled"); r.style.pointerEvents="auto"; } 
            else { t.innerHTML = "ENABLE"; r.classList.add("disabled"); r.style.pointerEvents="none"; }
        }
    </script>
</head>
<body>
    <h1>Manage Complaint Page Options</h1>
    <a href="admin_dashboard.php">Back to Dashboard</a>
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
            echo "<a href='complaint_config.php?move_section=".$sec['id']."&dir=up' class='$up_class'>&uarr;</a>";
            echo "<a href='complaint_config.php?move_section=".$sec['id']."&dir=down' class='$down_class'>&darr;</a>";
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
            $table = "opts_" . $col;
            $act_id="act_$sid"; $ren_id="ren_$sid"; $add_id="add_$sid"; $rem_id="rem_$sid"; $saf_id="saf_$sid";
        ?>
            <div class="column">
                <div class="header-row">
                    <h3><?php echo $title; ?></h3>
                    <button class="btn-classic-edit" onclick="toggleFlex('<?php echo $act_id; ?>')">Edit Options</button>
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
                        <a href="complaint_config.php?remove_section=<?php echo $sid; ?>" 
                           id="<?php echo $rem_id; ?>" 
                           class="btn-action-remove disabled" 
                           onclick="return confirm('WARNING: This will delete the entire section and all data within it. Are you sure?');">
                           Remove This Section
                        </a>
                        <button id="<?php echo $saf_id; ?>" class="btn-safety-toggle" onclick="toggleSafety('<?php echo $rem_id; ?>', '<?php echo $saf_id; ?>')">ENABLE</button>
                    </div>

                    <button class="btn-toggle" onclick="toggle('<?php echo $add_id; ?>')">+ Add New Item</button>
                </div>
                
                <div id="<?php echo $add_id; ?>" style="display:none; margin-top:10px; background:#f9f9f9; padding:10px; border:1px solid #eee;">
                    <form method="POST">
                        <input type="hidden" name="target_col" value="<?php echo $col; ?>">
                        <input type="text" name="new_val" placeholder="New Option Name" required style="width:100%; padding:5px; box-sizing:border-box;">
                        <div class="button-group">
                            <input type="submit" name="add_option" value="Add" class="btn-add">
                            <button type="button" class="btn-cancel" onclick="toggle('<?php echo $add_id; ?>')">Cancel</button>
                        </div>
                    </form>
                </div>

                <ul>
                <?php
                if(mysqli_query($conn, "SHOW TABLES LIKE '$table'")) {
                    $opts = mysqli_query($conn, "SELECT * FROM $table");
                    while ($opt = mysqli_fetch_assoc($opts)) {
                        echo "<li>";
                        echo "<span>" . $opt['name'] . "</span>";
                        echo "<a href='complaint_config.php?del_opt_id=".$opt['id']."&target=".$col."' class='btn-remove'>Remove</a>";
                        echo "</li>";
                    }
                }
                ?>
                </ul>
            </div>
        <?php } ?>
    </div>
</body>
</html>