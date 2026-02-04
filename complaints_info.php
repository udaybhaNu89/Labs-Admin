<?php
require 'auth_session.php';

// --- DASHBOARD ACTIONS ---

// 1. Delete Complaint (ONLY FROM MAIN TABLE)
if (isset($_GET['delete_complaint'])) {
    $id = intval($_GET['delete_complaint']);
    
    // Find parent_id
    $check_query = mysqli_query($conn, "SELECT parent_id, id FROM complaints WHERE id = $id");
    $row = mysqli_fetch_assoc($check_query);

    if ($row) {
        $target_parent = !empty($row['parent_id']) ? $row['parent_id'] : $row['id'];
        
        // Delete from COMPLAINTS only
        mysqli_query($conn, "DELETE FROM complaints WHERE parent_id = $target_parent");
        
        $_SESSION['sys_msg'] = "Complaint Chain Deleted"; $_SESSION['sys_msg_color'] = "green";
    } else {
        $_SESSION['sys_msg'] = "Error: Complaint not found"; $_SESSION['sys_msg_color'] = "red";
    }
    header("Location: complaints_info.php"); exit();
}

// 2. Mark as Completed (INSERT ROW TO BOTH TABLES)
if (isset($_GET['mark_complete'])) {
    $id = intval($_GET['mark_complete']);
    
    $comp_query = mysqli_query($conn, "SELECT * FROM complaints WHERE id = $id");
    $comp_data = mysqli_fetch_assoc($comp_query);
    
    if ($comp_data) {
        
        // --- EMAIL NOTIFICATION ---
        $col_query = mysqli_query($conn, "SELECT column_name FROM dynamic_sections WHERE input_type = 'email' LIMIT 1");
        $col_row = mysqli_fetch_assoc($col_query);
        
        if ($col_row && !empty($comp_data[$col_row['column_name']])) {
            $to_email = $comp_data[$col_row['column_name']];
            $fixed_time = date("Y-m-d H:i:s");
            
            $email_subject = "Update: Lab Issue Fixed";
            $email_body = "Your complaint regarding the Lab Issue has been resolved.\n\n";
            $email_body .= "--- Complaint Details ---\n";
            
            $sec_res = mysqli_query($conn, "SELECT * FROM dynamic_sections ORDER BY display_order ASC");
            while ($sec = mysqli_fetch_assoc($sec_res)) {
                $col = $sec['column_name'];
                $title = $sec['section_title'];
                $val = isset($comp_data[$col]) ? $comp_data[$col] : '-';
                $email_body .= "$title: $val\n";
            }
            $email_body .= "Other Details: " . $comp_data['other_details'] . "\n";
            $email_body .= "----------------\n";
            $email_body .= "Date Reported: " . $comp_data['created_at'] . "\n";
            $email_body .= "Status: Issue Fixed\n";
            $email_body .= "Issue Fixed At: " . $fixed_time . "\n";
            
            $headers = "From: no-reply@labsystem.com"; 
            @mail($to_email, $email_subject, $email_body, $headers);
        }
        // --- END EMAIL ---

        // PREPARE DATA FOR INSERTION
        $parent_id = !empty($comp_data['parent_id']) ? $comp_data['parent_id'] : $comp_data['id'];
        $orig_date = $comp_data['created_at'];
        $orig_details = mysqli_real_escape_string($conn, $comp_data['other_details']);

        $col_sql = "";
        $val_sql = "";
        $sec_query = mysqli_query($conn, "SELECT column_name FROM dynamic_sections");
        while ($sec = mysqli_fetch_assoc($sec_query)) {
            $col = $sec['column_name'];
            $val = isset($comp_data[$col]) ? mysqli_real_escape_string($conn, $comp_data[$col]) : '';
            $col_sql .= ", `$col`";
            $val_sql .= ", '$val'";
        }

        // A. Insert into COMPLAINTS
        $insert_sql = "INSERT INTO complaints (other_details, status, issue_fixed_at, parent_id, created_at $col_sql) 
                       VALUES ('$orig_details', 'Completed', NOW(), '$parent_id', '$orig_date' $val_sql)";
        mysqli_query($conn, $insert_sql);

        // B. Insert into COMPLAINTS_LOG
        $insert_log_sql = "INSERT INTO complaints_log (other_details, status, issue_fixed_at, parent_id, created_at $col_sql) 
                           VALUES ('$orig_details', 'Completed', NOW(), '$parent_id', '$orig_date' $val_sql)";
        mysqli_query($conn, $insert_log_sql);
    }

    $_SESSION['sys_msg'] = "Status Updated to Completed"; $_SESSION['sys_msg_color'] = "green";
    header("Location: complaints_info.php"); exit();
}

// 3. Mark as Partially Completed (INSERT ROW TO BOTH TABLES)
if (isset($_POST['submit_partial'])) {
    $id = intval($_POST['partial_id']);
    $desc = mysqli_real_escape_string($conn, $_POST['partial_desc']);
    
    $query = "SELECT * FROM complaints WHERE id = $id";
    $original = mysqli_fetch_assoc(mysqli_query($conn, $query));

    if ($original) {
        
        // --- EMAIL NOTIFICATION (PARTIAL) ---
        $col_query = mysqli_query($conn, "SELECT column_name FROM dynamic_sections WHERE input_type = 'email' LIMIT 1");
        $col_row = mysqli_fetch_assoc($col_query);
        
        if ($col_row && !empty($original[$col_row['column_name']])) {
            $to_email = $original[$col_row['column_name']];
            $partial_time = date("Y-m-d H:i:s");
            
            $email_subject = "Update: Lab Issue Partially Completed";
            $email_body = "Your complaint has been updated to 'Partially Completed'.\n\n";
            $email_body .= "--- Update Note ---\nAdmin Note: " . stripslashes($desc) . "\n\n";
            $email_body .= "--- Complaint Details ---\n";
            
            $sec_res = mysqli_query($conn, "SELECT * FROM dynamic_sections ORDER BY display_order ASC");
            while ($sec = mysqli_fetch_assoc($sec_res)) {
                $col = $sec['column_name'];
                $title = $sec['section_title'];
                $val = isset($original[$col]) ? $original[$col] : '-';
                $email_body .= "$title: $val\n";
            }
            $email_body .= "Original Details: " . $original['other_details'] . "\n";
            $email_body .= "----------------\n";
            $email_body .= "Date Reported: " . $original['created_at'] . "\n";
            $email_body .= "Status: Partially Completed\nUpdated At: " . $partial_time . "\n";
            
            $headers = "From: no-reply@labsystem.com"; 
            @mail($to_email, $email_subject, $email_body, $headers);
        }
        // --- END EMAIL ---

        // PREPARE DATA
        $parent_id = !empty($original['parent_id']) ? $original['parent_id'] : $original['id'];
        $orig_date = $original['created_at'];
        $status_text = "Partially Completed: " . $desc;
        $orig_details = mysqli_real_escape_string($conn, $original['other_details']);

        $col_sql = "";
        $val_sql = "";
        $sec_query = mysqli_query($conn, "SELECT column_name FROM dynamic_sections");
        while ($sec = mysqli_fetch_assoc($sec_query)) {
            $col = $sec['column_name'];
            $val = isset($original[$col]) ? mysqli_real_escape_string($conn, $original[$col]) : '';
            $col_sql .= ", `$col`";
            $val_sql .= ", '$val'";
        }
        
        // A. Insert into COMPLAINTS
        $insert_sql = "INSERT INTO complaints (other_details, status, partially_completed_at, parent_id, created_at $col_sql) 
                       VALUES ('$orig_details', '$status_text', NOW(), '$parent_id', '$orig_date' $val_sql)";
        mysqli_query($conn, $insert_sql);

        // B. Insert into COMPLAINTS_LOG
        $insert_log_sql = "INSERT INTO complaints_log (other_details, status, partially_completed_at, parent_id, created_at $col_sql) 
                           VALUES ('$orig_details', '$status_text', NOW(), '$parent_id', '$orig_date' $val_sql)";
        mysqli_query($conn, $insert_log_sql);

        $_SESSION['sys_msg'] = "Partial Status Recorded"; $_SESSION['sys_msg_color'] = "green";
    }
    header("Location: complaints_info.php"); exit();
}

include 'header.php';
?>

<style>
    .status-pending { color: #e67e22; font-weight: bold; background: #fff3e0; padding: 4px 8px; border-radius: 4px; font-size: 12px; display: inline-block; }
    .status-completed { color: #27ae60; font-weight: bold; background: #e8f5e9; padding: 4px 8px; border-radius: 4px; font-size: 12px; display: inline-block; }
    .status-partial { color: #f39c12; font-weight: bold; background: #fef9e7; padding: 4px 8px; border-radius: 4px; font-size: 12px; border: 1px solid #f39c12; display: inline-block; max-width: 250px; white-space: normal; text-align: left; }
    .btn-complete { background-color: #27ae60; color: white; padding: 6px 10px; border-radius: 4px; font-size: 11px; margin-right: 5px; transition: 0.2s; text-decoration: none; display: inline-block; cursor: pointer; border: none; }
    .btn-complete:hover { background-color: #219150; }
    .btn-partial { background-color: #f1c40f; color: #333; padding: 6px 10px; border-radius: 4px; font-size: 11px; transition: 0.2s; text-decoration: none; display: inline-block; cursor: pointer; border: none; font-weight: bold; }
    .btn-partial:hover { background-color: #d4ac0d; }
    .btn-delete { background-color: #e74c3c; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; }
    .btn-delete:hover { background-color: #c0392b; }
    .modal { display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); }
    .modal-content { background-color: #fff; margin: 15% auto; padding: 25px; border-radius: 8px; width: 400px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); position: relative; animation: slideDown 0.3s ease-out; }
    .close-modal { color: #aaa; float: right; font-size: 24px; font-weight: bold; cursor: pointer; }
    .close-modal:hover { color: #000; }
    @keyframes slideDown { from { transform: translateY(-20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>

<script>
    function openPartialModal(id) {
        document.getElementById('partial_id').value = id;
        document.getElementById('partialModal').style.display = 'block';
    }
    function closePartialModal() {
        document.getElementById('partialModal').style.display = 'none';
    }
    window.onclick = function(event) {
        if (event.target == document.getElementById('partialModal')) {
            closePartialModal();
        }
    }
</script>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1><strong>Complaints</strong></h1>
</div>

<?php
$sections = [];
$res = mysqli_query($conn, "SELECT * FROM dynamic_sections ORDER BY display_order ASC");
while ($row = mysqli_fetch_assoc($res)) { $sections[] = $row; }

// --- FILTERING LOGIC ---
$sql = "SELECT * FROM complaints c1
        WHERE c1.id = (
            SELECT MAX(c2.id)
            FROM complaints c2
            WHERE COALESCE(c2.parent_id, c2.id) = COALESCE(c1.parent_id, c1.id)
        )
        AND (partially_completed_at IS NULL OR issue_fixed_at IS NULL)
        ORDER BY c1.id DESC";

$result = mysqli_query($conn, $sql);
$num_complaints = mysqli_num_rows($result);
?>

<?php if ($num_complaints > 0): ?>
    <table>
        <tr>
            <?php foreach ($sections as $sec) { echo "<th>" . $sec['section_title'] . "</th>"; } ?>
            <th>Other Details</th>
            <th class="col-status">Status</th>
            <th>Status Actions</th>
            <th>Date Reported</th>
            <th>Partially Completed At</th>
            <th>Issue Fixed At</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <?php foreach ($sections as $sec) {
                    $col = $sec['column_name'];
                    $val = (!empty($row[$col])) ? $row[$col] : "-";
                    echo "<td>" . $val . "</td>";
                } ?>
                <td><?php echo (!empty($row['other_details'])) ? $row['other_details'] : "-"; ?></td>
                <td>
                    <?php 
                    $status_val = $row['status'];
                    if($status_val == 'Pending'): ?>
                        <span class='status-pending'>Pending</span>
                    <?php elseif($status_val == 'Completed'): ?>
                        <span class='status-completed'>Completed</span>
                    <?php elseif(strpos($status_val, 'Partially Completed') === 0): ?>
                        <span class='status-partial'><?php echo htmlspecialchars($status_val); ?></span>
                    <?php else: ?>
                        <?php echo $status_val; ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($row['status'] != 'Completed'): ?>
                        <div style="display: flex; flex-direction:column; align-items:center; gap: 5px;">
                            <a href='complaints_info.php?mark_complete=<?php echo $row['id']; ?>' class='btn-complete' title='Mark as Fully Completed'>✔ Complete</a>
                            <button type="button" class='btn-partial' onclick="openPartialModal(<?php echo $row['id']; ?>)">⚠ Partial</button>
                        </div>
                    <?php else: ?>
                        <span style="color:#aaa; font-size:12px;">-</span>
                    <?php endif; ?>
                </td>
                <td><?php echo $row['created_at']; ?></td>
                <td><?php echo (!empty($row['partially_completed_at'])) ? $row['partially_completed_at'] : "-"; ?></td>
                <td><?php echo (!empty($row['issue_fixed_at'])) ? $row['issue_fixed_at'] : "-"; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <div style="text-align:center; padding:40px; border:1px solid #eee; border-radius:8px; background:#fff; color:#777;">
        <h3>No Complaints Found</h3>
    </div>
<?php endif; ?>

<div id="partialModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="closePartialModal()">&times;</span>
        <h2 style="color:var(--primary); margin-top:0;">Partial Completion</h2>
        <p style="color:#666; font-size:14px;">This will create a new log entry. Please describe the partial work:</p>
        <form method="POST">
            <input type="hidden" name="partial_id" id="partial_id">
            <textarea name="partial_desc" rows="4" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px; font-family:inherit;" placeholder="Enter description..." required></textarea>
            <div style="margin-top:15px; text-align:right;">
                <button type="button" onclick="closePartialModal()" style="background:#ccc; color:#333; padding:8px 15px; border-radius:4px; border:none; cursor:pointer;">Cancel</button>
                <button type="submit" name="submit_partial" style="background:#f1c40f; color:#333; padding:8px 15px; border-radius:4px; border:none; cursor:pointer; font-weight:bold;">Update</button>
            </div>
        </form>
    </div>
</div>

</div>
</body>
</html>