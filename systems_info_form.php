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

// --- HELPER: Get Table Name from Lab ID/Name ---
function getLabTableName($conn, $lab_id_or_name) {
    // Expecting ID or Name from value. Let's assume the value passed is the ID for safety, 
    // or the Name if that's how options are built. 
    // To be consistent with labs_info_form logic, let's look it up.
    
    $safe_val = mysqli_real_escape_string($conn, $lab_id_or_name);
    // Check if it matches ID
    $q = mysqli_query($conn, "SELECT table_lab_name FROM lab_name WHERE id = '$safe_val' OR name = '$safe_val' LIMIT 1");
    if ($row = mysqli_fetch_assoc($q)) {
        return $row['table_lab_name'];
    }
    return false;
}

// --- ACTION 1: MANUAL FORM SUBMISSION ---
if (isset($_POST['submit_systems_form'])) {
    $target_lab = $_POST['target_lab_id'];
    $table_name = getLabTableName($conn, $target_lab);

    if ($table_name) {
        $cols = ""; $vals = "";
        
        // Fetch dynamic sections for systems
        $res = mysqli_query($conn, "SELECT column_name FROM systems_sections ORDER BY display_order ASC");
        
        if(mysqli_num_rows($res) > 0) {
            while ($sec = mysqli_fetch_assoc($res)) {
                $col = $sec['column_name'];
                $val = "";
                if (isset($_POST[$col])) { $val = mysqli_real_escape_string($conn, $_POST[$col]); }
                
                $cols .= ", `$col`";
                $vals .= ", '$val'";
            }
            
            // Construct Insert for the specific Lab Table
            $sql = "INSERT INTO `$table_name` (id $cols) VALUES (NULL $vals)"; 
            
            try {
                if (mysqli_query($conn, $sql)) {
                    $_SESSION['local_msg'] = "System added successfully to lab."; 
                    $_SESSION['local_msg_type'] = "green";
                }
            } catch (mysqli_sql_exception $e) {
                // Check for Duplicate Entry
                if ($e->getCode() == 1062) {
                    $error_msg = $e->getMessage();
                    preg_match("/Duplicate entry '(.*?)' for key '(.*?)'/", $error_msg, $matches);
                    $dup_value = isset($matches[1]) ? $matches[1] : 'Value';
                    $_SESSION['local_msg'] = "Error: '$dup_value' already exists in this lab."; 
                    $_SESSION['local_msg_type'] = "red";
                } else {
                    $_SESSION['local_msg'] = "Database Error: " . $e->getMessage(); 
                    $_SESSION['local_msg_type'] = "red";
                }
            }
        } else {
            $_SESSION['local_msg'] = "Error: No form sections configured."; 
            $_SESSION['local_msg_type'] = "red";
        }
    } else {
        $_SESSION['local_msg'] = "Error: Selected Lab not found or not configured."; 
        $_SESSION['local_msg_type'] = "red";
    }
    header("Location: systems_info_form.php"); exit();
}

// --- ACTION 2: EXCEL/CSV FILE IMPORT ---
if (isset($_POST['import_excel'])) {
    // 1. Check File & Lab
    if (!isset($_FILES['excel_file']) || $_FILES['excel_file']['error'] != 0) {
        $_SESSION['local_msg'] = "Error: Please upload a valid file."; 
        $_SESSION['local_msg_type'] = "red";
        header("Location: systems_info_form.php"); exit();
    }
    
    $target_lab = $_POST['target_lab_id'];
    $table_name = getLabTableName($conn, $target_lab);
    
    if (!$table_name) {
        $_SESSION['local_msg'] = "Error: Invalid Lab selected."; 
        $_SESSION['local_msg_type'] = "red";
        header("Location: systems_info_form.php"); exit();
    }

    $filename = $_FILES['excel_file']['tmp_name'];
    $file_ext = pathinfo($_FILES['excel_file']['name'], PATHINFO_EXTENSION);

    // 2. Validate Extension
    if (strtolower($file_ext) !== 'csv') {
        $_SESSION['local_msg'] = "Invalid File: Please use .CSV format."; 
        $_SESSION['local_msg_type'] = "red";
        header("Location: systems_info_form.php"); exit();
    }

    function normalize_header($str) {
        return strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $str));
    }

    // 3. Map Headers (Section Title -> Column Name)
    $db_map = [];
    $res = mysqli_query($conn, "SELECT section_title, column_name FROM systems_sections");
    while ($row = mysqli_fetch_assoc($res)) {
        $key = normalize_header($row['section_title']);
        $db_map[$key] = $row['column_name'];
    }

    // 4. Parse CSV
    $handle = fopen($filename, "r");
    if ($handle !== FALSE) {
        $row_count = 0;
        $success_count = 0;
        $header_map = []; 

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $row_count++;

            // HEADERS
            if ($row_count == 1) {
                foreach ($data as $index => $title) {
                    $clean = normalize_header($title);
                    if (isset($db_map[$clean])) {
                        $header_map[$index] = $db_map[$clean];
                    }
                }
                if (empty($header_map)) {
                    $_SESSION['local_msg'] = "Error: No matching columns found in CSV."; 
                    $_SESSION['local_msg_type'] = "red";
                    fclose($handle);
                    header("Location: systems_info_form.php"); exit();
                }
                continue;
            }

            // DATA
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
                $sql_insert = "INSERT INTO `$table_name` (id, " . implode(',', $cols_sql) . ") VALUES (NULL, " . implode(',', $vals_sql) . ")";
                try {
                    mysqli_query($conn, $sql_insert);
                    $success_count++;
                } catch (Exception $e) { continue; }
            }
        }
        fclose($handle);
        $_SESSION['local_msg'] = "Import Complete: $success_count systems added."; 
        $_SESSION['local_msg_type'] = "green";
    } else {
        $_SESSION['local_msg'] = "Error reading file."; 
        $_SESSION['local_msg_type'] = "red";
    }
    header("Location: systems_info_form.php"); exit();
}

include 'header.php';

// Fetch Labs for Dropdown
$lab_options = "";
$lab_res = mysqli_query($conn, "SELECT id, name FROM lab_name ORDER BY name ASC");
while ($row = mysqli_fetch_assoc($lab_res)) {
    $lab_options .= "<option value='".$row['id']."'>".$row['name']."</option>";
}
?>

<style>
    .form-group { margin-bottom: 20px; }
    .error-msg { color: #d93025; font-size: 12px; margin-top: 5px; display: none; align-items: center; gap: 5px; }
    .input-invalid { border-bottom: 2px solid #d93025 !important; }
    .msg-box { padding: 10px; margin-top: 15px; margin-bottom: 15px; border-radius: 4px; font-weight: bold; text-align: center; font-size: 14px; }
    .msg-green { background-color: #e8f5e9; color: #27ae60; border: 1px solid #c8e6c9; }
    .msg-red { background-color: #fce4ec; color: #c0392b; border: 1px solid #fadbd8; }
    .import-box { background-color: #e3f2fd; border: 1px dashed #2196f3; padding: 15px; margin-bottom: 25px; border-radius: 6px; text-align: center; display: none; }
    .btn-file { background-color: #fff; border: 1px solid #ccc; padding: 5px; width: 100%; border-radius: 4px; }
    .lab-select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; background: #fff; }
</style>

<script>
    function toggleImport() {
        var x = document.getElementById("importSection");
        if (x.style.display === "none") { x.style.display = "block"; } else { x.style.display = "none"; }
    }
    function validateOnSubmit(event) {
        const inputs = document.querySelectorAll('.lab-input');
        let isValid = true;
        inputs.forEach(input => {
            if (!input.value.trim()) {
                input.classList.add('input-invalid');
                isValid = false;
            }
        });
        if (!isValid) {
            event.preventDefault();
            alert("Please fill in required fields.");
        }
    }
</script>

<div class="create-section-box" style="max-width:600px; margin:0 auto; text-align:left;">
    
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h2 style="margin:0; color:var(--primary);">Add System Details</h2>
        <button onclick="toggleImport()" class="btn-toggle" style="background:#2196f3; font-size:12px;">Import from CSV</button>
    </div>

    <?php if ($local_msg != ""): ?>
        <div class="msg-box <?php echo ($local_msg_type == 'green') ? 'msg-green' : 'msg-red'; ?>">
            <?php echo $local_msg; ?>
        </div>
    <?php endif; ?>
    
    <hr>
    
    <div id="importSection" class="import-box">
        <h3 style="margin-top:0; color:#0d47a1;">Bulk Import Systems</h3>
        <p style="font-size:13px; color:#555;">Select the lab and upload a CSV file matching the system fields.</p>
        
        <form method="POST" enctype="multipart/form-data">
            <div style="margin-bottom:10px; text-align:left;">
                <label style="font-weight:bold; font-size:12px;">Destination Lab:</label>
                <select name="target_lab_id" class="lab-select" required>
                    <option value="" disabled selected>-- Select Lab --</option>
                    <?php echo $lab_options; ?>
                </select>
            </div>
            
            <input type="file" name="excel_file" class="btn-file" accept=".csv" required>
            <div style="margin-top:10px;">
                <input type="submit" name="import_excel" value="Upload & Import" class="btn-add" style="background:#1976d2;">
            </div>
        </form>
    </div>

    <form method="POST" id="systemsForm" onsubmit="validateOnSubmit(event)">
        
        <div class="form-group" style="background:#fafafa; padding:15px; border-radius:5px; border:1px solid #eee;">
            <label style="display:block; font-weight:bold; margin-bottom:5px; color:#333;">Select Lab <span style="color:#d93025">*</span></label>
            <select name="target_lab_id" class="lab-select" required>
                <option value="" disabled selected>-- Choose Lab to Add System --</option>
                <?php echo $lab_options; ?>
            </select>
        </div>

        <?php
        // Generate Dynamic Fields from Systems Manager
        $res = mysqli_query($conn, "SELECT * FROM systems_sections ORDER BY display_order ASC");
        if (mysqli_num_rows($res) > 0) {
            while ($sec = mysqli_fetch_assoc($res)) {
                $col = $sec['column_name'];
                $title = $sec['section_title'];
                
                echo "<div class='form-group'>";
                echo "<label style='display:block; font-weight:bold; margin-bottom:5px;'>$title <span style='color:#d93025'>*</span></label>";
                echo "<input type='text' name='$col' class='lab-input' required style='width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;'>";
                echo "</div>";
            }
        } else { 
            echo "<p style='text-align:center; color:#e74c3c;'>No system fields defined. Go to Systems Info Manager.</p>"; 
        }
        ?>
        
        <div style="text-align:center; margin-top:20px;">
            <input type="submit" name="submit_systems_form" value="Save System Details" class="btn-add" style="width:100%; padding:12px; cursor:pointer;">
        </div>
    </form>
    
    <p style="text-align:center; margin-top:15px;">
        <a href="labs_hub.php" class="btn-outline">&larr; Back to Hub</a>
    </p>
</div>

</div>
</body>
</html>