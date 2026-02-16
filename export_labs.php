<?php
require 'auth_session.php';
include 'header.php';

// =========================================================
// 1. PREPARE DATA FOR EXPORT (PHP SIDE)
// =========================================================

// A. Define Headers (Static Columns first)
$columns = [];
$columns[] = ['title' => 'Lab Name', 'col' => 'lab_name'];
$columns[] = ['title' => 'Lab Code', 'col' => 'lab_code'];
$columns[] = ['title' => 'Room No', 'col' => 'room_no'];
$columns[] = ['title' => 'Capacity', 'col' => 'no_of_system_capacity'];
$columns[] = ['title' => 'Present', 'col' => 'no_of_systems_present'];

// B. Add Dynamic Columns
$res = mysqli_query($conn, "SELECT section_title, column_name FROM labs_sections ORDER BY display_order ASC");
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $col_key = $row['column_name'];
        if (!in_array($col_key, ['lab_name', 'lab_code', 'room_no', 'no_of_system_capacity', 'no_of_systems_present', 'lab_name_table', 'table_lab_name'])) {
            $columns[] = ['title' => $row['section_title'], 'col' => $col_key];
        }
    }
}

// C. Fetch Data Rows
$data_rows = [];
$unit_res = mysqli_query($conn, "SELECT * FROM labs_unit ORDER BY lab_name ASC");
if ($unit_res) {
    while ($row = mysqli_fetch_assoc($unit_res)) {
        $clean_row = [];
        foreach ($columns as $header) {
            $key = $header['col'];
            $val = isset($row[$key]) ? $row[$key] : "-";
            $clean_row[] = $val;
        }
        $data_rows[] = $clean_row;
    }
}

// D. Encode for JavaScript
$json_headers = json_encode(array_column($columns, 'title'));
$json_data = json_encode($data_rows);
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>

<style>
    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); }
    .modal-content { background-color: #fff; margin: 5% auto; padding: 25px; border-radius: 8px; width: 80%; max-height: 80vh; overflow-y: auto; box-shadow: 0 4px 15px rgba(0,0,0,0.2); position: relative; }
    .close-modal { position: absolute; top: 15px; right: 20px; font-size: 24px; font-weight: bold; color: #aaa; cursor: pointer; }
    .close-modal:hover { color: #000; }
    
    .preview-table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 13px; }
    .preview-table th, .preview-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    .preview-table th { background-color: #f2f2f2; color: #333; font-weight: bold; }
    .preview-table tr:nth-child(even) { background-color: #f9f9f9; }
</style>

<div class="create-section-box" style="max-width:600px; margin:50px auto; text-align:center; padding:40px; background:#fff; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.05);">
    
    <h2 style="color:var(--primary); margin-top:0;">Export Labs Data</h2>
    <p style="color:#666; font-size:14px; margin-bottom:30px; line-height:1.5;">
        Generate a report containing details of all registered labs (Name, Code, Room, Capacity, etc.).
    </p>
    
    <div style="display:flex; justify-content:center; gap:10px;">
        <button onclick="previewData()" class="btn-outline" style="padding:12px 25px; font-size:15px; cursor:pointer; background-color:#fff; border:2px solid #2c3e50; color:#2c3e50; border-radius:5px; font-weight:bold;">
            👁 Preview Data
        </button>

        <button id="btnExport" onclick="generateAndDownloadPDF()" class="btn-add" style="padding:12px 25px; font-size:15px; cursor:pointer; background-color:#2c3e50; border:none; color:white; border-radius:5px; transition:0.3s;">
            ⬇ Export PDF
        </button>
    </div>

    <div style="margin-top:30px;">
        <a href="export_hub.php" class="btn-outline" style="text-decoration:none; color:#555; border:1px solid #ccc; padding:8px 16px; border-radius:4px; font-size:13px;">&larr; Back to Hub</a>
    </div>

</div>

<div id="previewModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="closePreview()">&times;</span>
        <h2 style="color:var(--primary); margin-top:0;">Data Preview</h2>
        <div id="previewContent"></div>
        <div style="text-align:right; margin-top:20px;">
            <button onclick="closePreview()" style="padding:8px 16px; background:#ddd; border:none; border-radius:4px; cursor:pointer;">Close</button>
            <button onclick="generateAndDownloadPDF()" style="padding:8px 16px; background:#2c3e50; color:white; border:none; border-radius:4px; cursor:pointer; margin-left:10px;">Download PDF</button>
        </div>
    </div>
</div>

<script>
    const headers = <?php echo $json_headers; ?>;
    const dataBody = <?php echo $json_data; ?>;

    function previewData() {
        let html = '<table class="preview-table"><thead><tr>';
        
        // Build Headers
        headers.forEach(h => { html += `<th>${h}</th>`; });
        html += '</tr></thead><tbody>';

        // Build Rows
        if(dataBody.length > 0) {
            dataBody.forEach(row => {
                html += '<tr>';
                row.forEach(cell => { html += `<td>${cell}</td>`; });
                html += '</tr>';
            });
        } else {
            html += `<tr><td colspan="${headers.length}" style="text-align:center; padding:20px;">No data found.</td></tr>`;
        }

        html += '</tbody></table>';
        
        document.getElementById('previewContent').innerHTML = html;
        document.getElementById('previewModal').style.display = 'block';
    }

    function closePreview() {
        document.getElementById('previewModal').style.display = 'none';
    }

    // Close modal on outside click
    window.onclick = function(event) {
        const modal = document.getElementById('previewModal');
        if (event.target == modal) { modal.style.display = "none"; }
    }

    function generateAndDownloadPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'mm', 'a4'); 

        doc.setFontSize(18);
        doc.setTextColor(44, 62, 80);
        doc.text("Registered Labs Report", 14, 20);
        
        doc.setFontSize(10);
        doc.setTextColor(100);
        doc.text("Generated on: " + new Date().toLocaleString(), 14, 26);

        doc.autoTable({
            head: [headers],
            body: dataBody,
            startY: 32,
            theme: 'grid',
            headStyles: { fillColor: [44, 62, 80], textColor: 255, fontStyle: 'bold' },
            styles: { fontSize: 9, cellPadding: 3, overflow: 'linebreak' },
            alternateRowStyles: { fillColor: [245, 245, 245] }
        });

        doc.save('Labs_Master_Data.pdf');
    }
</script>

</body>
</html>