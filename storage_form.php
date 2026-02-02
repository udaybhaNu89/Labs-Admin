<?php
require 'auth_session.php';

if (isset($_POST['submit_storage_form'])) {
    $cols = ""; $vals = "";
    $res = mysqli_query($conn, "SELECT column_name FROM storage_sections ORDER BY display_order ASC");
    if(mysqli_num_rows($res) > 0) {
        while ($sec = mysqli_fetch_assoc($res)) {
            $col = $sec['column_name'];
            $val = "";
            if (isset($_POST[$col])) { $val = mysqli_real_escape_string($conn, $_POST[$col]); }
            $cols .= ", `$col`";
            $vals .= ", '$val'";
        }
        $sql = "INSERT INTO storage_unit (status $cols) VALUES ('Logged' $vals)";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['sys_msg'] = "Storage Log Added Successfully"; $_SESSION['sys_msg_color'] = "green";
        } else {
            $_SESSION['sys_msg'] = "Error: " . mysqli_error($conn); $_SESSION['sys_msg_color'] = "red";
        }
    } else {
        $_SESSION['sys_msg'] = "Error: No sections defined."; $_SESSION['sys_msg_color'] = "red";
    }
    header("Location: storage_form.php"); exit();
}

include 'header.php';
?>

<script>
    function validateStorageForm() {
        const inputs = document.querySelectorAll('.storage-input');
        const btn = document.getElementById('btnSubmitStorage');
        if (!btn) return;
        if (inputs.length === 0) { btn.disabled = true; btn.style.backgroundColor = '#ccc'; btn.style.cursor = 'not-allowed'; return; }
        let allFilled = true;
        inputs.forEach(input => { if (input.value.trim() === "") { allFilled = false; } });
        if (allFilled) { btn.disabled = false; btn.style.backgroundColor = 'var(--primary)'; btn.style.cursor = 'pointer'; } 
        else { btn.disabled = true; btn.style.backgroundColor = '#ccc'; btn.style.cursor = 'not-allowed'; }
    }
    document.addEventListener("DOMContentLoaded", function() {
        const inputs = document.querySelectorAll('.storage-input');
        inputs.forEach(input => { input.addEventListener('input', validateStorageForm); });
        validateStorageForm();
    });
</script>

<div class="create-section-box" style="max-width:600px; margin:0 auto; text-align:left;">
    <h2 style="margin-top:0; color:var(--primary); text-align:center;">Add Storage Item</h2>
    <hr>
    <form method="POST" id="storageForm">
        <?php
        $res = mysqli_query($conn, "SELECT * FROM storage_sections ORDER BY display_order ASC");
        $has_sections = (mysqli_num_rows($res) > 0);
        if ($has_sections) {
            while ($sec = mysqli_fetch_assoc($res)) {
                $col = $sec['column_name'];
                $type = $sec['input_type'];
                echo "<label style='display:block; font-weight:bold; margin-bottom:5px; justify-content:left;'>".$sec['section_title']."</label>";
                
                if ($type == 'numeric') { 
                    echo "<input type='number' name='$col' class='storage-input' required>"; 
                } elseif ($type == 'date') { 
                    echo "<div class='google-date-wrapper'>";
                    echo "<input type='date' name='$col' class='google-date-input' required>"; 
                    echo "</div>";
                } else { 
                    echo "<input type='text' name='$col' class='storage-input' required>"; 
                }
            }
        } else { echo "<p style='text-align:center; color:#e74c3c;'>No input sections defined. Go to Form Management to add fields.</p>"; }
        ?>
        <div style="text-align:center; margin-top:20px;">
            <input type="submit" id="btnSubmitStorage" name="submit_storage_form" value="Save Log Entry" class="btn-add" style="width:100%; padding:12px;" disabled>
        </div>
    </form>
    <p style="text-align:center; margin-top:15px;">
        <a href="storage_hub.php" class="btn-outline">&larr; Back to Hub</a>
    </p>
</div>

</div>
</body>
</html>