<?php
require 'auth_session.php';

// Check for local message from session
$local_msg = "";
$local_msg_type = "";
if (isset($_SESSION['local_msg'])) {
    $local_msg = $_SESSION['local_msg'];
    $local_msg_type = $_SESSION['local_msg_type'];
    unset($_SESSION['local_msg']);
    unset($_SESSION['local_msg_type']);
}

// --- ACTION 1: MANUAL FORM SUBMISSION ---
if (isset($_POST['submit_labs_form'])) {
    
    // 1. Capture Lab Name for Table Generation
    $lab_name = isset($_POST['lab_name']) ? $_POST['lab_name'] : '';
    
    // 2. Generate lab_name_table (e.g., "Nikson Lab" -> "nikson_lab")
    $raw_name = $_POST['lab_name'];
    $generated_table_name = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $raw_name));
    $generated_table_name = trim($generated_table_name, '_');

    // My options
    $lab_code = $_POST['lab_code'];
    $sys_present = $_POST['no_of_systems_present'];
    $end_count = strlen($sys_present);

    // 3. Prepare Columns & Values dynamically
    $cols = ""; 
    $vals = "";
    
    $res = mysqli_query($conn, "SELECT column_name FROM labs_sections ORDER BY display_order ASC");
    
    if(mysqli_num_rows($res) > 0) {
        while ($sec = mysqli_fetch_assoc($res)) {
            $col = $sec['column_name'];
            $val = "";

            // Special Logic: Auto-generate lab_name_table value
            if ($col == 'lab_name_table') {
                $val = $generated_table_name;
            } 
            // Standard Fields
            else {
                if (isset($_POST[$col])) { 
                    $val = mysqli_real_escape_string($conn, $_POST[$col]); 
                    
                    // Force numeric type for capacity/present to ensure 0 is saved if empty/zero
                    if ($col == 'no_of_system_capacity' || $col == 'no_of_systems_present') {
                        $val = (int)$val; 
                    }
                }
            }
            
            $cols .= ", `$col`";
            $vals .= ", '$val'";
        }
    }
        
    // 4. Construct Final Insert Query
    $sql = "INSERT INTO labs_unit (id $cols) VALUES (NULL $vals)"; 
    
    try {        
        if (mysqli_query($conn, $sql)) {
            
            // --- Automatically Create the Table for this Lab ---
            $last_id = mysqli_insert_id($conn);
            
            // 1. Fetch the generated table name from the database
            mysqli_query($conn, "UPDATE labs_unit SET lab_name_table = '$generated_table_name' WHERE id = '$last_id'");
            mysqli_query($conn, "INSERT INTO lab_series_config (lab_name, prefix, start_no, end_no, padding) VALUES ('$lab_name', '$lab_code', 1, $sys_present, $end_count)");
            $fetch_tbl_q = mysqli_query($conn, "SELECT lab_name_table FROM labs_unit WHERE id = '$last_id'");
            if ($row = mysqli_fetch_assoc($fetch_tbl_q)) {
                $tbl_lab_name = $row['lab_name_table'];
                
                if (!empty($tbl_lab_name)) {
                    // 2. Create the Table
                    $create_lab_sql = "CREATE TABLE IF NOT EXISTS `$tbl_lab_name` (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        system_number TEXT NOT NULL,
                        os TEXT, config_details TEXT,
                        UNIQUE(system_number(255)))"; 
                    mysqli_query($conn, $create_lab_sql);
                }
            }
            
            $_SESSION['local_msg'] = "Lab Created Successfully"; 
            $_SESSION['local_msg_type'] = "green";
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            $error_msg = $e->getMessage();
            preg_match("/Duplicate entry '(.*?)' for key '(.*?)'/", $error_msg, $matches);
            $dup_value = isset($matches[1]) ? $matches[1] : 'Value';
            $dup_key   = isset($matches[2]) ? $matches[2] : 'Field';
            
            // Nice Name Mapping
            $nice_name = $dup_key;
            $name_check = mysqli_query($conn, "SELECT section_title, column_name FROM labs_sections");
            while ($row = mysqli_fetch_assoc($name_check)) {
                if (strpos($dup_key, $row['column_name']) !== false) {
                    $nice_name = $row['section_title'];
                    break;
                }
            }
            
            $_SESSION['local_msg'] = "$nice_name already has '$dup_value' value"; 
            $_SESSION['local_msg_type'] = "red";
        } else {
            $_SESSION['local_msg'] = "Error: " . $e->getMessage(); 
            $_SESSION['local_msg_type'] = "red";
        }
    }
    
    header("Location: labs_info_form.php"); exit();
}

// --- ACTION 2: EXCEL/CSV FILE IMPORT ---
if (isset($_POST['import_excel'])) {
    if (!isset($_FILES['excel_file']) || $_FILES['excel_file']['error'] != 0) {
        $_SESSION['local_msg'] = "Error: Please upload a valid file."; 
        $_SESSION['local_msg_type'] = "red";
        header("Location: labs_info_form.php"); exit();
    }

    $filename = $_FILES['excel_file']['tmp_name'];
    $file_ext = pathinfo($_FILES['excel_file']['name'], PATHINFO_EXTENSION);

    if (strtolower($file_ext) !== 'csv') {
        $_SESSION['local_msg'] = "Invalid File: Please save your Excel file as .CSV (Comma Separated Values) and try again."; 
        $_SESSION['local_msg_type'] = "red";
        header("Location: labs_info_form.php"); exit();
    }

    function normalize_header($str) {
        return strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $str));
    }

    $db_map = [];
    $res = mysqli_query($conn, "SELECT section_title, column_name FROM labs_sections");
    while ($row = mysqli_fetch_assoc($res)) {
        $key = normalize_header($row['section_title']);
        $db_map[$key] = $row['column_name'];
    }
    // Ensure defaults are mapped if they aren't in the DB sections
    $db_map['labname'] = 'lab_name';
    $db_map['labcode'] = 'lab_code';
    $db_map['roomnumber'] = 'room_no';
    $db_map['roomno'] = 'room_no';
    $db_map['systemcapacity'] = 'no_of_system_capacity';
    $db_map['systemspresent'] = 'no_of_systems_present';

    $handle = fopen($filename, "r");
    if ($handle !== FALSE) {
        $row_count = 0;
        $success_count = 0;
        $header_map = []; 

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $row_count++;

            if ($row_count == 1) {
                foreach ($data as $index => $title) {
                    $clean_title = normalize_header($title);
                    if (isset($db_map[$clean_title])) {
                        $header_map[$index] = $db_map[$clean_title];
                    }
                }
                
                if (empty($header_map)) {
                    $_SESSION['local_msg'] = "Error: No matching columns found. Please check your CSV headers."; 
                    $_SESSION['local_msg_type'] = "red";
                    fclose($handle);
                    header("Location: labs_info_form.php"); exit();
                }
                continue;
            }

            $cols_sql = [];
            $vals_sql = [];
            $current_lab_name = "";

            foreach ($data as $index => $cell_value) {
                if (isset($header_map[$index])) {
                    $db_col = $header_map[$index];
                    $safe_val = mysqli_real_escape_string($conn, trim($cell_value));
                    
                    if($db_col == 'lab_name') $current_lab_name = $safe_val;

                    $cols_sql[] = "`$db_col`";
                    $vals_sql[] = "'$safe_val'";
                }
            }

            if(!empty($current_lab_name)) {
                $gen_tbl = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $current_lab_name));
                $gen_tbl = trim($gen_tbl, '_');
                $cols_sql[] = "`lab_name_table`";
                $vals_sql[] = "'$gen_tbl'";
            }

            if (!empty($cols_sql)) {
                $sql_insert = "INSERT INTO labs_unit (id, " . implode(',', $cols_sql) . ") VALUES (NULL, " . implode(',', $vals_sql) . ")";
                try {
                    if(mysqli_query($conn, $sql_insert)) {
                        $success_count++;
                        if(!empty($current_lab_name)) {
                            $gen_tbl = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $current_lab_name));
                            $gen_tbl = trim($gen_tbl, '_');
                            $create_lab_sql = "CREATE TABLE IF NOT EXISTS `$gen_tbl` (
                                id INT AUTO_INCREMENT PRIMARY KEY,
                                system_number TEXT NOT NULL,
                                os TEXT, config_details TEXT,
                                UNIQUE(system_number(255)))"; 
                            mysqli_query($conn, $create_lab_sql);
                        }
                    }
                } catch (Exception $e) { continue; }
            }
        }
        fclose($handle);
        
        $_SESSION['local_msg'] = "Import Complete: $success_count records added."; 
        $_SESSION['local_msg_type'] = "green";
    } else {
        $_SESSION['local_msg'] = "Error reading file."; 
        $_SESSION['local_msg_type'] = "red";
    }
    header("Location: labs_info_form.php"); exit();
}

include 'header.php';

$unique_list = "";
$map_res = mysqli_query($conn, "SELECT * FROM labs_sections");
$col_title_map = [];
while($r = mysqli_fetch_assoc($map_res)) {
    $col_title_map[$r['column_name']] = $r['section_title'];
}
$col_title_map['lab_code'] = 'Lab Code';

$idx_res = mysqli_query($conn, "SHOW INDEXES FROM labs_unit WHERE Non_unique = 0 AND Key_name != 'PRIMARY'");
$found_unique = [];
while($row = mysqli_fetch_assoc($idx_res)) {
    $col = $row['Column_name'];
    if(isset($col_title_map[$col])) {
        $found_unique[] = $col_title_map[$col];
    }
}
$found_unique = array_unique($found_unique);
if(!empty($found_unique)) {
    $unique_list = implode(", ", $found_unique);
}
?>

<style>
    .form-group { margin-bottom: 20px; }
    .error-msg { color: #d93025; font-size: 12px; margin-top: 5px; display: none; align-items: center; gap: 5px; }
    .error-msg::before { content: "⚠ "; font-size: 14px; }
    .input-invalid { border-bottom: 2px solid #d93025 !important; }
    .wrapper-invalid { border-bottom: 2px solid #d93025 !important; }
    
    .msg-box { padding: 10px; margin-top: 15px; margin-bottom: 15px; border-radius: 4px; font-weight: bold; text-align: center; font-size: 14px; }
    .msg-green { background-color: #e8f5e9; color: #27ae60; border: 1px solid #c8e6c9; }
    .msg-red { background-color: #fce4ec; color: #c0392b; border: 1px solid #fadbd8; }

    .import-box { background-color: #f0f4c3; border: 1px dashed #c0ca33; padding: 15px; margin-bottom: 25px; border-radius: 6px; text-align: center; display: none; }
    .btn-file { background-color: #fff; border: 1px solid #ccc; padding: 5px; width: 100%; border-radius: 4px; }
</style>

<script>
    function toggleImport() {
        var x = document.getElementById("importSection");
        if (x.style.display === "none") { x.style.display = "block"; } else { x.style.display = "none"; }
    }

    function checkField(input) {
        const formGroup = input.closest('.form-group');
        const errorMsg = formGroup.querySelector('.error-msg');
        const dateWrapper = formGroup.querySelector('.google-date-wrapper');
        if (!input.value.trim()) {
            errorMsg.style.display = 'flex';
            if(dateWrapper) dateWrapper.classList.add('wrapper-invalid');
            else input.classList.add('input-invalid');
        }
    }
    function clearError(input) {
        const formGroup = input.closest('.form-group');
        const errorMsg = formGroup.querySelector('.error-msg');
        const dateWrapper = formGroup.querySelector('.google-date-wrapper');
        errorMsg.style.display = 'none';
        if(dateWrapper) dateWrapper.classList.remove('wrapper-invalid');
        else input.classList.remove('input-invalid');
    }
    function validateOnSubmit(event) {
        const inputs = document.querySelectorAll('.lab-input, .google-date-input');
        let isValid = true;
        inputs.forEach(input => {
            // Check all inputs since all are required
            if (!input.value.trim()) {
                checkField(input);
                isValid = false;
            }
        });
        if (!isValid) {
            event.preventDefault();
            alert("Please fill in all required fields.");
        }
    }
</script>

<div class="create-section-box" style="max-width:600px; margin:0 auto; text-align:left;">
    
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h2 style="margin:0; color:var(--primary);">Add Lab Information</h2>
        <button onclick="toggleImport()" class="btn-toggle" style="background:#8bc34a; font-size:12px;">Import from Excel/CSV</button>
    </div>

    <?php if ($local_msg != ""): ?>
        <div class="msg-box <?php echo ($local_msg_type == 'green') ? 'msg-green' : 'msg-red'; ?>">
            <?php echo $local_msg; ?>
        </div>
    <?php endif; ?>
    
    <hr>
    
    <div id="importSection" class="import-box">
        <h3 style="margin-top:0; color:#555;">Bulk Import System Details</h3>
        <p style="font-size:13px; color:#777; margin-bottom:15px; line-height:1.6;">
            Save your Excel file as <strong>.CSV (Comma delimited)</strong>.<br>
            Ensure the first row contains field names matching your form.
            
            <?php if ($unique_list != ""): ?>
                <br>
                <span style="color:#d93025; background:#fff; padding:2px 6px; border-radius:4px; border:1px solid #ef9a9a; display:inline-block; margin-top:5px;">
                    <strong>Important:</strong> The following fields must contain UNIQUE values:<br>
                    <strong><?php echo $unique_list; ?></strong>
                </span>
            <?php endif; ?>
        </p>
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="excel_file" class="btn-file" accept=".csv" required>
            <div style="margin-top:10px;">
                <input type="submit" name="import_excel" value="Upload & Import" class="btn-add" style="background:#558b2f;">
            </div>
        </form>
    </div>

    <form method="POST" id="labsForm" onsubmit="validateOnSubmit(event)">
        <?php
        // Fetch ALL sections ordered by display_order
        $res = mysqli_query($conn, "SELECT * FROM labs_sections ORDER BY display_order ASC");
        
        if (mysqli_num_rows($res) > 0) {
            while ($sec = mysqli_fetch_assoc($res)) {
                $col = $sec['column_name'];
                $type = $sec['input_type'];
                $title = $sec['section_title'];
                
                // Hide Internal Fields
                if ($col == 'lab_name_table' || $col == 'table_lab_name') continue;

                // Enforce REQUIRED on ALL visible fields
                $req_attr = "required";

                echo "<div class='form-group'>";
                echo "<label style='display:block; font-weight:bold; margin-bottom:5px; justify-content:left;'>$title";
                echo " <span style='color:#d93025'>*</span>"; // Always show asterisk
                echo "</label>";
                
                if ($type == 'numeric') { 
                    echo "<input type='number' name='$col' class='lab-input' $req_attr onblur='checkField(this)' oninput='clearError(this)'>"; 
                } elseif ($type == 'date') { 
                    echo "<div class='google-date-wrapper'>";
                    echo "<input type='date' name='$col' class='google-date-input' $req_attr onblur='checkField(this)' oninput='clearError(this)'>"; 
                    echo "</div>";
                } else { 
                    echo "<input type='text' name='$col' class='lab-input' $req_attr onblur='checkField(this)' oninput='clearError(this)'>"; 
                }
                
                // Always show error message div
                echo "<div class='error-msg'>This field is required</div>";
                echo "</div>";
            }
        } else { 
            echo "<p style='text-align:center; color:#e74c3c;'>No fields defined. Please configure them in Labs Manager.</p>"; 
        }
        ?>
        
        <div style="text-align:center; margin-top:20px;">
            <input type="submit" id="btnSubmitLabs" name="submit_labs_form" value="Save Lab Info" class="btn-add" style="width:100%; padding:12px; cursor:pointer;">
        </div>
    </form>
    
    <p style="text-align:center; margin-top:15px;">
        <a href="labs_hub.php" class="btn-outline">&larr; Back to Hub</a>
    </p>
</div>

</div>
</body>
</html>