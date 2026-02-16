<?php
// Start output buffering
ob_start();
require 'auth_session.php';

if (isset($_POST['fetch_export_data'])) {
    ob_clean();
    
    $lab_id = intval($_POST['lab_id']);
    $include_systems = (isset($_POST['include_systems']) && $_POST['include_systems'] === 'true');
    $include_complaints = (isset($_POST['include_complaints']) && $_POST['include_complaints'] === 'true');
    
    $response = [
        'status' => 'error', 'message' => '',
        'lab_info' => [],
        'systems' => ['headers' => [], 'data' => []],
        'complaints' => ['headers' => [], 'data' => []]
    ];

    // 1. FETCH LAB INFO
    $lab_query = mysqli_query($conn, "SELECT * FROM labs_unit WHERE id = $lab_id LIMIT 1");
    $lab_data = mysqli_fetch_assoc($lab_query);
    
    if (!$lab_data) {
        $response['message'] = "Lab not found.";
        echo json_encode($response); exit();
    }

    $target_lab_name = $lab_data['lab_name']; 
    $target_table = $lab_data['lab_name_table']; 
    if (empty($target_table)) {
        $target_table = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $target_lab_name));
        $target_table = trim($target_table, '_');
    }

    $response['lab_info'][] = ['key' => 'Lab Name', 'value' => $lab_data['lab_name']];
    $response['lab_info'][] = ['key' => 'Lab Code', 'value' => $lab_data['lab_code']];
    $response['lab_info'][] = ['key' => 'Room No', 'value' => $lab_data['room_no']];
    $response['lab_info'][] = ['key' => 'Capacity', 'value' => $lab_data['no_of_system_capacity']];
    $response['lab_info'][] = ['key' => 'Systems Present', 'value' => $lab_data['no_of_systems_present']];

    $sec_res = mysqli_query($conn, "SELECT section_title, column_name FROM labs_sections ORDER BY display_order ASC");
    while ($sec = mysqli_fetch_assoc($sec_res)) {
        $col = $sec['column_name'];
        if(in_array($col, ['lab_name', 'lab_code', 'room_no', 'no_of_system_capacity', 'no_of_systems_present', 'lab_name_table'])) continue;
        $val = isset($lab_data[$col]) ? $lab_data[$col] : '-';
        $response['lab_info'][] = ['key' => $sec['section_title'], 'value' => $val];
    }

    // 2. SYSTEMS
    if ($include_systems) {
        $sys_headers = []; $sys_cols = [];
        $sys_sec_res = mysqli_query($conn, "SELECT section_title, column_name FROM systems_sections ORDER BY display_order ASC");
        while ($row = mysqli_fetch_assoc($sys_sec_res)) {
            $sys_headers[] = $row['section_title']; $sys_cols[] = $row['column_name'];
        }
        $response['systems']['headers'] = $sys_headers;

        if (!empty($target_table)) {
            $tbl_check = mysqli_query($conn, "SHOW TABLES LIKE '$target_table'");
            if (mysqli_num_rows($tbl_check) > 0) {
                $sys_data_res = mysqli_query($conn, "SELECT * FROM `$target_table`");
                while ($row = mysqli_fetch_assoc($sys_data_res)) {
                    $row_data = [];
                    foreach ($sys_cols as $col) { $row_data[] = isset($row[$col]) ? $row[$col] : '-'; }
                    $response['systems']['data'][] = $row_data;
                }
            }
        }
    }

    // 3. COMPLAINTS
    if ($include_complaints) {
        $lab_name_col = 'lab_name'; 
        $ds_res = mysqli_query($conn, "SELECT column_name FROM dynamic_sections WHERE section_title = 'Lab Name' LIMIT 1");
        if($r = mysqli_fetch_assoc($ds_res)) { $lab_name_col = $r['column_name']; }

        $comp_headers = []; $comp_cols = [];
        $dyn_res = mysqli_query($conn, "SELECT section_title, column_name FROM dynamic_sections ORDER BY display_order ASC");
        while ($row = mysqli_fetch_assoc($dyn_res)) {
            $comp_headers[] = $row['section_title']; $comp_cols[] = $row['column_name'];
        }
        $comp_headers[] = "Other Details"; $comp_headers[] = "Status"; $comp_headers[] = "Date Reported"; $comp_headers[] = "Resolved Date"; $comp_headers[] = "Resolved By";

        $response['complaints']['headers'] = $comp_headers;
        $safe_lab_name = mysqli_real_escape_string($conn, $target_lab_name);
        $log_res = mysqli_query($conn, "SELECT * FROM complaints_log WHERE `$lab_name_col` = '$safe_lab_name' ORDER BY created_at DESC");
        
        while ($row = mysqli_fetch_assoc($log_res)) {
            $row_data = [];
            foreach ($comp_cols as $col) { $row_data[] = isset($row[$col]) ? $row[$col] : '-'; }
            $row_data[] = !empty($row['other_details']) ? $row['other_details'] : '-'; $row_data[] = $row['status']; $row_data[] = $row['created_at'];
            $row_data[] = !empty($row['issue_fixed_at']) ? $row['issue_fixed_at'] : '-';
            $row_data[] = !empty($row['complaint_modified_by']) ? $row['complaint_modified_by'] : '-';
            $response['complaints']['data'][] = $row_data;
        }
    }

    $response['status'] = 'success';
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
ob_end_flush();
include 'header.php';
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>

<style>
    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); }
    .modal-content { background-color: #fff; margin: 3% auto; padding: 25px; border-radius: 8px; width: 85%; max-height: 85vh; overflow-y: auto; box-shadow: 0 4px 15px rgba(0,0,0,0.2); position: relative; }
    .close-modal { position: absolute; top: 15px; right: 20px; font-size: 24px; font-weight: bold; color: #aaa; cursor: pointer; }
    .close-modal:hover { color: #000; }
    
    .preview-section-title { color: #009688; border-bottom: 2px solid #009688; padding-bottom: 5px; margin-top: 25px; margin-bottom: 10px; font-size: 16px; }
    .preview-kv-table { width: 100%; margin-bottom: 10px; border-collapse: collapse; }
    .preview-kv-table td { padding: 6px; border-bottom: 1px solid #eee; }
    .preview-kv-key { font-weight: bold; width: 200px; color: #555; }
    
    .preview-grid-table { width: 100%; border-collapse: collapse; font-size: 12px; margin-bottom: 10px; }
    .preview-grid-table th, .preview-grid-table td { border: 1px solid #ddd; padding: 6px; text-align: left; }
    .preview-grid-table th { background-color: #f2f2f2; color: #333; font-weight: bold; }
</style>

<div class="create-section-box" style="max-width:700px; margin:40px auto; text-align:left; background:#fff; padding:30px; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.05);">
    
    <h2 style="margin-top:0; color:var(--primary); text-align:center;">Export Lab Systems Data</h2>
    <hr>

    <div class="form-group" style="margin-bottom:20px;">
        <label style="display:block; font-weight:bold; margin-bottom:8px;">Select Lab:</label>
        <select id="export_lab_id" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;">
            <option value="" disabled selected>-- Choose Lab --</option>
            <?php
            $res = mysqli_query($conn, "SELECT id, lab_name FROM labs_unit ORDER BY lab_name ASC");
            while ($row = mysqli_fetch_assoc($res)) { echo "<option value='".$row['id']."'>".$row['lab_name']."</option>"; }
            ?>
        </select>
    </div>

    <div class="form-group" style="margin-bottom:25px; background:#f9f9f9; padding:15px; border-radius:5px; border:1px solid #eee;">
        <label style="display:block; font-weight:bold; margin-bottom:10px;">Select Data to Include:</label>
        <label style="display:block; margin-bottom:12px; cursor:pointer;"><input type="checkbox" id="chk_systems" style="margin-right:8px;" checked> <strong>Get Systems Data</strong></label>
        <label style="display:block; cursor:pointer;"><input type="checkbox" id="chk_complaints" style="margin-right:8px;"> <strong>Get Complaints Data</strong></label>
    </div>

    <div style="display:flex; justify-content:center; gap:10px;">
        <button onclick="handleAction('preview')" class="btn-outline" style="padding:12px 25px; cursor:pointer; background-color:#fff; border:2px solid #2c3e50; color:#2c3e50; border-radius:5px; font-weight:bold;">
            👁 Preview Data
        </button>
        <button id="btnExport" onclick="handleAction('export')" class="btn-add" style="padding:12px 25px; cursor:pointer;">
            ⬇ Export PDF
        </button>
    </div>
    
    <div style="margin-top:20px; text-align:center;">
        <a href="export_hub.php" class="btn-outline" style="font-size:13px; text-decoration:none;">&larr; Back to Hub</a>
    </div>

</div>

<div id="previewModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="closePreview()">&times;</span>
        <h2 style="color:var(--primary); margin-top:0;">Data Preview</h2>
        <div id="previewContent">Loading...</div>
        <div style="text-align:right; margin-top:20px;">
            <button onclick="closePreview()" style="padding:8px 16px; background:#ddd; border:none; border-radius:4px; cursor:pointer;">Close</button>
            <button onclick="handleAction('export')" style="padding:8px 16px; background:#2c3e50; color:white; border:none; border-radius:4px; cursor:pointer; margin-left:10px;">Download PDF</button>
        </div>
    </div>
</div>

<script>
    function handleAction(actionType) {
        const labId = document.getElementById('export_lab_id').value;
        const includeSystems = document.getElementById('chk_systems').checked;
        const includeComplaints = document.getElementById('chk_complaints').checked;
        
        if (!labId) { alert("Please select a Lab first."); return; }

        if (actionType === 'export') {
            const btn = document.getElementById('btnExport');
            btn.disabled = true; btn.innerHTML = "Generating...";
        } else {
            document.getElementById('previewModal').style.display = 'block';
            document.getElementById('previewContent').innerHTML = '<p>Loading data...</p>';
        }

        const formData = new FormData();
        formData.append('fetch_export_data', '1');
        formData.append('lab_id', labId);
        formData.append('include_systems', includeSystems);
        formData.append('include_complaints', includeComplaints);

        fetch('export_lab_systems.php', { method: 'POST', body: formData })
        .then(response => response.json())
        .then(data => {
            if (actionType === 'export') {
                if (data.status === 'success') generatePDF(data, includeSystems, includeComplaints);
                else alert("Error: " + data.message);
                document.getElementById('btnExport').disabled = false;
                document.getElementById('btnExport').innerHTML = "⬇ Export PDF";
            } else {
                if (data.status === 'success') renderPreview(data, includeSystems, includeComplaints);
                else document.getElementById('previewContent').innerHTML = `<p style='color:red'>${data.message}</p>`;
            }
        })
        .catch(error => {
            console.error(error);
            alert("Error fetching data.");
            if (actionType === 'export') {
                document.getElementById('btnExport').disabled = false;
                document.getElementById('btnExport').innerHTML = "⬇ Export PDF";
            }
        });
    }

    function renderPreview(data, hasSystems, hasComplaints) {
        let html = '';

        // 1. Lab Info
        html += '<div class="preview-section-title">Lab Information</div>';
        html += '<table class="preview-kv-table">';
        data.lab_info.forEach(item => {
            html += `<tr><td class="preview-kv-key">${item.key}</td><td>${item.value}</td></tr>`;
        });
        html += '</table>';

        // 2. Systems
        if (hasSystems) {
            html += '<div class="preview-section-title">Systems Data</div>';
            if (data.systems.data.length > 0) {
                html += '<table class="preview-grid-table"><thead><tr>';
                data.systems.headers.forEach(h => { html += `<th>${h}</th>`; });
                html += '</tr></thead><tbody>';
                data.systems.data.forEach(row => {
                    html += '<tr>';
                    row.forEach(cell => { html += `<td>${cell}</td>`; });
                    html += '</tr>';
                });
                html += '</tbody></table>';
            } else { html += '<p style="color:#777; font-style:italic;">No systems found.</p>'; }
        }

        // 3. Complaints
        if (hasComplaints) {
            html += `<div class="preview-section-title">Complaint History</div>`;
            if (data.complaints.data.length > 0) {
                html += '<table class="preview-grid-table"><thead><tr>';
                data.complaints.headers.forEach(h => { html += `<th>${h}</th>`; });
                html += '</tr></thead><tbody>';
                data.complaints.data.forEach(row => {
                    html += '<tr>';
                    row.forEach(cell => { html += `<td>${cell}</td>`; });
                    html += '</tr>';
                });
                html += '</tbody></table>';
            } else { html += '<p style="color:#777; font-style:italic;">No complaints found.</p>'; }
        }

        document.getElementById('previewContent').innerHTML = html;
    }

    function closePreview() { document.getElementById('previewModal').style.display = 'none'; }
    window.onclick = function(ev) { if (ev.target == document.getElementById('previewModal')) closePreview(); }

    function generatePDF(data, hasSystems, hasComplaints) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'mm', 'a4');
        let finalY = 20;

        doc.setFontSize(18); doc.setTextColor(0, 150, 136); doc.text("Lab Report", 14, finalY); finalY += 10;
        doc.setFontSize(10); doc.setTextColor(100); doc.text("Generated: " + new Date().toLocaleString(), 14, finalY); finalY += 10;

        doc.setFontSize(14); doc.setTextColor(0,0,0); doc.text("Lab Information", 14, finalY); finalY += 5;
        const infoBody = data.lab_info.map(item => [item.key, item.value]);
        doc.autoTable({ body: infoBody, startY: finalY, theme: 'plain', styles: { fontSize: 10, cellPadding: 2 }, columnStyles: { 0: { fontStyle: 'bold', width: 50 } } });
        finalY = doc.lastAutoTable.finalY + 15;

        if (hasSystems) {
            doc.setFontSize(14); doc.text("Systems Data", 14, finalY); finalY += 5;
            if (data.systems.data.length > 0) {
                doc.autoTable({ head: [data.systems.headers], body: data.systems.data, startY: finalY, theme: 'grid', headStyles: { fillColor: [41, 128, 185] }, styles: { fontSize: 9 } });
                finalY = doc.lastAutoTable.finalY + 15;
            } else { doc.setFontSize(10); doc.setTextColor(150); doc.text("(No systems found)", 14, finalY + 5); finalY += 15; }
        }

        if (hasComplaints) {
            if (finalY > 170) { doc.addPage(); finalY = 20; }
            doc.setFontSize(14); doc.setTextColor(0,0,0); doc.text("Complaint History", 14, finalY); finalY += 5;
            if (data.complaints.data.length > 0) {
                doc.autoTable({ head: [data.complaints.headers], body: data.complaints.data, startY: finalY, theme: 'grid', headStyles: { fillColor: [192, 57, 43] }, styles: { fontSize: 8 } });
            } else { doc.setFontSize(10); doc.setTextColor(150); doc.text("(No complaints recorded)", 14, finalY + 5); }
        }

        const labName = data.lab_info.find(i => i.key === 'Lab Name')?.value || 'Lab';
        doc.save(labName.replace(/[^a-zA-Z0-9]/g, '_') + '_Full_Report.pdf');
    }
</script>
</body>
</html>