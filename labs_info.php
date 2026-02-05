<?php
require 'auth_session.php';

// =============================================================
// --- ADDED: HANDLE FIELD UPDATE ---
// =============================================================
if (isset($_POST['update_info_field'])) {
    $id = intval($_POST['target_id']);
    $col = mysqli_real_escape_string($conn, $_POST['target_col']);
    $val = mysqli_real_escape_string($conn, $_POST['new_value']);
    $tbl = mysqli_real_escape_string($conn, $_POST['target_table']);

    // Security: Check permissions based on the table type
    $allowed = false;
    
    if ($tbl == 'labs_unit') {
        // Checking Lab Options
        $check = mysqli_query($conn, "SELECT id FROM labs_edit_options WHERE edit_options = '$col'");
        if (mysqli_num_rows($check) > 0) $allowed = true;
    } else {
        // Checking System Options (Dynamic Tables)
        $check = mysqli_query($conn, "SELECT id FROM systems_edit_options WHERE edit_options = '$col'");
        if (mysqli_num_rows($check) > 0) $allowed = true;
    }

    if ($allowed) {
        $update_sql = "UPDATE `$tbl` SET `$col` = '$val' WHERE id = $id";
        if(mysqli_query($conn, $update_sql)) {
            $_SESSION['sys_msg'] = "Updated Successfully"; $_SESSION['sys_msg_color'] = "green";
        } else {
            $_SESSION['sys_msg'] = "Update Failed"; $_SESSION['sys_msg_color'] = "red";
        }
    } else {
        $_SESSION['sys_msg'] = "Error: Field not editable"; $_SESSION['sys_msg_color'] = "red";
    }
    
    // --- RESTORE CONTEXT (Prevent Page Reset) ---
    if (isset($_POST['context_lab'])) {
        $_POST['submit_info'] = 1; // Trick the script into thinking "Get Info" was clicked
        $_POST['lab_name'] = $_POST['context_lab'];
        $_POST['system_number'] = isset($_POST['context_sys']) ? $_POST['context_sys'] : '';
    }
    // --------------------------------------------
}
// =============================================================

// =============================================================
// 0. AJAX HANDLER: FETCH LOG DETAILS FOR POPUP
// =============================================================
if (isset($_POST['fetch_log_details'])) {
    $req_lab = mysqli_real_escape_string($conn, $_POST['lab']);
    $req_sys = mysqli_real_escape_string($conn, $_POST['sys']); // Can be 'ALL'
    $req_type = $_POST['type']; // 'total' or 'active'

    // 1. Identify Columns
    $lab_col = 'lab_name';
    $sys_col = 'system_number';
    $email_col = '';
    $room_col = ''; 

    // Get dynamic column names
    $sec_res = mysqli_query($conn, "SELECT column_name, section_title, input_type FROM dynamic_sections ORDER BY display_order ASC");
    $dynamic_fields = [];
    
    while ($sec = mysqli_fetch_assoc($sec_res)) {
        if ($sec['section_title'] == 'Lab Name') { $lab_col = $sec['column_name']; continue; }
        
        if ($sec['section_title'] == 'System Number') { 
            $sys_col = $sec['column_name']; 
            continue; 
        }
        
        if ($sec['input_type'] == 'email') { $email_col = $sec['column_name']; continue; }
        
        if (strcasecmp($sec['section_title'], 'Room Number') == 0 || strcasecmp($sec['section_title'], 'Room No') == 0) { 
            $room_col = $sec['column_name'];
            continue; 
        }

        $dynamic_fields[] = $sec;
    }

    // 2. Build Query
    $sys_condition = "";
    if ($req_sys !== 'ALL') {
        $sys_condition = "AND main.`$sys_col` = '$req_sys'";
    }

    $sql = "SELECT main.*, c.created_at as orig_reported_date 
            FROM complaints_log main
            LEFT JOIN complaints c ON main.parent_id = c.id
            WHERE main.`$lab_col` = '$req_lab' 
            $sys_condition
            AND main.id = (
                SELECT MAX(sub.id)
                FROM complaints_log sub
                WHERE sub.parent_id = main.parent_id
            )";

    if ($req_type == 'active') {
        $sql .= " AND (main.status = 'Pending' OR main.status LIKE 'Partially Completed%')";
    }
    
    $sql .= " ORDER BY main.id DESC";

    $res = mysqli_query($conn, $sql);

    // 3. Output HTML Table Rows
    if ($res && mysqli_num_rows($res) > 0) {
        echo '<table class="log-table">';
        echo '<thead><tr>';
        
        if ($req_sys === 'ALL') { echo '<th>System No</th>'; }
        
        echo '<th>Issues</th> 
              <th>Email</th>
              <th>Status</th>
              <th>Reported Date</th>
              <th>Last Update</th>
              </tr></thead>';
        echo '<tbody>';
        
        while ($row = mysqli_fetch_assoc($res)) {
            
            $issue_parts = [];
            foreach ($dynamic_fields as $field) {
                $col_key = $field['column_name'];
                if (!empty($row[$col_key])) {
                    $issue_parts[] = "<strong>" . htmlspecialchars($field['section_title']) . ":</strong> " . htmlspecialchars($row[$col_key]);
                }
            }
            if (!empty($row['other_details'])) {
                $issue_parts[] = "<strong>Note:</strong> " . htmlspecialchars($row['other_details']);
            }
            $issues_display = implode("<br>", $issue_parts);
            if (empty($issues_display)) { $issues_display = "-"; }

            $sys_val = isset($row[$sys_col]) ? htmlspecialchars($row[$sys_col]) : '-';
            $email = ($email_col && isset($row[$email_col])) ? htmlspecialchars($row[$email_col]) : '-';
            
            $reported_raw = isset($row['orig_reported_date']) ? $row['orig_reported_date'] : $row['created_at'];
            $reported_date = date("d-M-Y h:i A", strtotime($reported_raw));
            
            $status_raw = $row['status'];
            $updated_date = "-"; 
            
            if ($status_raw == 'Completed' && !empty($row['issue_fixed_at']) && $row['issue_fixed_at'] != '0000-00-00 00:00:00') {
                $updated_date = date("d-M-Y h:i A", strtotime($row['issue_fixed_at']));
            } elseif (strpos($status_raw, 'Partially Completed') === 0 && !empty($row['partially_completed_at']) && $row['partially_completed_at'] != '0000-00-00 00:00:00') {
                $updated_date = date("d-M-Y h:i A", strtotime($row['partially_completed_at']));
            }
            
            $status_display = htmlspecialchars($status_raw);
            if ($status_raw == 'Pending') {
                $status_display = "<span class='status-pending'>Pending</span>";
            } elseif ($status_raw == 'Completed') {
                $status_display = "<span class='status-completed'>Completed</span>";
            } elseif (strpos($status_raw, 'Partially Completed') === 0) {
                $status_display = "<span class='status-partial'>" . htmlspecialchars($status_raw) . "</span>";
            }

            echo "<tr>";
            if ($req_sys === 'ALL') { echo "<td style='vertical-align:middle; text-align:center; font-weight:bold;'>$sys_val</td>"; }
            
            echo "<td style='vertical-align:middle; text-align:center; white-space: normal;'>$issues_display</td>";
            echo "<td style='vertical-align:middle; text-align:center;'>$email</td>";
            echo "<td style='vertical-align:middle; text-align:center;'>$status_display</td>";
            echo "<td style='vertical-align:middle; text-align:center; white-space: nowrap;'>$reported_date</td>";
            echo "<td style='vertical-align:middle; text-align:center; white-space: nowrap;'>$updated_date</td>";
            echo "</tr>";
        }
        echo '</tbody></table>';
    } else {
        echo '<p style="text-align:center; padding:20px; color:#666;">No records found.</p>';
    }
    exit(); 
}

// =============================================================
// END AJAX HANDLER
// =============================================================

$lab_details = null;
$show_results = false;
$is_system_search = false;
$search_msg = "";
$ordered_sections = []; 
$lab_personnel_data = []; 
$lab_col_map = []; 

// Variables for stats
$system_total_complaints = 0;
$system_active_complaints = 0;

$lab_total_complaints = 0;
$lab_active_complaints = 0;

// Variables for editing
$target_table_for_edit = "";
$lab_unit_id = 0; 

// =============================================================
// 1. FETCH LAB SYSTEMS MAPPING
// =============================================================
$lab_systems_data = [];
$master_query = "SELECT name, table_lab_name FROM lab_name";
$master_res = mysqli_query($conn, $master_query);

if ($master_res) {
    while ($row = mysqli_fetch_assoc($master_res)) {
        $l_name = $row['name'];
        $t_name = $row['table_lab_name'];
        $systems = [];

        if (!empty($t_name)) {
            $t_check = mysqli_query($conn, "SHOW TABLES LIKE '$t_name'");
            if (mysqli_num_rows($t_check) > 0) {
                $s_query = mysqli_query($conn, "SELECT system_number FROM `$t_name` ORDER BY id ASC");
                if ($s_query) {
                    while ($s_row = mysqli_fetch_assoc($s_query)) {
                        $systems[] = $s_row['system_number'];
                    }
                }
            }
        }
        $lab_systems_data[$l_name] = $systems;
    }
}
$json_lab_systems = json_encode($lab_systems_data);
// =============================================================

// --- Fetch Edit Permissions ---
$editable_lab_cols = [];
$e_lab_res = mysqli_query($conn, "SELECT edit_options FROM labs_edit_options");
while ($r = mysqli_fetch_assoc($e_lab_res)) { $editable_lab_cols[] = $r['edit_options']; }

$editable_sys_cols = [];
$e_sys_res = mysqli_query($conn, "SELECT edit_options FROM systems_edit_options");
while ($r = mysqli_fetch_assoc($e_sys_res)) { $editable_sys_cols[] = $r['edit_options']; }
// -------------------------------------

$pre_selected_system = "";

// Handle Form Submission
if (isset($_POST['submit_info'])) {
    $selected_lab = mysqli_real_escape_string($conn, $_POST['lab_name']);
    $selected_system = isset($_POST['system_number']) ? mysqli_real_escape_string($conn, $_POST['system_number']) : "";
    
    $pre_selected_system = $selected_system;

    // --- CHECK: IS SPECIFIC SYSTEM SELECTED? ---
    if (!empty($selected_system) && $selected_system != "None" && $selected_system != "Other") {
        $is_system_search = true;
        
        // 1. FETCH LAB UNIT INFO (Incharge / Programmer)
        $unit_sql = "SELECT * FROM labs_unit WHERE lab_name = '$selected_lab' LIMIT 1";
        $unit_res = mysqli_query($conn, $unit_sql);
        if ($unit_res && mysqli_num_rows($unit_res) > 0) {
            $unit_row = mysqli_fetch_assoc($unit_res);
            $lab_unit_id = $unit_row['id'];
            
            $meta_q = "SELECT column_name, section_title FROM labs_sections WHERE section_title LIKE '%Incharge%' OR section_title LIKE '%Programmer%'";
            $meta_res = mysqli_query($conn, $meta_q);
            
            while ($sec = mysqli_fetch_assoc($meta_res)) {
                $col_name = $sec['column_name'];
                if (isset($unit_row[$col_name])) {
                    $lab_personnel_data[$sec['section_title']] = $unit_row[$col_name];
                    $lab_col_map[$sec['section_title']] = $col_name;
                }
            }
        }

        // 2. FETCH SYSTEM DETAILS
        $tbl_query = "SELECT table_lab_name FROM lab_name WHERE name = '$selected_lab' LIMIT 1";
        $tbl_res = mysqli_query($conn, $tbl_query);
        
        if ($tbl_res && mysqli_num_rows($tbl_res) > 0) {
            $tbl_row = mysqli_fetch_assoc($tbl_res);
            $target_table = $tbl_row['table_lab_name'];
            $target_table_for_edit = $target_table; 
            
            if (!empty($target_table)) {
                $sql = "SELECT * FROM `$target_table` WHERE system_number = '$selected_system' LIMIT 1";
                
                try {
                    $result = mysqli_query($conn, $sql);
                    if ($result && mysqli_num_rows($result) > 0) {
                        $lab_details = mysqli_fetch_assoc($result);
                        
                        if (!empty($lab_personnel_data)) {
                            $lab_details = array_merge($lab_personnel_data, $lab_details);
                        }
                        
                        $lab_details['LAB_CONTEXT_NAME'] = $selected_lab; 
                        $lab_details['SYSTEM_CONTEXT_NO'] = $selected_system;
                        $show_results = true;

                        // Stats Calculation
                        $lab_col = 'lab_name'; 
                        $sys_col = 'system_number';
                        
                        $lc_q = mysqli_query($conn, "SELECT column_name FROM dynamic_sections WHERE section_title = 'Lab Name' LIMIT 1");
                        if(mysqli_num_rows($lc_q) > 0) { $lab_col = mysqli_fetch_assoc($lc_q)['column_name']; }

                        $sc_q = mysqli_query($conn, "SELECT column_name FROM dynamic_sections WHERE section_title = 'System Number' LIMIT 1");
                        if(mysqli_num_rows($sc_q) > 0) { $sys_col = mysqli_fetch_assoc($sc_q)['column_name']; }

                        $stats_sql = "SELECT 
                            COUNT(*) as total_complaints,
                            SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as total_pending,
                            SUM(CASE WHEN status LIKE 'Partially Completed%' THEN 1 ELSE 0 END) as total_partial
                        FROM complaints_log main
                        WHERE `$lab_col` = '$selected_lab' 
                        AND `$sys_col` = '$selected_system'
                        AND id = (
                            SELECT MAX(sub.id)
                            FROM complaints_log sub
                            WHERE sub.parent_id = main.parent_id
                        )";

                        $stats_res = mysqli_query($conn, $stats_sql);
                        if ($stats_res) {
                            $stats = mysqli_fetch_assoc($stats_res);
                            $system_total_complaints = $stats['total_complaints'] ?? 0;
                            $system_active_complaints = ($stats['total_pending'] ?? 0) + ($stats['total_partial'] ?? 0);
                        }

                    } else {
                        $search_msg = "System '$selected_system' not found in database for $selected_lab.";
                    }
                } catch (Exception $e) {
                    $search_msg = "Error accessing table '$target_table'. Table might not exist.";
                }
            } else {
                $search_msg = "Configuration Error: No database table linked to '$selected_lab'.";
            }
        } else {
            $search_msg = "Error: Lab '$selected_lab' not found in master list.";
        }
    } 
    else {
        // --- GENERAL LAB INFO LOGIC (Lab Name Selected, System Empty) ---
        $sec_query = "SELECT column_name, section_title FROM labs_sections ORDER BY display_order ASC";
        $sec_res = mysqli_query($conn, $sec_query);
        while($row = mysqli_fetch_assoc($sec_res)){
            $ordered_sections[] = $row;
        }

        $sql = "SELECT * FROM labs_unit WHERE lab_name = '$selected_lab' LIMIT 1";
        $result = mysqli_query($conn, $sql);
        
        if($result && mysqli_num_rows($result) > 0) {
            $lab_details = mysqli_fetch_assoc($result);
            $lab_details['LAB_CONTEXT_NAME'] = $selected_lab; 
            $show_results = true;

            $target_col = "";
            $col_search = mysqli_query($conn, "SELECT column_name FROM dynamic_sections WHERE section_title = 'Lab Name' LIMIT 1");
            if(mysqli_num_rows($col_search) > 0) {
                $r = mysqli_fetch_assoc($col_search);
                $target_col = $r['column_name'];
            } else {
                $col_search = mysqli_query($conn, "SELECT column_name FROM dynamic_sections WHERE section_title LIKE '%Lab%' LIMIT 1");
                if(mysqli_num_rows($col_search) > 0) {
                    $r = mysqli_fetch_assoc($col_search);
                    $target_col = $r['column_name'];
                }
            }

            if ($target_col != "") {
                $stats_sql = "SELECT 
                    COUNT(*) as total_complaints,
                    SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as total_pending,
                    SUM(CASE WHEN status LIKE 'Partially Completed%' THEN 1 ELSE 0 END) as total_partial
                FROM complaints_log main
                WHERE `$target_col` = '$selected_lab' 
                AND id = (
                    SELECT MAX(sub.id)
                    FROM complaints_log sub
                    WHERE sub.parent_id = main.parent_id
                )";

                try {
                    $stats_res = mysqli_query($conn, $stats_sql);
                    if ($stats_res) {
                        $stats = mysqli_fetch_assoc($stats_res);
                        $lab_total_complaints = $stats['total_complaints'] ?? 0;
                        $lab_active_complaints = ($stats['total_pending'] ?? 0) + ($stats['total_partial'] ?? 0);
                    }
                } catch (Exception $e) { $lab_total_complaints = 0; }
            }
        } else {
            $search_msg = "No records found for $selected_lab.";
        }
    }
}

include 'header.php';
?>

<style>
    .status-pending { color: #e67e22; font-weight: bold; background: #fff3e0; padding: 4px 8px; border-radius: 4px; font-size: 12px; display: inline-block; }
    .status-completed { color: #27ae60; font-weight: bold; background: #e8f5e9; padding: 4px 8px; border-radius: 4px; font-size: 12px; display: inline-block; }
    .status-partial { color: #f39c12; font-weight: bold; background: #fef9e7; padding: 4px 8px; border-radius: 4px; font-size: 12px; border: 1px solid #f39c12; display: inline-block; white-space: normal; }

    .modal-overlay { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); }
    .modal-content { background-color: #fefefe; margin: 5% auto; padding: 20px; border: 1px solid #888; width: 90%; max-width: 1000px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.2); position: relative; }
    .close-btn { color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer; }
    .close-btn:hover { color: black; }
    .stat-link { color: inherit; text-decoration: none; border-bottom: 1px dashed #999; cursor: pointer; transition: color 0.2s; }
    .stat-link:hover { color: var(--primary); border-bottom-style: solid; }
    .log-table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 14px; }
    .log-table th, .log-table td { border: 1px solid #ddd; padding: 12px; text-align: center; vertical-align: middle; }
    .log-table th { background-color: #f8f9fa; font-weight: bold; color: #495057; border-bottom: 2px solid #dee2e6; }
    .log-table tr:nth-child(even) { background-color: #f9f9f9; }
</style>


<div class="create-section-box" style="max-width:600px; margin:40px auto; text-align:left;">
    <h2 style="margin-top:0; color:var(--primary); text-align:center;">View Lab / System Information</h2>
    <hr>

    <?php if ($search_msg != ""): ?>
        <div style="text-align:center; margin-bottom:20px; padding:15px; background-color:#fce4ec; color:#c62828; border:1px solid #fadbd8; border-radius:4px; font-weight:bold;">
            <?php echo $search_msg; ?>
        </div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="form-group">
            <label style="display:block; font-weight:bold; margin-bottom:5px;">Lab Name <span style="color:red">*</span></label>
            <select name="lab_name" required style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px;">
                <option value="" disabled selected>-- Select Lab --</option>
                <?php
                $lab_query = "SELECT name FROM lab_name ORDER BY name ASC";
                $lab_res = mysqli_query($conn, $lab_query);
                if (mysqli_num_rows($lab_res) > 0) {
                    while ($row = mysqli_fetch_assoc($lab_res)) {
                        $lab_val = $row['name'];
                        $sel = (isset($_POST['lab_name']) && $_POST['lab_name'] == $lab_val) ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($lab_val) . "' $sel>" . htmlspecialchars($lab_val) . "</option>";
                    }
                } else {
                    $fallback_query = "SELECT DISTINCT lab_name FROM labs_unit";
                    $fb_res = mysqli_query($conn, $fallback_query);
                    while ($row = mysqli_fetch_assoc($fb_res)) {
                        $lab_val = $row['lab_name'];
                        echo "<option value='" . htmlspecialchars($lab_val) . "'>" . htmlspecialchars($lab_val) . "</option>";
                    }
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label style="display:block; font-weight:bold; margin-bottom:5px;">System Number</label>
            <select name="system_number" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px;">
                <option value="" selected>-- Select System --</option>
            </select>
        </div>

        <div style="text-align:center; margin-top:25px;">
            <input type="submit" name="submit_info" value="Get Info" class="btn-add" style="width:100%; padding:12px; cursor:pointer;">
        </div>
    </form>
        <p style="text-align:center; margin-top:15px;">
            <a href="labs_hub.php" class="btn-outline">&larr; Back to Hub</a>
        </p>
</div>

<?php if ($show_results && $lab_details): ?>
    <div class="create-section-box" style="max-width:600px; margin:20px auto; text-align:left; background:#fff; border-left:5px solid var(--primary);">
        
        <h3 style="margin-top:0; color:#333; margin-bottom:15px; border-bottom: 2px solid #eee; padding-bottom: 10px;">
            <?php if($is_system_search): ?>
                <div style="font-size:18px;">Lab Name: <span style="color:var(--primary);"><?php echo htmlspecialchars($lab_details['LAB_CONTEXT_NAME']); ?></span></div>
                <div style="font-size:18px; margin-top:5px;">System Number: <span style="color:var(--primary);"><?php echo htmlspecialchars($lab_details['SYSTEM_CONTEXT_NO']); ?></span></div>
            <?php else: ?>
                Details for: <span style="color:var(--primary);"><?php echo htmlspecialchars($lab_details['lab_name']); ?></span>
            <?php endif; ?>
        </h3>
        
        <table style="width:100%; border-collapse: collapse;">
            <?php 
            if (!$is_system_search && !empty($ordered_sections)) {
                // --- A. GENERAL LAB INFO ---
                foreach($ordered_sections as $sec) {
                    $col_key = $sec['column_name'];
                    $label = $sec['section_title'];
                    $col_val = isset($lab_details[$col_key]) ? $lab_details[$col_key] : '';
                    $display_val = ($col_val === null || $col_val === '') ? '<span style="color:#999;">-</span>' : htmlspecialchars($col_val);
                    
                    // Edit Button Check (LABS UNIT)
                    $edit_btn = "";
                    $js_val = addslashes($col_val);
                    
                    // Prep Context for JS
                    $js_lab_context = htmlspecialchars($selected_lab);
                    $js_sys_context = htmlspecialchars($selected_system);

                    if (in_array($col_key, $editable_lab_cols)) {
                        $row_id = $lab_details['id'];
                        $edit_btn = " <a href='javascript:void(0)' onclick='openEditModal(" . $row_id . ", \"$col_key\", \"$js_val\", \"labs_unit\", \"$js_lab_context\", \"$js_sys_context\")' title='Edit' style='text-decoration:none; color:var(--primary); font-size:14px; margin-left:5px; cursor:pointer;'>&#9998;</a>";
                    }
                    
                    echo "<tr style='border-bottom:1px solid #eee;'>";
                    echo "<td style='padding:12px 5px; font-weight:bold; color:#555; width:40%;'>$label</td>";
                    echo "<td style='padding:12px 5px; color:#333;'>$display_val $edit_btn</td>";
                    echo "</tr>";
                }
                
                $js_lab = htmlspecialchars($lab_details['LAB_CONTEXT_NAME']);
                echo "<tr style='border-bottom:1px solid #eee; background-color:#fcfcfc;'>";
                echo "<td style='padding:12px 5px; font-weight:bold; color:#2c3e50;'>Total Complaints</td>";
                echo "<td style='padding:12px 5px; font-weight:bold; color:#2c3e50;'><a class='stat-link' onclick='openLogModal(\"$js_lab\", \"ALL\", \"total\")'>$lab_total_complaints</a></td>";
                echo "</tr>";
                echo "<tr style='border-bottom:1px solid #eee; background-color:#fff3e0;'>";
                echo "<td style='padding:12px 5px; font-weight:bold; color:#e67e22;'>Active Complaints</td>";
                echo "<td style='padding:12px 5px; font-weight:bold; color:#d35400;'><a class='stat-link' onclick='openLogModal(\"$js_lab\", \"ALL\", \"active\")'>$lab_active_complaints</a></td>";
                echo "</tr>";

            } else {
                // --- B. SPECIFIC SYSTEM SPECS ---
                
                $sys_labels = [];
                $label_q = mysqli_query($conn, "SELECT column_name, section_title FROM systems_sections");
                while($l_row = mysqli_fetch_assoc($label_q)) {
                    $sys_labels[$l_row['column_name']] = $l_row['section_title'];
                }

                foreach($lab_details as $col_key => $col_val) {
                    if(in_array($col_key, ['id', 'LAB_CONTEXT_NAME', 'SYSTEM_CONTEXT_NO'])) continue;
                    
                    $label = isset($sys_labels[$col_key]) ? $sys_labels[$col_key] : ucwords(str_replace('_', ' ', $col_key));
                    $display_val = ($col_val === null || $col_val === '') ? '<span style="color:#999;">-</span>' : htmlspecialchars($col_val);
                    
                    // --- CHECK EDIT PERMISSIONS ---
                    $js_val = addslashes($col_val);
                    $edit_btn = "";
                    
                    // Prep Context for JS
                    $js_lab_context = htmlspecialchars($selected_lab);
                    $js_sys_context = htmlspecialchars($selected_system);
                    
                    // 1. Check System Columns (Dynamic Table)
                    if (in_array($col_key, $editable_sys_cols)) {
                        $edit_btn = " <a href='javascript:void(0)' onclick='openEditModal(" . $lab_details['id'] . ", \"$col_key\", \"$js_val\", \"$target_table_for_edit\", \"$js_lab_context\", \"$js_sys_context\")' title='Edit' style='text-decoration:none; color:var(--primary); font-size:14px; margin-left:5px; cursor:pointer;'>&#9998;</a>";
                    }
                    // 2. Check Lab Personnel Columns (Labs Unit Table)
                    elseif (isset($lab_col_map[$col_key])) {
                        $actual_col = $lab_col_map[$col_key];
                        if (in_array($actual_col, $editable_lab_cols)) {
                            // Use $lab_unit_id specifically for labs_unit updates
                            $edit_btn = " <a href='javascript:void(0)' onclick='openEditModal(" . $lab_unit_id . ", \"$actual_col\", \"$js_val\", \"labs_unit\", \"$js_lab_context\", \"$js_sys_context\")' title='Edit' style='text-decoration:none; color:var(--primary); font-size:14px; margin-left:5px; cursor:pointer;'>&#9998;</a>";
                        }
                    }
                    // -----------------------------
                    
                    echo "<tr style='border-bottom:1px solid #eee;'>";
                    echo "<td style='padding:12px 5px; font-weight:bold; color:#555; width:40%;'>$label</td>";
                    echo "<td style='padding:12px 5px; color:#333;'>$display_val $edit_btn</td>";
                    echo "</tr>";
                }

                $js_lab = htmlspecialchars($lab_details['LAB_CONTEXT_NAME']);
                $js_sys = htmlspecialchars($lab_details['SYSTEM_CONTEXT_NO']);
                
                echo "<tr style='border-bottom:1px solid #eee; background-color:#fcfcfc;'>";
                echo "<td style='padding:12px 5px; font-weight:bold; color:#2c3e50;'>Total Complaints Registered</td>";
                echo "<td style='padding:12px 5px; font-weight:bold; color:#2c3e50;'><a class='stat-link' onclick='openLogModal(\"$js_lab\", \"$js_sys\", \"total\")'>$system_total_complaints</a></td>";
                echo "</tr>";
                echo "<tr style='border-bottom:1px solid #eee; background-color:#fff3e0;'>";
                echo "<td style='padding:12px 5px; font-weight:bold; color:#e67e22;'>Active Complaints</td>";
                echo "<td style='padding:12px 5px; font-weight:bold; color:#d35400;'><a class='stat-link' onclick='openLogModal(\"$js_lab\", \"$js_sys\", \"active\")'>$system_active_complaints</a></td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
<?php endif; ?>

<div id="logModal" class="modal-overlay">
    <div class="modal-content">
        <span class="close-btn" onclick="closeLogModal()">&times;</span>
        <h3 id="modalTitle" style="margin-top:0; color:var(--primary);">Complaint Details</h3>
        <div id="modalBody" style="min-height:100px;">
            <p style="text-align:center; color:#777;">Loading...</p>
        </div>
    </div>
</div>

<style>
    .edit-modal { display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); }
    .edit-modal-content { background-color: #fff; margin: 15% auto; padding: 25px; border-radius: 8px; width: 350px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); position: relative; animation: fadeIn 0.3s; }
    .edit-close { float: right; font-size: 20px; font-weight: bold; cursor: pointer; color: #aaa; }
    .edit-close:hover { color: #000; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div id="editInfoModal" class="edit-modal">
    <div class="edit-modal-content">
        <span class="edit-close" onclick="closeEditModal()">&times;</span>
        <h3 style="margin-top:0; color:var(--primary);">Edit Value</h3>
        
        <form method="POST">
            <input type="hidden" name="target_id" id="edit_modal_id">
            <input type="hidden" name="target_col" id="edit_modal_col">
            <input type="hidden" name="target_table" id="edit_modal_table">
            
            <input type="hidden" name="context_lab" id="edit_context_lab">
            <input type="hidden" name="context_sys" id="edit_context_sys">
            
            <label style="display:block; text-align:left; margin-bottom:5px;">Update Data:</label>
            <input type="text" name="new_value" id="edit_modal_val" required style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;">
            
            <div style="margin-top:15px; text-align:right;">
                <button type="button" class="btn-cancel" onclick="closeEditModal()">Cancel</button>
                <input type="submit" name="update_info_field" value="Update" class="btn-add">
            </div>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Pass Data from PHP
    const labSystems = <?php echo $json_lab_systems ?: '{}'; ?>;
    const preSelectedSystem = "<?php echo $pre_selected_system; ?>";

    const labSelect = document.querySelector('select[name="lab_name"]');
    const sysSelect = document.querySelector('select[name="system_number"]');

    // 2. Define Populate Function
    function populateSystems(labName, selectedValue = null) {
        const systems = labSystems[labName];
        sysSelect.innerHTML = '<option value="" selected>-- Select System --</option>';

        if (systems && systems.length > 0) {
            systems.forEach(function(sysNum) {
                let opt = document.createElement('option');
                opt.value = sysNum;
                opt.textContent = sysNum;
                if (selectedValue && sysNum === selectedValue) {
                    opt.selected = true;
                }
                sysSelect.appendChild(opt);
            });
        } else {
            let opt = document.createElement('option');
            opt.value = "Other";
            opt.textContent = "No Systems Found / Other";
            sysSelect.appendChild(opt);
        }
    }

    if (labSelect && sysSelect) {
        labSelect.addEventListener('change', function() {
            populateSystems(this.value);
        });
        if (labSelect.value) {
            populateSystems(labSelect.value, preSelectedSystem);
        }
    }
});

// --- POPUP MODAL LOGIC (COMPLAINTS) ---
function openLogModal(lab, sys, type) {
    const modal = document.getElementById('logModal');
    const body = document.getElementById('modalBody');
    const title = document.getElementById('modalTitle');
    
    // Set Title
    let typeText = (type === 'active') ? "Active Complaints" : "All Registered Complaints";
    let subText = (sys === 'ALL') ? "All Systems" : sys;
    title.innerText = typeText + " (" + subText + ")";
    
    // Show Modal
    modal.style.display = "block";
    body.innerHTML = '<p style="text-align:center; color:#777;">Loading...</p>';

    // AJAX Call
    const formData = new FormData();
    formData.append('fetch_log_details', '1');
    formData.append('lab', lab);
    formData.append('sys', sys);
    formData.append('type', type);

    fetch('labs_info.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(html => {
        body.innerHTML = html;
    })
    .catch(error => {
        body.innerHTML = '<p style="color:red; text-align:center;">Error loading details.</p>';
    });
}

function closeLogModal() {
    document.getElementById('logModal').style.display = "none";
}

// --- ADDED: EDIT MODAL LOGIC ---
function openEditModal(id, col, val, tbl, lab, sys) {
    document.getElementById('edit_modal_id').value = id;
    document.getElementById('edit_modal_col').value = col;
    document.getElementById('edit_modal_table').value = tbl;
    document.getElementById('edit_modal_val').value = (val === '-') ? '' : val; 
    
    // Set Context
    document.getElementById('edit_context_lab').value = lab;
    document.getElementById('edit_context_sys').value = sys;
    
    document.getElementById('editInfoModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editInfoModal').style.display = 'none';
}
// ------------------------------

// Close if clicked outside
window.onclick = function(event) {
    const modal = document.getElementById('logModal');
    const editModal = document.getElementById('editInfoModal');
    if (event.target == modal) {
        modal.style.display = "none";
    }
    if (event.target == editModal) {
        editModal.style.display = "none";
    }
}
</script>

</div> </body>
</html>