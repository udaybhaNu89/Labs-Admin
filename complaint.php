<?php
include 'db.php';
$message = "";

if (isset($_POST['submit_complaint'])) {
    $lab = $_POST['lab_no'];
    $pc = $_POST['pc_no'];
    
    // Checkbox Logic
    $issue_summary = "";
    if (isset($_POST['issues'])) { $issue_summary = implode(", ", $_POST['issues']); }
    $manual = $_POST['other_desc'];
    $final = $issue_summary;
    if (!empty($manual)) { $final .= " - Details: " . $manual; }

    // DYNAMIC COLUMNS LOGIC
    // We need to build the SQL query dynamically based on what sections exist
    $col_names = "lab_number, pc_number, issue_description";
    $col_values = "'$lab', '$pc', '$final'";

    // Check for dynamic fields
    $dyn_res = mysqli_query($conn, "SELECT * FROM dynamic_sections");
    while ($sec = mysqli_fetch_assoc($dyn_res)) {
        $col = $sec['column_name'];
        if (isset($_POST[$col])) {
            $val = $_POST[$col];
            $col_names .= ", " . $col; // Add column name to SQL
            $col_values .= ", '" . $val . "'"; // Add value to SQL
        }
    }

    if (!empty($final)) {
        $sql = "INSERT INTO complaints ($col_names) VALUES ($col_values)";
        if (mysqli_query($conn, $sql)) { $message = "Submitted successfully!"; } 
        else { $message = "Error: " . mysqli_error($conn); }
    } else { $message = "Please describe the issue."; }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Report Issue</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Raise a Complaint</h2>
    <a href="index.php">Back to Home</a>
    <?php if ($message) echo "<p style='color:green; font-weight:bold;'>$message</p>"; ?>

    <form method="POST">
        <label>Select Lab:</label>
        <select name="lab_no" required>
            <option value="">-- Choose --</option>
            <?php $r=mysqli_query($conn,"SELECT * FROM labs"); while($row=mysqli_fetch_assoc($r)) echo "<option value='".$row['name']."'>".$row['name']."</option>"; ?>
        </select>

        <label>Select PC:</label>
        <select name="pc_no" required>
            <option value="">-- Choose --</option>
            <?php $r=mysqli_query($conn,"SELECT * FROM pcs"); while($row=mysqli_fetch_assoc($r)) echo "<option value='".$row['name']."'>".$row['name']."</option>"; ?>
        </select>

        <?php
        $dyn_res = mysqli_query($conn, "SELECT * FROM dynamic_sections");
        while ($sec = mysqli_fetch_assoc($dyn_res)) {
            $title = $sec['section_title'];
            $col = $sec['column_name'];
            $table = "opts_" . $col;
            
            echo "<label>Select $title:</label><br>";
            echo "<select name='$col' required>";
            echo "<option value=''>-- Choose $title --</option>";
            
            $opts = mysqli_query($conn, "SELECT * FROM $table");
            while ($opt = mysqli_fetch_assoc($opts)) {
                echo "<option value='".$opt['name']."'>".$opt['name']."</option>";
            }
            echo "</select><br><br>";
        }
        ?>

        <label>Common Issues:</label><br>
        <?php $r=mysqli_query($conn,"SELECT * FROM issue_types"); while($row=mysqli_fetch_assoc($r)) echo "<input type='checkbox' name='issues[]' value='".$row['name']."'> ".$row['name']."<br>"; ?>
        <br>

        <label>Other Details:</label>
        <textarea name="other_desc" rows="3"></textarea>
        <input type="submit" name="submit_complaint" value="Submit">
    </form>
</body>
</html>