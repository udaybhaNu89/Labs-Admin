<?php
include 'db.php';

$message = "";

if (isset($_POST['submit_complaint'])) {
    // 1. Get Dropdown Data
    $lab = $_POST['lab_no'];
    $pc = $_POST['pc_no'];

    // 2. Get Checkbox Data (Common Issues)
    // Since checkboxes return an array, we combine them into a string
    $issue_summary = "";
    if (isset($_POST['issues'])) {
        // "implode" joins array items with a comma
        $issue_summary = implode(", ", $_POST['issues']); 
    }

    // 3. Get Manual Description
    $manual_desc = $_POST['other_desc'];

    // 4. Combine them into one final description string
    // Example Result: "Mouse Not Working, Internet Issue - Details: Cable is broken"
    $final_issue = $issue_summary;
    
    if (!empty($manual_desc)) {
        $final_issue .= " , Others: " . $manual_desc;
    }

    // 5. Save to Database
    // We check if at least one issue was selected or typed
    if (!empty($final_issue)) {
        $sql = "INSERT INTO complaints (lab_number, pc_number, issue_description) VALUES ('$lab', '$pc', '$final_issue')";
        
        if (mysqli_query($conn, $sql)) {
            $message = "Complaint submitted successfully!";
        } else {
            $message = "Error: " . mysqli_error($conn);
        }
    } else {
        $message = "Please select an issue or describe it.";
    }
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
    
    <?php if ($message != "") { echo "<p style='color:green; font-weight:bold;'>$message</p>"; } ?>

    <form method="POST" action="">
        
        <label>Select Lab:</label><br>
        <select name="lab_no" required>
            <option value="">-- Choose Lab --</option>
            <option value="Lab 1">Lab 1 (Programming)</option>
            <option value="Lab 2">Lab 2 (Hardware)</option>
            <option value="Lab 3">Lab 3 (Networking)</option>
        </select>
        <br><br>

        <label>Select PC Number:</label><br>
        <select name="pc_no" required>
            <option value="">-- Choose PC --</option>
            <option value="PC-01">PC-01</option>
            <option value="PC-02">PC-02</option>
            <option value="PC-03">PC-03</option>
            <option value="PC-04">PC-04</option>
            <option value="PC-05">PC-05</option>
            <option value="PC-06">PC-06</option>
            <option value="Server">Main Server</option>
            <option value="Projector">Projector</option>
        </select>
        <br><br>

        <label>Select Issue(s):</label><br>
        <input type="checkbox" name="issues[]" value="Mouse Not Working"> Mouse Not Working<br>
        <input type="checkbox" name="issues[]" value="Keyboard Keys Stuck"> Keyboard Keys Stuck<br>
        <input type="checkbox" name="issues[]" value="Monitor Flickering"> Monitor Flickering<br>
        <input type="checkbox" name="issues[]" value="No Internet"> No Internet Access<br>
        <input type="checkbox" name="issues[]" value="Software Crash"> Software Crash<br>
        <br>

        <label>Other / Details (Optional):</label><br>
        <textarea name="other_desc" rows="3" placeholder="Describe the issue if not listed above..."></textarea><br><br>

        <input type="submit" name="submit_complaint" value="Submit Complaint">
    </form>

</body>
</html>