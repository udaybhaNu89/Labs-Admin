<?php
session_start(); // 1. Start Session
include 'db.php';

$message = "";
$msg_type = "";

// 2. CHECK FOR SESSION MESSAGE
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $msg_type = $_SESSION['msg_type'];
    unset($_SESSION['message']);
    unset($_SESSION['msg_type']);
}

// 3. HANDLE FORM SUBMISSION
if (isset($_POST['submit_complaint'])) {
    $cols = ""; $vals = "";
    
    $res = mysqli_query($conn, "SELECT * FROM dynamic_sections ORDER BY display_order ASC");
    while ($sec = mysqli_fetch_assoc($res)) {
        $col = $sec['column_name'];
        $data = "";
        if (isset($_POST[$col])) {
            $data = is_array($_POST[$col]) ? implode(", ", $_POST[$col]) : $_POST[$col];
        }
        $data = mysqli_real_escape_string($conn, $data);
        $cols .= ", $col"; 
        $vals .= ", '$data'";
    }
    
    $other = mysqli_real_escape_string($conn, $_POST['other_details']);
    $sql = "INSERT INTO complaints (other_details $cols) VALUES ('$other' $vals)";
    
    if (mysqli_query($conn, $sql)) { 
        $_SESSION['message'] = "Your response has been recorded."; 
        $_SESSION['msg_type'] = "success";
        header("Location: complaint.php"); 
        exit(); 
    } else { 
        $message = "Error: " . mysqli_error($conn); 
        $msg_type = "error";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Report Issue</title>
    <style>
        /* THEME SETTINGS */
        :root { --primary: #009688; --primary-dark: #00796b; --bg-body: #f0f4f4; }
        body { background-color: var(--bg-body); font-family: 'Roboto', 'Segoe UI', Arial, sans-serif; margin: 0; padding: 30px 0; color: #202124; }
        .form-container { max-width: 640px; margin: 0 auto; }
        .form-card { background-color: #fff; border: 1px solid #dadce0; border-radius: 8px; padding: 24px; margin-bottom: 12px; position: relative; }
        .form-card:focus-within { border-left: 6px solid var(--primary); padding-left: 18px; }
        .form-header { border-top: 10px solid var(--primary); border-top-left-radius: 8px; border-top-right-radius: 8px; }
        h1 { font-size: 32px; font-weight: 400; margin: 0 0 10px 0; }
        p.desc { font-size: 14px; color: #5f6368; margin-top: 0; }
        label.question-title { font-size: 16px; font-weight: 500; display: block; margin-bottom: 15px; }
        .req { color: #d93025; margin-left: 4px; }
        select, textarea { width: 100%; padding: 10px 0; border: none; border-bottom: 1px solid #e0e0e0; background: transparent; font-family: inherit; font-size: 14px; outline: none; transition: 0.3s; }
        select:focus, textarea:focus { border-bottom: 2px solid var(--primary); background-color: #fafafa; }
        .checkbox-group { display: flex; flex-direction: column; gap: 10px; }
        .checkbox-option { display: flex; align-items: center; font-size: 14px; cursor: pointer; }
        input[type="checkbox"] { margin-right: 15px; width: 18px; height: 18px; cursor: pointer; accent-color: var(--primary); }
        .form-footer { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; }
        .btn-submit { background-color: var(--primary); color: #fff; border: none; border-radius: 4px; padding: 10px 24px; font-size: 14px; font-weight: 500; cursor: pointer; }
        .btn-submit:hover { background-color: var(--primary-dark); box-shadow: 0 1px 2px rgba(0,0,0,0.2); }
        .top-nav { text-align: right; max-width: 640px; margin: 0 auto 10px auto; }
        .top-nav a { color: #5f6368; text-decoration: none; font-size: 13px; }
    </style>
</head>
<body>

    <div class="top-nav">
        <a href="index.php">Back to Home</a>
    </div>

    <div class="form-container">
        
        <div class="form-card form-header">
            <h1>Lab Issue Report</h1>
            <p class="desc">Submit your complaints regarding Labs, PCs, or Infrastructure.</p>
            <p style="color: #d93025; font-size: 12px;">* Required</p>
            
            <?php if ($message): ?>
                <div style="margin-top: 15px; color: <?php echo ($msg_type=='success')? 'var(--primary-dark)' : '#d93025'; ?>; font-weight: bold;">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
        </div>

        <form method="POST">
            
            <?php
            $res = mysqli_query($conn, "SELECT * FROM dynamic_sections ORDER BY display_order ASC");
            while ($sec = mysqli_fetch_assoc($res)) {
                $title = $sec['section_title']; 
                $col = $sec['column_name']; 
                $type = $sec['input_type'];
                
                // FIX 1: Table Name Logic (Remove 'dyn_' to match admin_panel)
                // e.g., 'dyn_room_no' -> 'room_no'
                $table = substr($col, 4); 
                
                echo '<div class="form-card">';
                echo '<label class="question-title">' . $title . ' <span class="req">*</span></label>';
                
                // FIX 2: Check if table exists to prevent crash
                $check_table = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
                
                if (mysqli_num_rows($check_table) > 0) {
                    // Fetch data
                    $options = [];
                    // FIX 3: Backticks added
                    $opts_query = mysqli_query($conn, "SELECT * FROM `$table`");
                    while ($r = mysqli_fetch_assoc($opts_query)) {
                        $options[] = $r;
                    }

                    // FIX 4: Natural Sort (PC1, PC3, PC12)
                    usort($options, function($a, $b) {
                        return strnatcasecmp($a['name'], $b['name']);
                    });

                    // Render Inputs
                    if ($type == 'dropdown') {
                        echo "<select name='$col' required>";
                        echo "<option value='' disabled selected>Choose</option>";
                        foreach ($options as $r) { 
                            echo "<option value='".$r['name']."'>".$r['name']."</option>"; 
                        }
                        echo "</select>";
                    } else {
                        echo '<div class="checkbox-group">';
                        if (count($options) > 0) {
                            foreach ($options as $r) { 
                                echo '<label class="checkbox-option">';
                                echo "<input type='checkbox' name='{$col}[]' value='".$r['name']."'>";
                                echo $r['name'];
                                echo '</label>';
                            }
                        } else { 
                            echo "<span style='color:#999; font-size:13px;'>No options found.</span>"; 
                        }
                        echo '</div>';
                    }
                } else {
                    echo "<p style='color:red; font-size:13px;'>Error: Configuration table not found.</p>";
                }
                echo '</div>';
            }
            ?>

            <div class="form-card">
                <label class="question-title">Other Details</label>
                <textarea name="other_details" rows="1" placeholder="Your answer"></textarea>
            </div>

            <div class="form-footer">
                <input type="submit" name="submit_complaint" value="Submit" class="btn-submit">
                <div style="font-size: 12px; color: var(--primary);">Clear form</div>
            </div>

        </form>
        
        <br><br>
        <center style="font-size: 12px; color: #5f6368;">
            This content is created by the Lab Admin.
        </center>
    </div>

</body>
</html>