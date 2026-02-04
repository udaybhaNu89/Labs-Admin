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
    $cols = ""; $vals = "";
    
    // Fetch dynamic sections
    $res = mysqli_query($conn, "SELECT column_name FROM labs_sections ORDER BY display_order ASC");
    
    if(mysqli_num_rows($res) > 0) {
        while ($sec = mysqli_fetch_assoc($res)) {
            $col = $sec['column_name'];
            $val = "";
            if (isset($_POST[$col])) { $val = mysqli_real_escape_string($conn, $_POST[$col]); }
            
            $cols .= ", `$col`";
            $vals .= ", '$val'";
        }
        
        $sql = "INSERT INTO labs_unit (id $cols) VALUES (NULL $vals)"; 
        
        // Try-Catch for Duplicate Errors
        try {
            if (mysqli_query($conn, $sql)) {
                $_SESSION['local_msg'] = "Form submitted successfully"; 
                $_SESSION['local_msg_type'] = "green";
            }
        } catch (mysqli_sql_exception $e) {
            // Check for Duplicate Entry Error (Code 1062)
            if ($e->getCode() == 1062) {
                $error_msg = $e->getMessage();
                preg_match("/Duplicate entry '(.*?)' for key '(.*?)'/", $error_msg, $matches);
                $dup_value = isset($matches[1]) ? $matches[1] : 'Value';
                $dup_key   = isset($matches[2]) ? $matches[2] : 'Field';
                
                // Find readable name
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
    } else {
        $_SESSION['local_msg'] = "Error: No sections defined."; 
        $_SESSION['local_msg_type'] = "red";
    }
    header("Location: labs_info_form.php"); exit();
}

// --- ACTION 2: EXCEL/CSV FILE IMPORT ---
if (isset($_POST['import_excel'])) {
    // 1. Check File
    if (!isset($_FILES['excel_file']) || $_FILES['excel_file']['error'] != 0) {
        $_SESSION['local_msg'] = "Error: Please upload a valid file."; 
        $_SESSION['local_msg_type'] = "red";
        header("Location: labs_info_form.php"); exit();
    }

    $filename = $_FILES['excel_file']['tmp_name'];
    $file_ext = pathinfo($_FILES['excel_file']['name'], PATHINFO_EXTENSION);

    // 2. Validate Extension (Allow CSV only)
    if (strtolower($file_ext) !== 'csv') {
        $_SESSION['local_msg'] = "Invalid File: Please save your Excel file as .CSV (Comma Separated Values) and try again."; 
        $_SESSION['local_msg_type'] = "red";
        header("Location: labs_info_form.php"); exit();
    }

    // --- FIX: ROBUST MAPPING HELPER ---
    // This removes spaces and symbols to ensure 'Building Name' matches 'buildingname' or 'Building_Name'
    function normalize_header($str) {
        return strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $str));
    }

    // 3. Prepare Column Mapping (Section Title -> Column Name)
    $db_map = [];
    $res = mysqli_query($conn, "SELECT section_title, column_name FROM labs_sections");
    while ($row = mysqli_fetch_assoc($res)) {
        // Use normalized key
        $key = normalize_header($row['section_title']);
        $db_map[$key] = $row['column_name'];
    }

    // 4. Parse CSV
    $handle = fopen($filename, "r");
    if ($handle !== FALSE) {
        $row_count = 0;
        $success_count = 0;
        $headers = [];
        $header_map = []; // CSV Index -> DB Column Name

        // Detect BOM (Byte Order Mark) which can break the first column
        // We will just trim standard whitespace in the loop below, but BOM removal is implicit in preg_replace inside normalize_header

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $row_count++;

            // ROW 1: HEADERS
            if ($row_count == 1) {
                $headers = $data;
                foreach ($headers as $index => $title) {
                    $clean_title = normalize_header($title); // Normalize CSV header
                    
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

            // DATA ROWS
            $cols_sql = [];
            $vals_sql = [];

            foreach ($data as $index => $cell_value) {
                if (isset($header_map[$index])) {
                    $db_col = $header_map[$index];
                    $safe_val = mysqli_real_escape_string($conn, trim($cell_value));
                    
                    $cols_sql[] = "`$db_col`";
                    $vals_sql[] = "'$safe_val'";
                }
            }

            if (!empty($cols_sql)) {
                $sql_insert = "INSERT INTO labs_unit (id, " . implode(',', $cols_sql) . ") VALUES (NULL, " . implode(',', $vals_sql) . ")";
                try {
                    mysqli_query($conn, $sql_insert);
                    $success_count++;
                } catch (Exception $e) {
                    // Skip duplicates silently or log count
                    continue; 
                }
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

// --- LOGIC TO IDENTIFY UNIQUE COLUMNS ---
$unique_list = "";
$map_res = mysqli_query($conn, "SELECT * FROM labs_sections");
$col_title_map = [];
while($r = mysqli_fetch_assoc($map_res)) {
    $col_title_map[$r['column_name']] = $r['section_title'];
}

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

    /* Import Section Styles */
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
        $res = mysqli_query($conn, "SELECT * FROM labs_sections ORDER BY display_order ASC");
        if (mysqli_num_rows($res) > 0) {
            while ($sec = mysqli_fetch_assoc($res)) {
                $col = $sec['column_name'];
                $type = $sec['input_type'];
                $title = $sec['section_title'];
                echo "<div class='form-group'>";
                echo "<label style='display:block; font-weight:bold; margin-bottom:5px; justify-content:left;'>$title <span style='color:#d93025'>*</span></label>";
                
                if ($type == 'numeric') { 
                    echo "<input type='number' name='$col' class='lab-input' required onblur='checkField(this)' oninput='clearError(this)'>"; 
                } elseif ($type == 'date') { 
                    echo "<div class='google-date-wrapper'>";
                    echo "<input type='date' name='$col' class='google-date-input' required onblur='checkField(this)' oninput='clearError(this)'>"; 
                    echo "</div>";
                } else { 
                    echo "<input type='text' name='$col' class='lab-input' required onblur='checkField(this)' oninput='clearError(this)'>"; 
                }
                echo "<div class='error-msg'>This field is required</div>";
                echo "</div>";
            }
        } else { 
            echo "<p style='text-align:center; color:#e74c3c;'>No input sections defined. Go to Form Management to add fields.</p>"; 
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