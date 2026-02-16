<?php
ob_start();
require 'auth_session.php';

if (isset($_POST['fetch_export_data'])) {
    ob_clean();
    $lab_id = intval($_POST['lab_id']);
    $system_no = mysqli_real_escape_string($conn, $_POST['system_number']);
    $include_log = (isset($_POST['include_log']) && $_POST['include_log'] === 'true');
    
    $response = [
        'status' => 'error', 'message' => '',
        'lab_info' => [], 'system_info' => [],
        'complaints' => ['headers' => [], 'data' => []]
    ];

    // A. LAB INFO
    $lab_query = mysqli_query($conn, "SELECT * FROM labs_unit WHERE id = $lab_id LIMIT 1");
    $lab_data = mysqli_fetch_assoc($lab_query);
    if (!$lab_data) { echo json_encode(['status'=>'error', 'message'=>'Lab not found']); exit(); }
    
    $target_lab_name = $lab_data['lab_name']; 
    $target_table = $lab_data['lab_name_table']; 
    if (empty($target_table)) { $target_table = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $target_lab_name)); $target_table = trim($target_table, '_'); }

    $response['lab_info'][] = ['key' => 'Lab Name', 'value' => $lab_data['lab_name']];
    $response['lab_info'][] = ['key' => 'Lab Code', 'value' => $lab_data['lab_code']];
    $response['lab_info'][] = ['key' => 'Room No', 'value' => $lab_data['room_no']];
    
    $sec_res = mysqli_query($conn, "SELECT section_title, column_name FROM labs_sections ORDER BY display_order ASC");
    while ($sec = mysqli_fetch_assoc($sec_res)) {
        $col = $sec['column_name'];
        if(in_array($col, ['lab_name', 'lab_code', 'room_no', 'no_of_system_capacity', 'no_of_systems_present', 'lab_name_table'])) continue;
        $val = isset($lab_data[$col]) ? $lab_data[$col] : '-';
        $response['lab_info'][] = ['key' => $sec['section_title'], 'value' => $val];
    }

    // B. SYSTEM INFO
    if (!empty($target_table) && mysqli_num_rows(mysqli_query($conn, "SHOW TABLES LIKE '$target_table'")) > 0) {
        $sys_query = mysqli_query($conn, "SELECT * FROM `$target_table` WHERE system_number = '$system_no' LIMIT 1");
        $sys_data = mysqli_fetch_assoc($sys_query);
        if ($sys_data) {
            $sys_sec_res = mysqli_query($conn, "SELECT section_title, column_name FROM systems_sections ORDER BY display_order ASC");
            while ($sec = mysqli_fetch_assoc($sys_sec_res)) {
                $col = $sec['column_name'];
                $val = isset($sys_data[$col]) ? $sys_data[$col] : '-';
                $response['system_info'][] = ['key' => $sec['section_title'], 'value' => $val];
            }
        } else { $response['system_info'][] = ['key' => 'Error', 'value' => 'System not found in DB']; }
    }

    // C. COMPLAINTS
    if ($include_log) {
        $lab_name_col = 'lab_name'; $sys_num_col = 'system_number';
        $ds_res = mysqli_query($conn, "SELECT column_name, section_title FROM dynamic_sections");
        while($r = mysqli_fetch_assoc($ds_res)) { 
            if($r['section_title'] == 'Lab Name') $lab_name_col = $r['column_name'];
            if($r['section_title'] == 'System Number') $sys_num_col = $r['column_name'];
        }

        $comp_headers = []; $comp_cols = [];
        $dyn_res = mysqli_query($conn, "SELECT section_title, column_name FROM dynamic_sections ORDER BY display_order ASC");
        while ($row = mysqli_fetch_assoc($dyn_res)) {
            if ($row['column_name'] == $lab_name_col || $row['column_name'] == $sys_num_col) continue;
            $comp_headers[] = $row['section_title']; $comp_cols[] = $row['column_name'];
        }
        $comp_headers[] = "Details"; $comp_headers[] = "Status"; $comp_headers[] = "Reported"; $comp_headers[] = "Resolved"; $comp_headers[] = "Resolved By";
        $response['complaints']['headers'] = $comp_headers;

        $safe_lab = mysqli_real_escape_string($conn, $target_lab_name);
        $log_res = mysqli_query($conn, "SELECT * FROM complaints_log WHERE `$lab_name_col` = '$safe_lab' AND `$sys_num_col` = '$system_no' ORDER BY created_at DESC");
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
    header('Content-Type: application/json'); echo json_encode($response); exit();
}
ob_end_flush();
include 'header.php';

// Map Logic
$lab_systems_map = [];
$u_res = mysqli_query($conn, "SELECT id, lab_name, lab_name_table FROM labs_unit");
while($u_row = mysqli_fetch_assoc($u_res)) {
    $lid = $u_row['id']; $tname = $u_row['lab_name_table'];
    if(empty($tname)) { $tname = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $u_row['lab_name'])); $tname = trim($tname, '_'); }
    $systems = [];
    if(mysqli_query($conn, "SHOW TABLES LIKE '$tname'")) {
        $s_res = mysqli_query($conn, "SELECT system_number FROM `$tname` ORDER BY id ASC");
        while($s = mysqli_fetch_assoc($s_res)) $systems[] = $s['system_number'];
    }
    $lab_systems_map[$lid] = $systems;
}
$json_map = json_encode($lab_systems_map);
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
    <h2 style="margin-top:0; color:var(--primary); text-align:center;">Export Specific System Data</h2>
    <hr>
    <div class="form-group" style="margin-bottom:20px;">
        <label style="display:block; font-weight:bold; margin-bottom:8px;">Select Lab:</label>
        <select id="export_lab_id" onchange="updateSystemOptions()" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;">
            <option value="" disabled selected>-- Choose Lab --</option>
            <?php
            $res = mysqli_query($conn, "SELECT id, lab_name FROM labs_unit ORDER BY lab_name ASC");
            while ($row = mysqli_fetch_assoc($res)) { echo "<option value='".$row['id']."'>".$row['lab_name']."</option>"; }
            ?>
        </select>
    </div>
    <div class="form-group" style="margin-bottom:20px;">
        <label style="display:block; font-weight:bold; margin-bottom:8px;">Select System Number:</label>
        <select id="export_system_no" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;" disabled>
            <option value="" disabled selected>-- Select Lab First --</option>
        </select>
    </div>
    <div class="form-group" style="margin-bottom:25px; background:#f9f9f9; padding:15px; border-radius:5px; border:1px solid #eee;">
        <label style="display:block; cursor:pointer;"><input type="checkbox" id="chk_log" style="margin-right:8px;" checked> <strong>Get systems complaints log</strong></label>
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
    const labSystemsMap = <?php echo $json_map; ?>;
    function updateSystemOptions() {
        const labId = document.getElementById('export_lab_id').value;
        const sysSelect = document.getElementById('export_system_no');
        sysSelect.innerHTML = '<option value="" disabled selected>-- Choose System --</option>';
        sysSelect.disabled = false;
        const systems = labSystemsMap[labId];
        if (systems && systems.length > 0) systems.forEach(sys => { let opt = document.createElement('option'); opt.value = sys; opt.textContent = sys; sysSelect.appendChild(opt); });
        else { let opt = document.createElement('option'); opt.textContent = "No systems found"; sysSelect.appendChild(opt); sysSelect.disabled = true; }
    }

    function handleAction(actionType) {
        const labId = document.getElementById('export_lab_id').value;
        const sysNo = document.getElementById('export_system_no').value;
        const includeLog = document.getElementById('chk_log').checked;

        if (!labId || !sysNo) { alert("Please select Lab and System."); return; }

        if (actionType === 'export') {
            document.getElementById('btnExport').disabled = true;
            document.getElementById('btnExport').innerHTML = "Generating...";
        } else {
            document.getElementById('previewModal').style.display = 'block';
            document.getElementById('previewContent').innerHTML = '<p>Loading data...</p>';
        }

        const formData = new FormData();
        formData.append('fetch_export_data', '1');
        formData.append('lab_id', labId);
        formData.append('system_number', sysNo);
        formData.append('include_log', includeLog);

        fetch('export_systems.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (actionType === 'export') {
                if(data.status==='success') generatePDF(data, sysNo, includeLog);
                else alert(data.message);
                document.getElementById('btnExport').disabled = false;
                document.getElementById('btnExport').innerHTML = "⬇ Export PDF";
            } else {
                if(data.status==='success') renderPreview(data, sysNo, includeLog);
                else document.getElementById('previewContent').innerHTML = `<p style='color:red'>${data.message}</p>`;
            }
        })
        .catch(err => { alert("Error fetching data"); if(actionType==='export') { document.getElementById('btnExport').disabled = false; document.getElementById('btnExport').innerHTML = "⬇ Export PDF"; } });
    }

    function renderPreview(data, sysName, hasLog) {
        let html = '';
        html += '<div class="preview-section-title">Lab Information</div><table class="preview-kv-table">';
        data.lab_info.forEach(item => html += `<tr><td class="preview-kv-key">${item.key}</td><td>${item.value}</td></tr>`);
        html += '</table>';

        html += '<div class="preview-section-title">System Specifications</div><table class="preview-kv-table">';
        data.system_info.forEach(item => html += `<tr><td class="preview-kv-key">${item.key}</td><td>${item.value}</td></tr>`);
        html += '</table>';

        if (hasLog) {
            html += '<div class="preview-section-title">Complaint History</div>';
            if(data.complaints.data.length > 0) {
                html += '<table class="preview-grid-table"><thead><tr>';
                data.complaints.headers.forEach(h => html += `<th>${h}</th>`);
                html += '</tr></thead><tbody>';
                data.complaints.data.forEach(row => { html += '<tr>'; row.forEach(cell => html += `<td>${cell}</td>`); html += '</tr>'; });
                html += '</tbody></table>';
            } else { html += '<p style="color:#777">No complaints recorded.</p>'; }
        }
        document.getElementById('previewContent').innerHTML = html;
    }

    function closePreview() { document.getElementById('previewModal').style.display = 'none'; }
    window.onclick = function(ev) { if (ev.target == document.getElementById('previewModal')) closePreview(); }

    function generatePDF(data, sysName, hasLog) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('p', 'mm', 'a4');
        let finalY = 20;

        doc.setFontSize(18); doc.setTextColor(0,150,136); doc.text("Individual System Report: " + sysName, 14, finalY); finalY += 10;
        doc.setFontSize(10); doc.setTextColor(100); doc.text("Generated: " + new Date().toLocaleString(), 14, finalY); finalY += 15;

        doc.setFontSize(14); doc.setTextColor(0,0,0); doc.text("Lab Information", 14, finalY); finalY += 5;
        const infoBody = data.lab_info.map(item => [item.key, item.value]);
        doc.autoTable({ body: infoBody, startY: finalY, theme: 'plain', styles: { fontSize: 10, cellPadding: 2 }, columnStyles: { 0: { fontStyle: 'bold', width: 60 } } });
        finalY = doc.lastAutoTable.finalY + 15;

        doc.setFontSize(14); doc.text("System Specifications", 14, finalY); finalY += 5;
        const sysBody = data.system_info.map(item => [item.key, item.value]);
        doc.autoTable({ body: sysBody, startY: finalY, theme: 'grid', headStyles: { fillColor: [41, 128, 185] }, styles: { fontSize: 10, cellPadding: 3 }, columnStyles: { 0: { fontStyle: 'bold', width: 60, fillColor: [240, 240, 240] } } });
        finalY = doc.lastAutoTable.finalY + 15;

        if (hasLog) {
            doc.setFontSize(14); doc.text("Complaint History", 14, finalY); finalY += 5;
            if (data.complaints.data.length > 0) {
                doc.autoTable({ head: [data.complaints.headers], body: data.complaints.data, startY: finalY, theme: 'grid', headStyles: { fillColor: [192, 57, 43] }, styles: { fontSize: 9 } });
            } else { doc.setFontSize(10); doc.setTextColor(150); doc.text("(No complaints recorded)", 14, finalY + 5); }
        }

        doc.save(sysName.replace(/[^a-zA-Z0-9]/g, '_') + '_System_Report.pdf');
    }
</script>
</body>
</html>