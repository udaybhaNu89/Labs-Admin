<?php
require 'auth_session.php';

// --- ADDED: Handle Field Update ---
if (isset($_POST['update_invoice_field'])) {
    $id = intval($_POST['target_id']);
    $col = mysqli_real_escape_string($conn, $_POST['target_col']);
    $val = mysqli_real_escape_string($conn, $_POST['new_value']);

    // Security: Check if this column is actually allowed to be edited
    $allowed_check = mysqli_query($conn, "SELECT id FROM invoices_edit_options WHERE edit_options = '$col'");
    
    if (mysqli_num_rows($allowed_check) > 0) {
        $update_sql = "UPDATE storage_unit SET `$col` = '$val' WHERE id = $id";
        if(mysqli_query($conn, $update_sql)) {
            $_SESSION['sys_msg'] = "Updated Successfully"; $_SESSION['sys_msg_color'] = "green";
        } else {
            $_SESSION['sys_msg'] = "Update Failed"; $_SESSION['sys_msg_color'] = "red";
        }
    } else {
        $_SESSION['sys_msg'] = "Error: Field not editable"; $_SESSION['sys_msg_color'] = "red";
    }
    header("Location: invoices_log.php"); exit();
}
// ----------------------------------

if (isset($_GET['delete_storage'])) {
    $id = intval($_GET['delete_storage']);
    mysqli_query($conn, "DELETE FROM storage_unit WHERE id = $id");
    $_SESSION['sys_msg'] = "Log Entry Deleted"; $_SESSION['sys_msg_color'] = "green";
    header("Location: invoices_log.php"); exit();
}

include 'header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1><strong>Invoices Logs</strong></h1>
    <p><a href="invoices_hub.php" class="btn-outline">&larr; Back to Hub</a></p>
</div>

<?php
$sections = [];
$res = mysqli_query($conn, "SELECT * FROM storage_sections ORDER BY display_order ASC");
while ($row = mysqli_fetch_assoc($res)) { $sections[] = $row; }

$editable_cols = [];
$edit_res = mysqli_query($conn, "SELECT edit_options FROM invoices_edit_options");
while ($row = mysqli_fetch_assoc($edit_res)) {
    $editable_cols[] = $row['edit_options'];
}

$sql = "SELECT * FROM storage_unit ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
$num_records = mysqli_num_rows($result);
?>

<?php if ($num_records > 0): ?>
    <table>
        <tr>
            <?php foreach ($sections as $sec) { echo "<th>" . $sec['section_title'] . "</th>"; } ?>
            <th>Date Logged</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <?php foreach ($sections as $sec) {
                    $col = $sec['column_name'];
                    $val = (!empty($row[$col])) ? $row[$col] : "-";
                    
                    // Escape value for JS usage
                    $js_val = addslashes($val); 
                    
                    echo "<td>" . $val;
                    
                    // --- UPDATED: Edit Icon triggers Modal ---
                    if (in_array($col, $editable_cols)) {
                        echo " <a href='javascript:void(0)' onclick='openEditModal(" . $row['id'] . ", \"$col\", \"$js_val\")' title='Edit' style='text-decoration:none; color:var(--primary); font-size:14px; margin-left:5px; cursor:pointer;'>&#9998;</a>";
                    }
                    // -----------------------------------------
                    
                    echo "</td>";
                } ?>
                <td><?php echo $row['created_at']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <div style="text-align:center; padding:40px; border:1px solid #eee; border-radius:8px; background:#fff; color:#777;">
        <h3>No Records Found</h3>
    </div>
<?php endif; ?>

<style>
    .edit-modal { display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); }
    .edit-modal-content { background-color: #fff; margin: 15% auto; padding: 25px; border-radius: 8px; width: 350px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); position: relative; animation: fadeIn 0.3s; }
    .edit-close { float: right; font-size: 20px; font-weight: bold; cursor: pointer; color: #aaa; }
    .edit-close:hover { color: #000; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div id="editInvoiceModal" class="edit-modal">
    <div class="edit-modal-content">
        <span class="edit-close" onclick="closeEditModal()">&times;</span>
        <h3 style="margin-top:0; color:var(--primary);">Edit Value</h3>
        
        <form method="POST">
            <input type="hidden" name="target_id" id="modal_id">
            <input type="hidden" name="target_col" id="modal_col">
            
            <label style="display:block; text-align:left; margin-bottom:5px;">Update Data:</label>
            <input type="text" name="new_value" id="modal_val" required style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;">
            
            <div style="margin-top:15px; text-align:right;">
                <button type="button" class="btn-cancel" onclick="closeEditModal()">Cancel</button>
                <input type="submit" name="update_invoice_field" value="Update" class="btn-add">
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(id, col, val) {
        document.getElementById('modal_id').value = id;
        document.getElementById('modal_col').value = col;
        document.getElementById('modal_val').value = (val === '-') ? '' : val; // Clear hyphen if empty
        document.getElementById('editInvoiceModal').style.display = 'block';
    }

    function closeEditModal() {
        document.getElementById('editInvoiceModal').style.display = 'none';
    }

    // Close on outside click
    window.onclick = function(event) {
        var modal = document.getElementById('editInvoiceModal');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>
</div>
</body>
</html>