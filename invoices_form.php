<?php
require 'auth_session.php';

// Check for local message from session (PRG pattern)
$local_msg = "";
$local_msg_type = "";
if (isset($_SESSION['local_msg'])) {
    $local_msg = $_SESSION['local_msg'];
    $local_msg_type = $_SESSION['local_msg_type'];
    unset($_SESSION['local_msg']);
    unset($_SESSION['local_msg_type']);
}

if (isset($_POST['submit_invoices_form'])) {
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
            // SUCCESS
            $_SESSION['local_msg'] = "Form submitted successfully"; 
            $_SESSION['local_msg_type'] = "green";
        } else {
            // ERROR
            $_SESSION['local_msg'] = "Error: " . mysqli_error($conn); 
            $_SESSION['local_msg_type'] = "red";
        }
    } else {
        $_SESSION['local_msg'] = "Error: No sections defined."; 
        $_SESSION['local_msg_type'] = "red";
    }
    
    header("Location: invoices_form.php"); exit();
}

include 'header.php';
?>

<style>
    .form-group { margin-bottom: 20px; }
    .error-msg { color: #d93025; font-size: 12px; margin-top: 5px; display: none; align-items: center; gap: 5px; }
    .error-msg::before { content: "⚠ "; font-size: 14px; }
    .input-invalid { border-bottom: 2px solid #d93025 !important; }
    .wrapper-invalid { border-bottom: 2px solid #d93025 !important; }
    
    /* Message Box Style */
    .msg-box {
        padding: 10px;
        margin-top: 15px;
        margin-bottom: 15px;
        border-radius: 4px;
        font-weight: bold;
        text-align: center;
        font-size: 14px;
    }
    .msg-green { background-color: #e8f5e9; color: #27ae60; border: 1px solid #c8e6c9; }
    .msg-red { background-color: #fce4ec; color: #c0392b; border: 1px solid #fadbd8; }
</style>

<script>
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
        const inputs = document.querySelectorAll('.storage-input, .google-date-input');
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
    <h2 style="margin-top:0; color:var(--primary); text-align:center;">Add Storage Item</h2>
    
    <?php if ($local_msg != ""): ?>
        <div class="msg-box <?php echo ($local_msg_type == 'green') ? 'msg-green' : 'msg-red'; ?>">
            <?php echo $local_msg; ?>
        </div>
    <?php endif; ?>
    
    <hr>
    
    <form method="POST" id="storageForm" onsubmit="validateOnSubmit(event)">
        <?php
        $res = mysqli_query($conn, "SELECT * FROM storage_sections ORDER BY display_order ASC");
        if (mysqli_num_rows($res) > 0) {
            while ($sec = mysqli_fetch_assoc($res)) {
                $col = $sec['column_name'];
                $type = $sec['input_type'];
                $title = $sec['section_title'];
                echo "<div class='form-group'>";
                echo "<label style='display:block; font-weight:bold; margin-bottom:5px; justify-content:left;'>$title <span style='color:#d93025'>*</span></label>";
                
                if ($type == 'numeric') { 
                    echo "<input type='number' name='$col' class='storage-input' required onblur='checkField(this)' oninput='clearError(this)'>"; 
                } elseif ($type == 'date') { 
                    echo "<div class='google-date-wrapper'>";
                    echo "<input type='date' name='$col' class='google-date-input' required onblur='checkField(this)' oninput='clearError(this)'>"; 
                    echo "</div>";
                } else { 
                    echo "<input type='text' name='$col' class='storage-input' required onblur='checkField(this)' oninput='clearError(this)'>"; 
                }
                echo "<div class='error-msg'>This field is required</div>";
                echo "</div>";
            }
        } else { 
            echo "<p style='text-align:center; color:#e74c3c;'>No input sections defined. Go to Form Management to add fields.</p>"; 
        }
        ?>
        
        <div style="text-align:center; margin-top:20px;">
            <input type="submit" id="btnSubmitStorage" name="submit_invoices_form" value="Save Log Entry" class="btn-add" style="width:100%; padding:12px; cursor:pointer;">
        </div>
    </form>
    
    <?php if (isset($user_permission) && $user_permission === 'Full'): ?>
        <p style="text-align:center; margin-top:15px;">
            <a href="invoices_hub.php" class="btn-outline">&larr; Back to Hub</a>
        </p>
    <?php endif; ?>
    </div>

</div>
</body>
</html>