<?php
require 'auth_session.php';

$meta_table = "dynamic_sections";
$data_table = "complaints"; 
$log_table  = "complaints_log"; 

// --- DEFINING STATIC SECTIONS (Cannot be removed) ---
$protected_cols = ['lab_name', 'system_number'];
// ----------------------------------------------------

// --- CONFIG ACTIONS ---

// 1. MOVE SECTION (Reorder)
if (isset($_GET['move_section']) && isset($_GET['dir'])) {
    $id = intval($_GET['move_section']); $dir = $_GET['dir'];
    $curr = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id, display_order FROM $meta_table WHERE id = $id"));
    $curr_order = $curr['display_order'];
    if ($dir == 'up') { $target = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id, display_order FROM $meta_table WHERE display_order < $curr_order ORDER BY display_order DESC LIMIT 1")); } 
    else { $target = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id, display_order FROM $meta_table WHERE display_order > $curr_order ORDER BY display_order ASC LIMIT 1")); }
    if ($target) {
        $t_id = $target['id']; $t_order = $target['display_order'];
        mysqli_query($conn, "UPDATE $meta_table SET display_order = $t_order WHERE id = $id");
        mysqli_query($conn, "UPDATE $meta_table SET display_order = $curr_order WHERE id = $t_id");
        
        $_SESSION['sys_msg'] = "Order updated successfully"; 
        $_SESSION['sys_msg_color'] = "green";
    }
    // Removed session_write_close();
    // header("Location: manage_config.php");
    // exit();
}

// 2. CREATE NEW SECTION
if (isset($_POST['create_new_section'])) {
    $title = trim($_POST['section_title']); 
    $title_safe = mysqli_real_escape_string($conn, $title);
    $type = $_POST['input_type']; 
    $is_unique = isset($_POST['is_unique']) ? 1 : 0; 
    
    $check = mysqli_query($conn, "SELECT id FROM $meta_table WHERE section_title = '$title_safe'");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['sys_msg'] = "'$title' already exists, Please choose other name."; 
        $_SESSION['sys_msg_color'] = "red";
    } else {
        $clean_name = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $title)); $clean_name = trim($clean_name, '_');
        $col = $clean_name; $tbl = $clean_name; 
        
        $col_check = mysqli_query($conn, "SHOW COLUMNS FROM `$data_table` LIKE '$col'");
        $tbl_exists = false;
        if ($type != 'email') {
            $tbl_check = mysqli_query($conn, "SHOW TABLES LIKE '$tbl'");
            if (mysqli_num_rows($tbl_check) > 0) { $tbl_exists = true; }
        }

        if(mysqli_num_rows($col_check) > 0 || $tbl_exists) {
             $_SESSION['sys_msg'] = "Error: Database field '$clean_name' is already in use."; 
             $_SESSION['sys_msg_color'] = "red";
             // Removed session_write_close();
             // header("Location: manage_config.php");
             // exit();
        }

        $max = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MAX(display_order) as m FROM $meta_table")); $next = $max['m'] + 1;
        
        if(mysqli_query($conn, "INSERT INTO $meta_table (section_title, column_name, input_type, display_order, is_unique) VALUES ('$title_safe', '$col', '$type', $next, $is_unique)")) {
            try {
                mysqli_query($conn, "ALTER TABLE `$data_table` ADD COLUMN `$col` VARCHAR(255)");
                mysqli_query($conn, "ALTER TABLE `$log_table` ADD COLUMN `$col` VARCHAR(255)");

                if ($type != 'email') {
                    if ($is_unique != 1) { mysqli_query($conn, "CREATE TABLE `$tbl` (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100), UNIQUE(name))"); } 
                    else { mysqli_query($conn, "CREATE TABLE `$tbl` (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100))"); }
                }

                $_SESSION['sys_msg'] = "Successfully created new section with '$title' name"; 
                $_SESSION['sys_msg_color'] = "green";

            } catch (Exception $e) {
                $_SESSION['sys_msg'] = "Database Error: " . $e->getMessage();
                $_SESSION['sys_msg_color'] = "red";
            }
        } else {
            $_SESSION['sys_msg'] = "Error: Could not save section metadata."; 
            $_SESSION['sys_msg_color'] = "red";
        }
    }
    // Removed session_write_close();
    // header("Location: manage_config.php");
    // exit();
}

// 3. RENAME SECTION
if (isset($_POST['rename_section'])) {
    $id = $_POST['target_id']; $new_name = trim($_POST['new_section_name']);
    $new_name_safe = mysqli_real_escape_string($conn, $new_name);
    
    $old_row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT section_title, input_type FROM $meta_table WHERE id = $id")); 
    $old_name = $old_row['section_title'];
    $type = $old_row['input_type'];

    $check = mysqli_query($conn, "SELECT id FROM $meta_table WHERE section_title = '$new_name_safe' AND id != $id");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['sys_msg'] = "'$new_name' already exists, Please choose other name."; 
        $_SESSION['sys_msg_color'] = "red";
    } else {
        $curr_query = mysqli_query($conn, "SELECT column_name FROM $meta_table WHERE id = $id");
        $curr_row = mysqli_fetch_assoc($curr_query);
        $old_col = $curr_row['column_name'];
        $new_col = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $new_name)); $new_col = trim($new_col, '_');
        
        try {
            if ($old_col != $new_col) {
                mysqli_query($conn, "ALTER TABLE `$data_table` CHANGE `$old_col` `$new_col` VARCHAR(255)");
                mysqli_query($conn, "ALTER TABLE `$log_table` CHANGE `$old_col` `$new_col` VARCHAR(255)");

                if ($type != 'email') {
                    mysqli_query($conn, "RENAME TABLE `$old_col` TO `$new_col`");
                }
            }
            mysqli_query($conn, "UPDATE $meta_table SET section_title = '$new_name_safe', column_name = '$new_col' WHERE id = $id");
            
            $_SESSION['sys_msg'] = "'$old_name' is successfully renamed to '$new_name'"; 
            $_SESSION['sys_msg_color'] = "green";

        } catch (Exception $e) {
            $_SESSION['sys_msg'] = "Error updating database columns: " . $e->getMessage();
            $_SESSION['sys_msg_color'] = "red";
        }
    }
    // Removed session_write_close();
    // header("Location: manage_config.php");
    // exit();
}

// 4. REMOVE SECTION
if (isset($_GET['remove_section'])) {
    $id = $_GET['remove_section'];
    $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM $meta_table WHERE id = $id"));
    if ($data) {
        $col = $data['column_name']; 
        
        if (in_array($col, $protected_cols)) {
            $_SESSION['sys_msg'] = "Error: '$col' is a protected system section.";
            $_SESSION['sys_msg_color'] = "red";
            // Removed session_write_close();
            // header("Location: manage_config.php");
            // exit();
        }

        $tbl = $col; 
        $type = $data['input_type'];
        $title = $data['section_title'];

        try {
            if ($type != 'email') {
                mysqli_query($conn, "DROP TABLE IF EXISTS `$tbl`"); 
            }
            mysqli_query($conn, "ALTER TABLE `$data_table` DROP COLUMN `$col`");
            mysqli_query($conn, "ALTER TABLE `$log_table` DROP COLUMN `$col`");
            mysqli_query($conn, "DELETE FROM $meta_table WHERE id = $id"); 
            
            $_SESSION['sys_msg'] = "Section '$title' removed successfully"; 
            $_SESSION['sys_msg_color'] = "green";

        } catch (Exception $e) {
            $_SESSION['sys_msg'] = "Error removing section: " . $e->getMessage();
            $_SESSION['sys_msg_color'] = "red";
        }
    } else {
        $_SESSION['sys_msg'] = "Error: Section not found.";
        $_SESSION['sys_msg_color'] = "red";
    }
    // Removed session_write_close();
    // header("Location: manage_config.php");
    // exit();
}

// 5. ADD OPTION
if (isset($_POST['add_option'])) {
    $target_col = $_POST['target_col']; $tbl = $target_col; 
    $raw_input = trim($_POST['new_val']); $items_to_add = [];
    
    $sec_info = mysqli_fetch_assoc(mysqli_query($conn, "SELECT section_title, is_unique FROM $meta_table WHERE column_name = '$target_col'"));
    $section_name = $sec_info['section_title'];
    $enforce_unique = ($sec_info['is_unique'] == 1); 

    $extra_cols = ""; $extra_vals = ""; $lab_table_created = false;
    if ($target_col == 'lab_name') {
        $tbl_lab_name = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $raw_input));
        $tbl_lab_name = trim($tbl_lab_name, '_');
        $extra_cols = ", table_lab_name"; $extra_vals = ", '$tbl_lab_name'";
        $create_lab_sql = "CREATE TABLE IF NOT EXISTS `$tbl_lab_name` (
            id INT AUTO_INCREMENT PRIMARY KEY,
            system_number VARCHAR(100) NOT NULL,
            os VARCHAR(100), config_details TEXT,
            UNIQUE(system_number))";
        if(mysqli_query($conn, $create_lab_sql)) { $lab_table_created = true; }
    }

    $lab_link_success = false;
    if ($target_col == 'system_number' && !empty($_POST['linked_lab_name'])) {
        $linked_lab = mysqli_real_escape_string($conn, $_POST['linked_lab_name']);
        if (preg_match('/^([a-zA-Z0-9\.-]*?)(\d+)-(\d+)$/', $raw_input, $matches)) {
            $prefix = $matches[1]; $start = (int)$matches[2]; $end = (int)$matches[3];
            $padding = strlen((string)$end);
            $config_sql = "INSERT INTO lab_series_config (lab_name, prefix, start_no, end_no, padding) 
                           VALUES ('$linked_lab', '$prefix', $start, $end, $padding)
                           ON DUPLICATE KEY UPDATE prefix='$prefix', start_no=$start, end_no=$end, padding=$padding";
            mysqli_query($conn, $config_sql);
            $lab_link_success = true;
            if ($start <= $end) { 
                for ($i = $start; $i <= $end; $i++) { $items_to_add[] = $prefix . str_pad($i, $padding, '0', STR_PAD_LEFT); } 
            }
        } else { $items_to_add[] = $raw_input; }
    } else {
        if (preg_match('/^([a-zA-Z0-9\.-]*?)(\d+)-(\d+)$/', $raw_input, $matches)) {
            $prefix = $matches[1]; $start = (int)$matches[2]; $end = (int)$matches[3];
            if ($start <= $end) { for ($i = $start; $i <= $end; $i++) { $items_to_add[] = $prefix . $i; } }
        } else { $items_to_add[] = $raw_input; }
    }
    
    $duplicates_found = [];
    $success_count = 0;

    foreach ($items_to_add as $val) {
        $val_safe = mysqli_real_escape_string($conn, $val);
        try {
            if ($enforce_unique) {
                $check = mysqli_query($conn, "SELECT id FROM `$tbl` WHERE name = '$val_safe'");
                if (mysqli_num_rows($check) > 0) { $duplicates_found[] = $val; } 
                else { 
                    mysqli_query($conn, "INSERT INTO `$tbl` (name $extra_cols) VALUES ('$val_safe' $extra_vals)"); 
                    $success_count++;
                }
            } else { 
                mysqli_query($conn, "INSERT INTO `$tbl` (name $extra_cols) VALUES ('$val_safe' $extra_vals)"); 
                $success_count++;
            }
        } catch (Exception $e) { $duplicates_found[] = "$val (Error)"; }
    }

    if (!empty($duplicates_found)) {
        if ($enforce_unique) {
             $_SESSION['sys_msg'] = "'$section_name' takes only unique values."; 
             $_SESSION['sys_msg_color'] = "red";
        } else {
             $dup_list = implode(", ", $duplicates_found);
             $_SESSION['sys_msg'] = "Error: Items '$dup_list' could not be added.";
             $_SESSION['sys_msg_color'] = "red";
        }
    } else {
        if ($success_count > 0) {
             $added_items = implode(", ", $items_to_add);
             if(strlen($added_items) > 50) $added_items = $success_count . " items"; 
             $_SESSION['sys_msg'] = "'$added_items' successfully added into '$section_name'"; 
             $_SESSION['sys_msg_color'] = "green";
        } else {
             $_SESSION['sys_msg'] = "No items added.";
             $_SESSION['sys_msg_color'] = "orange";
        }
    }
    // Removed session_write_close();
    // header("Location: manage_config.php");
    // exit(); 
}

// 6. DELETE OPTION
if (isset($_GET['del_opt_id'])) {
    $col = $_GET['target']; $tbl = $col; $id = $_GET['del_opt_id'];
    try {
        if(mysqli_query($conn, "DELETE FROM `$tbl` WHERE id=$id")) {
            $_SESSION['sys_msg'] = "Option deleted successfully";
            $_SESSION['sys_msg_color'] = "green";
        } else {
            $_SESSION['sys_msg'] = "Error: Could not delete option.";
            $_SESSION['sys_msg_color'] = "red";
        }
    } catch (Exception $e) {
        $_SESSION['sys_msg'] = "Error: " . $e->getMessage();
        $_SESSION['sys_msg_color'] = "red";
    }
    // Removed session_write_close();
    // header("Location: manage_config.php");
    // exit();
}

include 'header.php';
?>

<h1><strong>Manage Complaint Page Options</strong></h1>
<hr>


<div class="create-section-box">
    <h3 style="margin-top:0;">Need a new category?</h3>
    <button class="btn-toggle" onclick="toggle('new_sec_form_config')">+ Create New Section</button>
    <div id="new_sec_form_config" style="display:none; margin-top:15px; width: 70%; margin-left: auto; margin-right: auto;">
        <form method="POST">
            <input type="text" name="section_title" placeholder="Name (e.g. Room Number)" required>
            <select name="input_type">
                <option value="dropdown">Dropdown</option>
                <option value="checkbox">Checkbox</option>
                <option value="email">Email (Input Field)</option> </select>
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
        $res = mysqli_query($conn, "SELECT * FROM $meta_table ORDER BY display_order ASC");
        while($row = mysqli_fetch_assoc($res)) { $all_secs[] = $row; }
        foreach($all_secs as $index => $sec) {
            $is_first = ($index === 0); $is_last = ($index === (count($all_secs) - 1));
            $up_class = $is_first ? "btn-disabled" : "btn-up";
            $down_class = $is_last ? "btn-disabled" : "btn-down";
            echo "<div class='priority-row'><strong>" . $sec['section_title'] . "</strong><div>";
            echo "<a href='manage_config.php?move_section=".$sec['id']."&dir=up' class='$up_class'>&uarr;</a>";
            echo "<a href='manage_config.php?move_section=".$sec['id']."&dir=down' class='$down_class'>&darr;</a>";
            echo "</div></div>";
        }
        ?>
    </div>
</div>

<div class="row">
    <?php foreach($all_secs as $sec) {
        $title = $sec['section_title']; $col = $sec['column_name']; 
        $sid = $sec['id']; $table = $col; $type = $sec['input_type'];
        $act_id="act_$sid"; $ren_id="ren_$sid"; $add_id="add_$sid"; $list_id="list_$sid"; 
        
        $is_static = in_array($col, $protected_cols);
    ?>
        <div class="column">
            <div class="header-row">
                <div style="display:flex; align-items:center;">
                    <h3><?php echo $title; ?> <span style="font-size:12px; color:#777; font-weight:normal;">(<?php echo ucfirst($type); ?>)</span></h3>
                    <?php if ($type != 'email'): ?>
                        <button class="btn-arrow" onclick="toggleList('<?php echo $list_id; ?>', this)">&#9654;</button>
                    <?php endif; ?>
                </div>
                <button class="btn-edit-trigger" onclick="toggleFlex('<?php echo $act_id; ?>')">Edit Options</button>
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
                            STATIC (SYSTEM REQUIRED)
                        </div>
                    <?php else: ?>
                        <a href="manage_config.php?remove_section=<?php echo $sid; ?>" class="btn-action-remove disabled" id="rem_<?php echo $sid; ?>" onclick="return confirm('Delete entire section?');">Remove Section</a>
                        <button id="saf_<?php echo $sid; ?>" class="btn-safety-toggle" onclick="toggleSafety('rem_<?php echo $sid; ?>', this.id)">ENABLE</button>
                    <?php endif; ?>
                </div>
                
                <?php if ($type != 'email'): ?>
                    <button class="btn-toggle" onclick="toggle('<?php echo $add_id; ?>')">+ Add New Item</button>
                <?php endif; ?>
            </div>

            <?php if ($type != 'email'): ?>
                <div id="<?php echo $add_id; ?>" style="display:none; margin-top:10px; background:#f9f9f9; padding:15px; border-radius:8px; border:1px solid #eee;">
                    <form method="POST">
                        <input type="hidden" name="target_col" value="<?php echo $col; ?>">
                        
                        <?php if($col == 'system_number'): ?>
                            <label style="display:block; margin-bottom:5px; font-weight:600; font-size:12px;">Select Lab (for Configuration):</label>
                            <select name="linked_lab_name" style="margin-bottom:10px; border:1px solid #ccc; width:100%; padding:8px;">
                                <option value="">-- No Lab Link --</option>
                                <?php
                                $l_chk = mysqli_query($conn, "SHOW TABLES LIKE 'lab_name'");
                                if(mysqli_num_rows($l_chk) > 0) {
                                    $l_res = mysqli_query($conn, "SELECT name FROM lab_name ORDER BY name ASC");
                                    while($lr = mysqli_fetch_assoc($l_res)) {
                                        echo "<option value='".htmlspecialchars($lr['name'])."'>".htmlspecialchars($lr['name'])."</option>";
                                    }
                                }
                                ?>
                            </select>
                            <div style="font-size:11px; color:#666; margin-bottom:5px;">Ex: Select 'Aryabhatta' and type 'ARY1-134'</div>
                        <?php endif; ?>
                        
                        <input type="text" name="new_val" placeholder="Name or Range (e.g. PC1-5 or ARY1-134)" required>
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
                            echo "<li><span>" . $opt['name'] . "</span><a href='manage_config.php?del_opt_id=".$opt['id']."&target=".$col."' class='btn-remove'>Remove</a></li>";
                        }
                    }
                    ?>
                </ul>
            <?php else: ?>
                <p style="font-size:13px; color:#888; font-style:italic;">Users will type the email directly.</p>
            <?php endif; ?>
        </div>
    <?php } ?>
</div>
</div>
</body>
</html>