<?php
require 'auth_session.php';

// --- CONFIGURATION ---
$meta_table = "systems_sections";
$lab_list_table = "labs_unit"; // MODIFIED: Pointing to labs_unit

// --- DEFINING STATIC SECTIONS ---
// Empty array so NO columns are protected (All have Remove buttons)
$protected_cols = []; 

// =============================================================
// 1. AUTO-INIT: Defaults if Missing (Table Creation Removed)
// =============================================================

// Ensure default columns exist in the meta table (only if table is empty)
$check_empty = mysqli_query($conn, "SELECT id FROM $meta_table LIMIT 1");
if (mysqli_num_rows($check_empty) == 0) {
    $defaults = [
        ['title' => 'System Number', 'col' => 'system_number', 'order' => 1],
        ['title' => 'Operating System', 'col' => 'os', 'order' => 2],
        ['title' => 'Configuration', 'col' => 'config_details', 'order' => 3]
    ];

    foreach ($defaults as $def) {
        $t = $def['title'];
        $c = $def['col'];
        $o = $def['order'];
        mysqli_query($conn, "INSERT INTO $meta_table (section_title, column_name, input_type, display_order) VALUES ('$t', '$c', 'text', $o)");
    }
}
// =============================================================


// --- HELPER: Get All Lab Tables ---
function getLabTables($conn, $lab_list_table) {
    $tables = [];
    $res = mysqli_query($conn, "SELECT table_lab_name FROM $lab_list_table WHERE table_lab_name IS NOT NULL AND table_lab_name != ''");
    while($row = mysqli_fetch_assoc($res)) {
        $tbl = $row['table_lab_name'];
        $tbl_check = mysqli_query($conn, "SHOW TABLES LIKE '$tbl'");
        if (mysqli_num_rows($tbl_check) > 0) {
            $tables[] = $tbl;
        }
    }
    return $tables;
}


// --- ACTIONS ---

// 1. Move Section (Reorder)
if (isset($_GET['move_section']) && isset($_GET['dir'])) {
    $id = intval($_GET['move_section']); $dir = $_GET['dir'];
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
        
        $_SESSION['sys_msg'] = "Order updated successfully"; 
        $_SESSION['sys_msg_color'] = "green";
    }
    // header("Location: systems_info_manager.php"); exit();
}

// 2. Create New Section (Add Column to ALL Lab Tables)
if (isset($_POST['create_new_section'])) {
    $title = trim($_POST['section_title']); 
    $title_safe = mysqli_real_escape_string($conn, $title);
    
    // Check if duplicates are allowed (Checkbox: Checked = Yes, Unchecked = No/Unique)
    $allow_duplicates = isset($_POST['allow_duplicates']);
    
    $check = mysqli_query($conn, "SELECT id FROM $meta_table WHERE section_title = '$title_safe'");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['sys_msg'] = "Error: Field '$title' already exists"; 
        $_SESSION['sys_msg_color'] = "red";
    } else {
        $clean_name = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $title)); 
        $clean_name = trim($clean_name, '_');
        $col = $clean_name; 
        
        $col_check = mysqli_query($conn, "SELECT id FROM $meta_table WHERE column_name = '$col'");
        if(mysqli_num_rows($col_check) > 0) {
             $_SESSION['sys_msg'] = "Error: Database column '$clean_name' is already in use."; 
             $_SESSION['sys_msg_color'] = "red";
             // header("Location: systems_info_manager.php"); exit();
        } else {
            $max = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MAX(display_order) as m FROM $meta_table")); 
            $next = $max['m'] + 1;
            mysqli_query($conn, "INSERT INTO $meta_table (section_title, column_name, input_type, display_order) VALUES ('$title_safe', '$col', 'text', $next)");
            
            $target_tables = getLabTables($conn, $lab_list_table);
            $success_count = 0;
            
            // Determine Column Type based on Duplicates checkbox
            // If Duplicates Allowed: TEXT (Default)
            // If Unique Required: VARCHAR(255) UNIQUE (TEXT cannot be unique without key length)
            if ($allow_duplicates) {
                $column_def = "TEXT DEFAULT NULL";
            } else {
                $column_def = "VARCHAR(255) DEFAULT NULL UNIQUE";
            }
            
            foreach ($target_tables as $tbl) {
                $exists = mysqli_query($conn, "SHOW COLUMNS FROM `$tbl` LIKE '$col'");
                if (mysqli_num_rows($exists) == 0) {
                    if(mysqli_query($conn, "ALTER TABLE `$tbl` ADD COLUMN `$col` $column_def")) {
                        $success_count++;
                    }
                }
            }
            
            // --- ADDED: Handle Edit Option Checkbox ---
            if (isset($_POST['need_edit_option'])) {
                mysqli_query($conn, "INSERT INTO systems_edit_options (edit_options) VALUES ('$col')");
            }
            // ------------------------------------------
            
            $msg_suffix = $allow_duplicates ? "" : " (Unique)";
            $_SESSION['sys_msg'] = "Field created. Added to $success_count lab tables$msg_suffix."; 
            $_SESSION['sys_msg_color'] = "green";
        }
    }
    // header("Location: systems_info_manager.php"); exit();
}

// 3. Rename Section
if (isset($_POST['rename_section'])) {
    $id = $_POST['target_id']; 
    $new_name = trim($_POST['new_section_name']);
    $new_name_safe = mysqli_real_escape_string($conn, $new_name);
    
    $check = mysqli_query($conn, "SELECT id FROM $meta_table WHERE section_title = '$new_name_safe' AND id != $id");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['sys_msg'] = "Name already exists"; 
        $_SESSION['sys_msg_color'] = "red";
    } else {
        $curr_query = mysqli_query($conn, "SELECT column_name FROM $meta_table WHERE id = $id");
        $curr_row = mysqli_fetch_assoc($curr_query);
        $old_col = $curr_row['column_name'];
        
        $new_col = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $new_name)); 
        $new_col = trim($new_col, '_');
        
        $updated_tables = 0;
        if ($old_col != $new_col) {
            $target_tables = getLabTables($conn, $lab_list_table);
            foreach ($target_tables as $tbl) {
                $exists = mysqli_query($conn, "SHOW COLUMNS FROM `$tbl` LIKE '$old_col'");
                if (mysqli_num_rows($exists) > 0) {
                    // When renaming, we preserve the data type logic if possible, 
                    // but for safety we default to TEXT unless it was unique. 
                    // For simplicity in rename, we keep TEXT or VARCHAR depending on previous state is hard to track.
                    // We will default to TEXT to be safe for data size, but this might drop Unique constraints.
                    // Ideally, rename just changes name.
                    
                    mysqli_query($conn, "ALTER TABLE `$tbl` CHANGE `$old_col` `$new_col` TEXT DEFAULT NULL");
                    $updated_tables++;
                }
            }
        }
        
        mysqli_query($conn, "UPDATE $meta_table SET section_title = '$new_name_safe', column_name = '$new_col' WHERE id = $id");
        
        $_SESSION['sys_msg'] = "Renamed Successfully" . ($updated_tables > 0 ? " in $updated_tables tables." : "."); 
        $_SESSION['sys_msg_color'] = "green";
    }
    // header("Location: systems_info_manager.php"); exit();
}

// 4. Remove Section
if (isset($_GET['remove_section'])) {
    $id = $_GET['remove_section'];
    $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM $meta_table WHERE id = $id"));
    if ($data) {
        $col = $data['column_name']; 
        
        $target_tables = getLabTables($conn, $lab_list_table);
        $dropped_count = 0;
        
        foreach ($target_tables as $tbl) {
            try { 
                if(mysqli_query($conn, "ALTER TABLE `$tbl` DROP COLUMN `$col`")) {
                    $dropped_count++;
                }
            } catch (Exception $e) {}
        }

        mysqli_query($conn, "DELETE FROM $meta_table WHERE id = $id"); 
        
        $_SESSION['sys_msg'] = "Field Removed from $dropped_count tables."; 
        $_SESSION['sys_msg_color'] = "green";
    } else {
        $_SESSION['sys_msg'] = "Field not found."; 
        $_SESSION['sys_msg_color'] = "red";
    }
    // header("Location: systems_info_manager.php"); exit();
}

include 'header.php';
?>

<h1><strong>Systems Information Management</strong></h1>
<p><a href="labs_hub.php" class="btn-outline">&larr; Back to Hub</a></p>
<hr>

<?php if(isset($_SESSION['sys_msg']) && $_SESSION['sys_msg'] != ""): ?>
    <div style="text-align:center; margin-bottom:20px; padding:12px; border-radius:6px; 
                background-color: <?php echo ($_SESSION['sys_msg_color']=='green')?'#e8f5e9':'#fce4ec'; ?>; 
                color: <?php echo ($_SESSION['sys_msg_color']=='green')?'#2e7d32':'#c62828'; ?>; border:1px solid transparent;">
        <strong><?php echo $_SESSION['sys_msg']; ?></strong>
    </div>
    <?php 
    unset($_SESSION['sys_msg']);
    unset($_SESSION['sys_msg_color']);
    ?>
<?php endif; ?>
<div class="create-section-box">
    <h3 style="margin-top:0;">Add New System Specification</h3>
    <button class="btn-toggle" onclick="toggle('new_sec_form_sys')">+ Create New Field</button>
    <div id="new_sec_form_sys" style="display:none; margin-top:15px; width: 70%; margin-left: auto; margin-right: auto;">
        <form method="POST">
            <input type="text" name="section_title" placeholder="Field Name (e.g. Graphics Card, Monitor ID)" required>
            
            <div style="margin-bottom:15px; text-align:left; font-size:13px; color:#555; background:#f0f0f0; padding:10px; border-radius:4px;">
                <strong>Data Type:</strong> Text / Long Text<br>
                <em>System fields support alphanumeric text. Unchecking "Allow Duplicates" will limit length to 255 chars to support uniqueness.</em>
            </div>
            
            <div style="margin-top:12px; margin-bottom:12px; background:#f9f9f9; padding:8px; border-radius:4px; border:1px solid #eee;">
                <label style="display:inline-flex; align-items:center; gap:8px; font-weight:normal; cursor:pointer;">
                    <input type="checkbox" name="allow_duplicates" checked style="width:auto; margin:0;"> 
                    <span><strong>Allow Duplicate Values</strong> <br><span style="font-size:12px; color:#666;">(Uncheck this to enforce unique values only, e.g., for Mac Address)</span></span>
                </label>
            </div>
            
            <div style="margin: 10px 0; text-align: left;">
                <label style="display: inline-flex; align-items: center; cursor: pointer; color: #333;">
                    <input type="checkbox" name="need_edit_option" value="1" style="width:auto; margin-right:8px;"> 
                    Need Edit Option
                </label>
            </div>
            <div class="button-group">
                <input type="submit" name="create_new_section" value="Create Field" class="btn-add">
                <button type="button" class="btn-cancel" onclick="toggle('new_sec_form_sys')">Cancel</button>
            </div>
        </form>
    </div>
</div>

<div class="priority-box">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
        <h3 style="margin:0;">Field Priority / Reorder</h3>
        <button class="btn-arrow" onclick="toggleList('prio_list_sys', this)">&#9654;</button>
    </div>
    <div id="prio_list_sys" style="display:none;">
        <?php 
        $all_secs = [];
        $res = mysqli_query($conn, "SELECT * FROM $meta_table ORDER BY display_order ASC");
        while($row = mysqli_fetch_assoc($res)) { $all_secs[] = $row; }
        
        foreach($all_secs as $sec) {
            $title = $sec['section_title']; 
            $sid = $sec['id'];
            $is_first = ($sec === reset($all_secs));
            $is_last = ($sec === end($all_secs));
            $up_class = $is_first ? "btn-disabled" : "btn-up";
            $down_class = $is_last ? "btn-disabled" : "btn-down";
            
            echo "<div class='priority-row'><strong>" . $sec['section_title'] . "</strong><div>";
            echo "<a href='systems_info_manager.php?move_section=".$sec['id']."&dir=up' class='$up_class'>&uarr;</a>";
            echo "<a href='systems_info_manager.php?move_section=".$sec['id']."&dir=down' class='$down_class'>&darr;</a>";
            echo "</div></div>";
        }
        ?>
    </div>
</div>

<div class="row">
    <?php foreach($all_secs as $sec) {
        $title = $sec['section_title']; 
        $sid = $sec['id'];
        $col = $sec['column_name'];
        $act_id="act_sys_$sid"; 
        $ren_id="ren_sys_$sid"; 
        
        $is_static = in_array($col, $protected_cols);
    ?>
        <div class="column">
            <div class="header-row">
                <h3><?php echo $title; ?> <span style="font-size:12px; color:#999;">(Text)</span></h3>
                <button class="btn-edit-trigger" onclick="toggleFlex('<?php echo $act_id; ?>')">Edit</button>
            </div>
            
            <div id="<?php echo $act_id; ?>" class="action-bar" style="display:none;">
                <button class="btn-toggle" style="background-color:#ff9800;" onclick="toggle('<?php echo $ren_id; ?>')">Edit Label</button>
                
                <div id="<?php echo $ren_id; ?>" class="rename-box">
                    <form method="POST">
                        <input type="hidden" name="target_id" value="<?php echo $sid; ?>">
                        <input type="text" name="new_section_name" value="<?php echo $title; ?>" required>
                        <div class="button-group">
                            <input type="submit" name="rename_section" value="Save Name" class="btn-add">
                        </div>
                    </form>
                </div>
                
                <div class="remove-wrapper">
                    <?php if ($is_static): ?>
                        <div style="flex: 3; background-color:#546e7a; color:white; padding:10px; text-align:center; border-radius:4px; font-size:13px; font-weight:bold; cursor:default;">
                            SYSTEM DEFAULT
                        </div>
                    <?php else: ?>
                        <a href="systems_info_manager.php?remove_section=<?php echo $sid; ?>" id="rem_sys_<?php echo $sid; ?>" class="btn-action-remove disabled" onclick="return confirm('WARNING: This will delete this column from ALL lab tables. Data will be lost.');">Remove Field</a>
                        <button id="saf_sys_<?php echo $sid; ?>" class="btn-safety-toggle" onclick="toggleSafety('rem_sys_<?php echo $sid; ?>', this.id)">ENABLE</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

</div>
</body>
</html>