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
    $safe_val = mysqli_real_escape_string($conn, $lab_id_or_name);
    $q = mysqli_query($conn, "SELECT table_lab_name FROM lab_name WHERE id = '$safe_val' OR name = '$safe_val' LIMIT 1");
    if ($row = mysqli_fetch_assoc($q)) {
        return $row['table_lab_name'];
    }
    return false;
}

// --- HELPER: Convert XLSX to CSV (Native PHP) ---
function convertXlsxToCsv($xlsx_file, $csv_file) {
    if (!class_exists('ZipArchive')) { 
        return "PHP ZipArchive extension is not enabled."; 
    }
    
    $zip = new ZipArchive;
    if ($zip->open($xlsx_file) === TRUE) {
        $strings = [];
        if ($zip->locateName('xl/sharedStrings.xml') !== false) {
            $xml = simplexml_load_string($zip->getFromName('xl/sharedStrings.xml'));
            foreach ($xml->si as $si) {
                $strings[] = (string)$si->t;
            }
        }

        if ($zip->locateName('xl/worksheets/sheet1.xml') !== false) {
            $sheet_xml = simplexml_load_string($zip->getFromName('xl/worksheets/sheet1.xml'));
            $out = fopen($csv_file, 'w');
            
            foreach ($sheet_xml->sheetData->row as $row) {
                $row_data = [];
                foreach ($row->c as $cell) {
                    $val = (string)$cell->v;
                    $t = (string)$cell['t'];
                    if ($t == 's' && isset($strings[$val])) {
                        $val = $strings[$val];
                    }
                    $row_data[] = $val;
                }
                fputcsv($out, $row_data);
            }
            fclose($out);
            $zip->close();
            return true; 
        }
        $zip->close();
        return "Could not locate Sheet 1 data in Excel file.";
    }
    return "Failed to open Excel file. The file might be corrupted.";
}

// --- ACTION 1: FETCH DATA FOR PDF GENERATION (AJAX HANDLER) ---
if (isset($_POST['fetch_export_data'])) {
    $target_lab = $_POST['lab_id'];
    $table_name = getLabTableName($conn, $target_lab);
    
    $lab_name_row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM lab_name WHERE id = '$target_lab'"));
    $lab_name = $lab_name_row ? $lab_name_row['name'] : 'Lab_Data';

    // --- NEW: Fetch Lab Personnel (Incharge / Programmer) ---
    $lab_incharge = "N/A";
    $lab_programmer = "N/A";
    
    // 1. Identify dynamic columns for Incharge and Programmer
    $meta_res = mysqli_query($conn, "SELECT section_title, column_name FROM labs_sections WHERE section_title LIKE '%Incharge%' OR section_title LIKE '%Programmer%'");
    $inc_col = ""; $prog_col = "";
    
    while($m = mysqli_fetch_assoc($meta_res)) {
        if(stripos($m['section_title'], 'Incharge') !== false) $inc_col = $m['column_name'];
        if(stripos($m['section_title'], 'Programmer') !== false) $prog_col = $m['column_name'];
    }
    
    // 2. Query labs_unit using the lab name
    if($inc_col || $prog_col) {
        $safe_lab_name = mysqli_real_escape_string($conn, $lab_name);
        $unit_q = mysqli_query($conn, "SELECT * FROM labs_unit WHERE lab_name = '$safe_lab_name' LIMIT 1");
        if($unit_q && mysqli_num_rows($unit_q) > 0) {
            $u_row = mysqli_fetch_assoc($unit_q);
            if($inc_col && isset($u_row[$inc_col])) $lab_incharge = $u_row[$inc_col];
            if($prog_col && isset($u_row[$prog_col])) $lab_programmer = $u_row[$prog_col];
        }
    }
    // --------------------------------------------------------

    if ($table_name) {
        $cols = []; $headers = [];
        $res = mysqli_query($conn, "SELECT column_name, section_title FROM systems_sections ORDER BY display_order ASC");
        while ($row = mysqli_fetch_assoc($res)) {
            $cols[] = $row['column_name'];
            $headers[] = $row['section_title'];
        }

        $data_rows = [];
        if (!empty($cols)) {
            $col_str = implode("`, `", $cols);
            $sql = "SELECT `$col_str` FROM `$table_name`";
            $data_res = mysqli_query($conn, $sql);
            
            while ($row = mysqli_fetch_assoc($data_res)) {
                $clean_row = [];
                foreach($cols as $c) {
                    $clean_row[] = $row[$c];
                }
                $data_rows[] = $clean_row;
            }
        }
        
        echo json_encode([
            'status' => 'success', 
            'filename' => $lab_name, 
            'lab_incharge' => $lab_incharge,
            'lab_programmer' => $lab_programmer,
            'headers' => $headers, 
            'data' => $data_rows
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Lab table not found']);
    }
    exit(); 
}

// --- ACTION 2: MANUAL FORM SUBMISSION ---
if (isset($_POST['submit_systems_form'])) {
    $target_lab = $_POST['target_lab_id'];
    $table_name = getLabTableName($conn, $target_lab);

    if ($table_name) {
        $cols = ""; $vals = "";
        $res = mysqli_query($conn, "SELECT column_name FROM systems_sections ORDER BY display_order ASC");
        
        if(mysqli_num_rows($res) > 0) {
            while ($sec = mysqli_fetch_assoc($res)) {
                $col = $sec['column_name'];
                $val = "";
                if (isset($_POST[$col])) { $val = mysqli_real_escape_string($conn, $_POST[$col]); }
                $cols .= ", `$col`";
                $vals .= ", '$val'";
            }
            $sql = "INSERT INTO `$table_name` (id $cols) VALUES (NULL $vals)"; 
            try {
                if (mysqli_query($conn, $sql)) {
                    $_SESSION['local_msg'] = "System added successfully to lab."; 
                    $_SESSION['local_msg_type'] = "green";
                }
            } catch (mysqli_sql_exception $e) {
                if ($e->getCode() == 1062) {
                    $_SESSION['local_msg'] = "Error: Duplicate entry detected."; 
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
        $_SESSION['local_msg'] = "Error: Selected Lab not found."; 
        $_SESSION['local_msg_type'] = "red";
    }
    header("Location: systems_info_form.php"); exit();
}

// --- ACTION 3: EXCEL/CSV FILE IMPORT ---
if (isset($_POST['import_excel'])) {
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

    $uploaded_file = $_FILES['excel_file']['tmp_name'];
    $file_ext = strtolower(pathinfo($_FILES['excel_file']['name'], PATHINFO_EXTENSION));
    $process_file = $uploaded_file;
    $is_converted = false;

    if ($file_ext == 'xlsx') {
        $temp_csv = sys_get_temp_dir() . '/import_' . uniqid() . '.csv';
        $conversion_result = convertXlsxToCsv($uploaded_file, $temp_csv);
        if ($conversion_result === true) {
            $process_file = $temp_csv; 
            $is_converted = true;
        } else {
            $_SESSION['local_msg'] = "Conversion Error: " . $conversion_result; 
            $_SESSION['local_msg_type'] = "red";
            header("Location: systems_info_form.php"); exit();
        }
    } elseif ($file_ext == 'xls') {
        $_SESSION['local_msg'] = "Error: .xls format is not supported. Use .xlsx or .csv"; 
        $_SESSION['local_msg_type'] = "red";
        header("Location: systems_info_form.php"); exit();
    } elseif ($file_ext !== 'csv') {
        $_SESSION['local_msg'] = "Invalid Format: Please upload .csv or .xlsx"; 
        $_SESSION['local_msg_type'] = "red";
        header("Location: systems_info_form.php"); exit();
    }

    function normalize_header($str) { return strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $str)); }

    $db_map = [];
    $res = mysqli_query($conn, "SELECT section_title, column_name FROM systems_sections");
    while ($row = mysqli_fetch_assoc($res)) {
        $key = normalize_header($row['section_title']);
        $db_map[$key] = $row['column_name'];
    }

    $handle = fopen($process_file, "r");
    if ($handle !== FALSE) {
        $row_count = 0;
        $success_count = 0;
        $header_map = []; 

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $row_count++;
            if ($row_count == 1) {
                foreach ($data as $index => $title) {
                    $clean = normalize_header($title);
                    if (isset($db_map[$clean])) { $header_map[$index] = $db_map[$clean]; }
                }
                if (empty($header_map)) {
                    $_SESSION['local_msg'] = "Error: No matching columns found in file."; 
                    $_SESSION['local_msg_type'] = "red";
                    fclose($handle);
                    if ($is_converted && file_exists($process_file)) unlink($process_file);
                    header("Location: systems_info_form.php"); exit();
                }
                continue;
            }
            $cols_sql = []; $vals_sql = [];
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
                try { mysqli_query($conn, $sql_insert); $success_count++; } catch (Exception $e) { continue; }
            }
        }
        fclose($handle);
        if ($is_converted && file_exists($process_file)) { unlink($process_file); }
        $_SESSION['local_msg'] = "Import Complete: $success_count systems added."; 
        $_SESSION['local_msg_type'] = "green";
    } else {
        $_SESSION['local_msg'] = "Error reading file data."; 
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>

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
        var y = document.getElementById("exportSection");
        y.style.display = "none"; 
        x.style.display = (x.style.display === "none") ? "block" : "none";
    }
    
    function toggleExport() {
        var x = document.getElementById("exportSection");
        var y = document.getElementById("importSection");
        y.style.display = "none"; 
        x.style.display = (x.style.display === "none") ? "block" : "none";
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

    // --- PDF DOWNLOAD FUNCTION ---
    function generatePDF() {
        const labId = document.getElementById('export_lab_id').value;
        const btn = document.getElementById('btnDownloadPDF');
        
        if (!labId) { alert("Please select a lab first."); return; }

        btn.value = "Generating...";
        btn.disabled = true;

        const formData = new FormData();
        formData.append('fetch_export_data', '1');
        formData.append('lab_id', labId);

        fetch('systems_info_form.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();

                // 1. Header: Lab Name
                doc.setFontSize(16);
                doc.setTextColor(0, 150, 136); // Primary Green
                doc.text(data.filename + " - Systems Report", 14, 20);
                
                // 2. Sub-header: Date
                doc.setFontSize(10);
                doc.setTextColor(100, 100, 100); // Grey
                doc.text("Generated on: " + new Date().toLocaleString(), 14, 28);

                // 3. Lab Personnel Info (Incharge / Programmer)
                doc.setTextColor(0, 0, 0); // Black
                doc.setFontSize(11);
                
                // Draw names
                doc.text("Lab Incharge: " + (data.lab_incharge || "N/A"), 14, 36);
                doc.text("Programmer: " + (data.lab_programmer || "N/A"), 14, 42);

                // 4. Data Table
                doc.autoTable({
                    head: [data.headers],
                    body: data.data,
                    startY: 48, // Start below the personnel info
                    theme: 'grid',
                    headStyles: { fillColor: [0, 150, 136] }, 
                    styles: { fontSize: 9 }
                });

                // Save File
                doc.save(data.filename + '_Systems_Report.pdf');
            } else {
                alert("Error: " + data.message);
            }
            btn.value = "Download PDF";
            btn.disabled = false;
        })
        .catch(error => {
            console.error('Error:', error);
            alert("An error occurred while generating the PDF.");
            btn.value = "Download PDF";
            btn.disabled = false;
        });
    }
</script>

<div class="create-section-box" style="max-width:600px; margin:0 auto; text-align:left;">
    
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h2 style="margin:0; color:var(--primary);">Add System Details</h2>
        <div>
            <button onclick="toggleExport()" class="btn-toggle" style="background:#009688; font-size:12px; margin-right:5px;">Export PDF</button>
            <button onclick="toggleImport()" class="btn-toggle" style="background:#2196f3; font-size:12px;">Import Excel/CSV</button>
        </div>
    </div>

    <?php if ($local_msg != ""): ?>
        <div class="msg-box <?php echo ($local_msg_type == 'green') ? 'msg-green' : 'msg-red'; ?>">
            <?php echo $local_msg; ?>
        </div>
    <?php endif; ?>
    
    <hr>
    
    <div id="exportSection" class="import-box" style="background-color:#e0f2f1; border-color:#009688; display:none;">
        <h3 style="margin-top:0; color:#00695c;">Download Lab Data (PDF)</h3>
        <p style="font-size:13px; color:#555;">Select a lab to download the report as a PDF file.</p>
        
        <div style="margin-bottom:10px; text-align:left;">
            <label style="font-weight:bold; font-size:12px;">Source Lab:</label>
            <select id="export_lab_id" class="lab-select">
                <option value="" disabled selected>-- Select Lab --</option>
                <?php echo $lab_options; ?>
            </select>
        </div>
        <div style="margin-top:10px;">
            <input type="button" id="btnDownloadPDF" onclick="generatePDF()" value="Export PDF" class="btn-add" style="background:#009688; cursor:pointer;">
        </div>
    </div>
    <div id="importSection" class="import-box">
        <h3 style="margin-top:0; color:#0d47a1;">Bulk Import Systems</h3>
        <p style="font-size:13px; color:#555;">Select the lab and upload an Excel (.xlsx) or CSV file matching the system fields.</p>
        
        <form method="POST" enctype="multipart/form-data">
            <div style="margin-bottom:10px; text-align:left;">
                <label style="font-weight:bold; font-size:12px;">Destination Lab:</label>
                <select name="target_lab_id" class="lab-select" required>
                    <option value="" disabled selected>-- Select Lab --</option>
                    <?php echo $lab_options; ?>
                </select>
            </div>
            
            <input type="file" name="excel_file" class="btn-file" accept=".csv, .xlsx, .xls" required>
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