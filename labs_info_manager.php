<?php
require 'auth_session.php';

// --- CONFIGURATION: SEPARATE TABLES FOR LABS ---
$meta_table = "labs_sections";
$data_table = "labs_unit"; 

// --- LAB CONFIG ACTIONS ---

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
    }
    // header("Location: labs_info_manager.php"); exit();
}

// 2. Create New Section (Add Column)
if (isset($_POST['create_new_section'])) {
    $title = trim($_POST['section_title']); 
    $title_safe = mysqli_real_escape_string($conn, $title);
    $type = $_POST['input_type']; 
    
    // Check if duplicates are allowed (Checkbox: Checked = Yes, Unchecked = No/Unique)
    $allow_duplicates = isset($_POST['allow_duplicates']);
    $constraint = $allow_duplicates ? "" : " UNIQUE";
    
    $check = mysqli_query($conn, "SELECT id FROM $meta_table WHERE section_title = '$title_safe'");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['sys_msg'] = "$title already exists"; $_SESSION['sys_msg_color'] = "red";
    } else {
        // Generate column name
        $clean_name = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $title)); 
        $clean_name = trim($clean_name, '_');
        $col = $clean_name; 
        
        // Check if column exists in the data table
        $col_check = mysqli_query($conn, "SHOW COLUMNS FROM `$data_table` LIKE '$col'");
        if(mysqli_num_rows($col_check) > 0) {
             $_SESSION['sys_msg'] = "Error: Internal name '$clean_name' is already used."; $_SESSION['sys_msg_color'] = "red";
             // header("Location: labs_info_manager.php"); exit();
        } else {
            // Insert into Meta Table
            $max = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MAX(display_order) as m FROM $meta_table")); 
            $next = $max['m'] + 1;
            mysqli_query($conn, "INSERT INTO $meta_table (section_title, column_name, input_type, display_order) VALUES ('$title_safe', '$col', '$type', $next)");
            
            // Alter Data Table with optional UNIQUE constraint
            // Note: For UNIQUE numeric columns, we use NULL default to avoid 'Duplicate entry 0' errors on existing rows
            if ($type == 'numeric') {
                $def = $allow_duplicates ? "DEFAULT 0" : "DEFAULT NULL";
                mysqli_query($conn, "ALTER TABLE `$data_table` ADD COLUMN `$col` INT $def $constraint"); 
            } elseif ($type == 'date') { 
                mysqli_query($conn, "ALTER TABLE `$data_table` ADD COLUMN `$col` DATE DEFAULT NULL $constraint"); 
            } else { 
                mysqli_query($conn, "ALTER TABLE `$data_table` ADD COLUMN `$col` VARCHAR(255) DEFAULT NULL $constraint"); 
            }
            
            // --- ADDED: Handle Edit Option Checkbox ---
            if (isset($_POST['need_edit_option'])) {
                mysqli_query($conn, "INSERT INTO labs_edit_options (edit_options) VALUES ('$col')");
            }
            // ------------------------------------------
            
            $msg_suffix = $allow_duplicates ? "" : " (Unique)";
            $_SESSION['sys_msg'] = "Lab Field Created Successfully$msg_suffix"; $_SESSION['sys_msg_color'] = "green";
        }
    }
    // header("Location: labs_info_manager.php"); exit();
}

// 3. Rename Section
if (isset($_POST['rename_section'])) {
    $id = $_POST['target_id']; 
    $new_name = trim($_POST['new_section_name']);
    $new_name_safe = mysqli_real_escape_string($conn, $new_name);
    
    $check = mysqli_query($conn, "SELECT id FROM $meta_table WHERE section_title = '$new_name_safe' AND id != $id");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['sys_msg'] = "Name already exists"; $_SESSION['sys_msg_color'] = "red";
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

        // Rename column in data table if the clean name changed
        if ($old_col != $new_col) {
            mysqli_query($conn, "ALTER TABLE `$data_table` CHANGE `$old_col` `$new_col` $sql_type");
        }
        
        mysqli_query($conn, "UPDATE $meta_table SET section_title = '$new_name_safe', column_name = '$new_col' WHERE id = $id");
        $_SESSION['sys_msg'] = "Renamed Successfully"; $_SESSION['sys_msg_color'] = "green";
    }
    // header("Location: labs_info_manager.php"); exit();
}

// 4. Remove Section
if (isset($_GET['remove_section'])) {
    $id = $_GET['remove_section'];
    $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM $meta_table WHERE id = $id"));
    if ($data) {
        $col = $data['column_name']; 
        try { 
            mysqli_query($conn, "ALTER TABLE `$data_table` DROP COLUMN `$col`"); 
        } catch (Exception $e) {}
        mysqli_query($conn, "DELETE FROM $meta_table WHERE id = $id"); 
    }
    $_SESSION['sys_msg'] = "Field Removed"; $_SESSION['sys_msg_color'] = "red";
    // header("Location: labs_info_manager.php"); exit();
}

include 'header.php';
?>

<h1><strong>Labs Information Management</strong></h1>
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
    <h3 style="margin-top:0;">Need a new Lab Info field?</h3>
    <button class="btn-toggle" onclick="toggle('new_sec_form_lab')">+ Create New Field</button>
    <div id="new_sec_form_lab" style="display:none; margin-top:15px; width: 70%; margin-left: auto; margin-right: auto;">
        <form method="POST">
            <input type="text" name="section_title" placeholder="Field Name (e.g. Lab Number, PC Count)" required>
            <select name="input_type">
                <option value="alphanumeric">Alphanumeric (Text)</option>
                <option value="numeric">Numeric Only</option>
                <option value="date">Date</option>
            </select>
            
            <div style="margin-top:12px; margin-bottom:12px; background:#f9f9f9; padding:8px; border-radius:4px; border:1px solid #eee;">
                <label style="display:inline-flex; align-items:center; gap:8px; font-weight:normal; cursor:pointer;">
                    <input type="checkbox" name="allow_duplicates" checked style="width:auto; margin:0;"> 
                    <span><strong>Allow Duplicate Values</strong> <br><span style="font-size:12px; color:#666;">(Uncheck this to enforce unique values only, e.g., for Lab IDs)</span></span>
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
                <button type="button" class="btn-cancel" onclick="toggle('new_sec_form_lab')">Cancel</button>
            </div>
        </form>
    </div>
</div>

<div class="priority-box">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
        <h3 style="margin:0;">Field Priority / Reorder</h3>
        <button class="btn-arrow" onclick="toggleList('prio_list_lab', this)">&#9654;</button>
    </div>
    <div id="prio_list_lab" style="display:none;">
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
            echo "<a href='labs_info_manager.php?move_section=".$sec['id']."&dir=up' class='$up_class'>&uarr;</a>";
            echo "<a href='labs_info_manager.php?move_section=".$sec['id']."&dir=down' class='$down_class'>&darr;</a>";
            echo "</div></div>";
        }
        ?>
    </div>
</div>

<div class="row">
    <?php foreach($all_secs as $sec) {
        $title = $sec['section_title']; 
        $sid = $sec['id'];
        $act_id="act_lab_$sid"; 
        $ren_id="ren_lab_$sid"; 
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
                        <input type="hidden" name="target_id" value="<?php echo $sid; ?>">
                        <input type="text" name="new_section_name" value="<?php echo $title; ?>" required>
                        <div class="button-group">
                            <input type="submit" name="rename_section" value="Save Name" class="btn-add">
                        </div>
                    </form>
                </div>
                
                <div class="remove-wrapper">
                    <a href="labs_info_manager.php?remove_section=<?php echo $sid; ?>" id="rem_lab_<?php echo $sid; ?>" class="btn-action-remove disabled" onclick="return confirm('Remove this field? Data in this column will be lost.');">Remove Field</a>
                    <button id="saf_lab_<?php echo $sid; ?>" class="btn-safety-toggle" onclick="toggleSafety('rem_lab_<?php echo $sid; ?>', this.id)">ENABLE</button>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

</div>
</body>
</html>