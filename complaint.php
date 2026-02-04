<?php
session_start();
include 'db.php';

// ==========================================
// CONFIGURATION
// ==========================================
$admin_email_address = "tonystark20201919@gmail.com"; 
// ==========================================

$message = "";
$msg_type = "";

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $msg_type = $_SESSION['msg_type'];
    unset($_SESSION['message']);
    unset($_SESSION['msg_type']);
}

// =================================================================
// 1. AUTO-HEAL DATABASE (Ensures Columns Exist in BOTH tables)
// =================================================================
// Checks dynamic_sections and adds missing columns to 'complaints' and 'complaints_log'
$sec_check_res = mysqli_query($conn, "SELECT column_name FROM dynamic_sections");
if ($sec_check_res) {
    while ($row = mysqli_fetch_assoc($sec_check_res)) {
        $col_name = $row['column_name'];
        
        // Check 'complaints' table
        $check_col_main = mysqli_query($conn, "SHOW COLUMNS FROM complaints LIKE '$col_name'");
        if (mysqli_num_rows($check_col_main) == 0) {
            mysqli_query($conn, "ALTER TABLE complaints ADD COLUMN `$col_name` VARCHAR(255) DEFAULT NULL");
        }

        // Check 'complaints_log' table
        $check_col_log = mysqli_query($conn, "SHOW COLUMNS FROM complaints_log LIKE '$col_name'");
        if (mysqli_num_rows($check_col_log) == 0) {
            mysqli_query($conn, "ALTER TABLE complaints_log ADD COLUMN `$col_name` VARCHAR(255) DEFAULT NULL");
        }
    }
}
// =================================================================

// --- 2. FETCH LAB CONFIGURATION (For Dynamic JS) ---
$lab_config_data = [];
$tbl_check = mysqli_query($conn, "SHOW TABLES LIKE 'lab_series_config'");
if (mysqli_num_rows($tbl_check) > 0) {
    $config_res = mysqli_query($conn, "SELECT * FROM lab_series_config");
    if ($config_res) {
        while ($row = mysqli_fetch_assoc($config_res)) {
            $lab_config_data[$row['lab_name']] = [
                'prefix'  => $row['prefix'],
                'start'   => (int)$row['start_no'],
                'end'     => (int)$row['end_no'],
                'padding' => (int)$row['padding']
            ];
        }
    }
}
$json_lab_config = json_encode($lab_config_data);
// ----------------------------------------------------

if (isset($_POST['submit_complaint'])) {
    $cols = ""; $vals = "";
    $valid = true; 
    
    // Initialize the email content
    $email_subject = "New Lab Complaint Submitted";
    $email_body = "A new complaint has been submitted via the Lab Admin System.\n\n--- Details ---\n";

    // Fetch dynamic sections
    $res = mysqli_query($conn, "SELECT * FROM dynamic_sections ORDER BY display_order ASC");
    while ($sec = mysqli_fetch_assoc($res)) {
        $col = $sec['column_name'];
        $title = $sec['section_title'];
        $type = $sec['input_type'];
        
        $raw_data = ""; 
        
        if (isset($_POST[$col])) {
            $raw_data = is_array($_POST[$col]) ? implode(", ", $_POST[$col]) : $_POST[$col];
        }

        // --- VALIDATION ---
        if ($type == 'email') {
            if (!empty($raw_data) && !preg_match("/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/", $raw_data)) {
                $message = "Error: Invalid email format for '$title'.";
                $msg_type = "error";
                $valid = false;
                break; 
            }
        }
        
        $email_body .= "$title: $raw_data\n";

        $db_data = mysqli_real_escape_string($conn, $raw_data);
        $cols .= ", `$col`"; 
        $vals .= ", '$db_data'";
    }
    
    if ($valid) {
        $raw_other = $_POST['other_details'];
        $db_other = mysqli_real_escape_string($conn, $raw_other);
        
        $email_body .= "Other Details: $raw_other\n";
        $email_body .= "----------------\n";
        $email_body .= "Date: " . date("Y-m-d H:i:s");

        // 1. INSERT INTO COMPLAINTS (Main Table)
        $sql = "INSERT INTO complaints (other_details, status, created_at $cols) VALUES ('$db_other', 'Pending', NOW() $vals)";
        
        try {
            if (mysqli_query($conn, $sql)) { 
                
                // 2. GET THE NEW ID & UPDATE PARENT_ID
                $new_id = mysqli_insert_id($conn);
                mysqli_query($conn, "UPDATE complaints SET parent_id = $new_id WHERE id = $new_id");

                // 3. INSERT INTO COMPLAINTS_LOG (Log Table)
                $sql_log = "INSERT INTO complaints_log (other_details, status, created_at, parent_id $cols) VALUES ('$db_other', 'Pending', NOW(), '$new_id' $vals)";
                mysqli_query($conn, $sql_log);

                // --- SEND EMAIL ---
                $headers = "From: no-reply@labsystem.com"; 
                @mail($admin_email_address, $email_subject, $email_body, $headers);
                // ------------------

                $_SESSION['message'] = "Your response has been recorded."; 
                $_SESSION['msg_type'] = "success";
                header("Location: complaint.php"); 
                exit(); 
            }
        } catch (mysqli_sql_exception $e) { 
            // Handle specific errors
            if (strpos($e->getMessage(), "Unknown column") !== false) {
                 $message = "Database Error: Missing column. Attempting auto-fix on next load. (" . $e->getMessage() . ")";
            } else {
                 $message = "Error: " . $e->getMessage(); 
            }
            $msg_type = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Report Issue</title>
    <style>
        :root { --primary: #009688; --primary-dark: #00796b; --bg-body: #f0f4f4; }
        body { background-color: var(--bg-body); font-family: 'Roboto', 'Segoe UI', Arial, sans-serif; margin: 0; padding: 30px 0; color: #202124; }
        .form-container { max-width: 640px; margin: 0 auto; }
        .form-card { background-color: #fff; border: 1px solid #dadce0; border-radius: 8px; padding: 24px; margin-bottom: 12px; position: relative; }
        .form-card:focus-within { border-left: 6px solid var(--primary); padding-left: 18px; }
        .form-header { border-top: 10px solid var(--primary); border-top-left-radius: 8px; border-top-right-radius: 8px; }
        h1 { font-size: 32px; font-weight: 400; margin: 0 0 10px 0; }
        p.desc { font-size: 14px; color: #5f6368; margin-top: 0; }
        label.question-title { font-size: 16px; font-weight: 500; display: block; margin-bottom: 15px; }
        .req { color: #d93025; margin-left: 4px; }
        select, textarea, input[type="text"], input[type="number"], input[type="email"], input[type="date"] { width: 100%; padding: 10px 0; border: none; border-bottom: 1px solid #e0e0e0; background: transparent; font-family: inherit; font-size: 14px; outline: none; transition: 0.3s; }
        select:focus, textarea:focus, input[type="text"]:focus, input[type="number"]:focus, input[type="email"]:focus, input[type="date"]:focus { border-bottom: 2px solid var(--primary); background-color: #fafafa; }
        .checkbox-group { display: flex; flex-direction: column; gap: 10px; }
        .checkbox-option { display: flex; align-items: center; font-size: 14px; cursor: pointer; }
        input[type="checkbox"] { margin-right: 15px; width: 18px; height: 18px; cursor: pointer; accent-color: var(--primary); }
        .form-footer { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; }
        .btn-submit { background-color: var(--primary); color: #fff; border: none; border-radius: 4px; padding: 10px 24px; font-size: 14px; font-weight: 500; cursor: pointer; }
        .btn-submit:hover { background-color: var(--primary-dark); box-shadow: 0 1px 2px rgba(0,0,0,0.2); }
        .top-nav { text-align: right; max-width: 640px; margin: 0 auto 15px auto; }
        .btn-outline { display: inline-block; padding: 8px 16px; border: 1px solid #ccc; background-color: white; color: #555; text-decoration: none; border-radius: 4px; font-size: 14px; font-weight: 500; transition: all 0.3s ease; }
        .btn-outline:hover { border-color: var(--primary); color: var(--primary); background-color: #f0fdfc; }
    </style>
</head>
<body>

    <div class="top-nav">
        <a href="index.php" class="btn-outline">&larr; Back to Home</a>
    </div>

    <div class="form-container">
        <div class="form-card form-header">
            <h1>Lab Issue Report</h1>
            <p class="desc">Submit your complaints regarding Labs, PCs, or Infrastructure.</p>
            <p style="color: #d93025; font-size: 12px;">* Required</p>
            <?php if ($message): ?>
                <div style="margin-top: 15px; color: <?php echo ($msg_type=='success')? 'var(--primary-dark)' : '#d93025'; ?>; font-weight: bold;">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
        </div>

        <form method="POST">
            <?php
            $res = mysqli_query($conn, "SELECT * FROM dynamic_sections ORDER BY display_order ASC");
            while ($sec = mysqli_fetch_assoc($res)) {
                $title = $sec['section_title']; 
                $col = $sec['column_name']; 
                $type = $sec['input_type'];
                $table = $col; 
                
                echo '<div class="form-card">';
                echo '<label class="question-title">' . $title . ' <span class="req">*</span></label>';
                
                if ($type == 'text') {
                    echo "<input type='text' name='$col' placeholder='Your answer' required>";
                }
                elseif ($type == 'number') {
                    echo "<input type='number' name='$col' placeholder='Your answer' required>";
                }
                elseif ($type == 'email') {
                    echo "<input type='email' name='$col' placeholder='Your email' required>";
                }
                elseif ($type == 'date') {
                    echo "<input type='date' name='$col' required>";
                }
                elseif ($type == 'textarea') {
                    echo "<textarea name='$col' placeholder='Your answer' required></textarea>";
                }
                else {
                    $check_table = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
                    if (mysqli_num_rows($check_table) > 0) {
                        $options = [];
                        $opts_query = mysqli_query($conn, "SELECT * FROM `$table`");
                        while ($r = mysqli_fetch_assoc($opts_query)) { $options[] = $r; }
                        usort($options, function($a, $b) { return strnatcasecmp($a['name'], $b['name']); });

                        if ($type == 'dropdown') {
                            echo "<select name='$col' required>";
                            echo "<option value='' disabled selected>Choose</option>";
                            foreach ($options as $r) { echo "<option value='".$r['name']."'>".$r['name']."</option>"; }
                            echo "</select>";
                        } else {
                            echo '<div class="checkbox-group">';
                            if (count($options) > 0) {
                                foreach ($options as $r) { 
                                    echo '<label class="checkbox-option"><input type="checkbox" name=\''.$col.'[]\' value=\''.$r['name'].'\'>'.$r['name'].'</label>';
                                }
                            } else { echo "<span style='color:#999; font-size:13px;'>No options found.</span>"; }
                            echo '</div>';
                        }
                    } else { echo "<p style='color:red; font-size:13px;'>Error: Configuration table '$table' not found.</p>"; }
                }
                echo '</div>';
            }
            ?>

            <div class="form-card">
                <label class="question-title">Other Details</label>
                <textarea name="other_details" rows="1" placeholder="Your answer"></textarea>
            </div>

            <div class="form-footer">
                <input type="submit" name="submit_complaint" value="Submit" class="btn-submit">
                <div style="font-size: 12px; color: var(--primary);">Clear form</div>
            </div>
        </form>
        
        <br><br>
        <center style="font-size: 12px; color: #5f6368;">
            This content is created by the Lab Admin.
        </center>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // Pass PHP Config to JS safely (Using the JSON from Database)
        const labConfig = <?php echo $json_lab_config ?: '{}'; ?>;

        const labSelect = document.querySelector('select[name="lab_name"]');
        const sysSelect = document.querySelector('select[name="system_number"]');

        if (labSelect && sysSelect) {
            labSelect.addEventListener('change', function() {
                const selectedLab = this.value;
                const config = labConfig[selectedLab];

                // Reset System Dropdown
                sysSelect.innerHTML = '<option value="" disabled selected>Select System</option>';

                if (config) {
                    // Generate Series
                    for (let i = config.start; i <= config.end; i++) {
                        let numStr = i.toString().padStart(config.padding, '0'); 
                        let code = config.prefix + numStr;
                        
                        let opt = document.createElement('option');
                        opt.value = code;
                        opt.textContent = code;
                        sysSelect.appendChild(opt);
                    }
                } else {
                    // Fallback
                    let opt = document.createElement('option');
                    opt.value = "Other";
                    opt.textContent = "Other / Not Listed";
                    sysSelect.appendChild(opt);
                }
            });
        }
    });
    </script>

</body>
</html>