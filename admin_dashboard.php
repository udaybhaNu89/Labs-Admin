<?php
require 'auth_session.php';

// --- DASHBOARD ACTIONS ---
if (isset($_GET['delete_complaint'])) {
    $id = intval($_GET['delete_complaint']);
    mysqli_query($conn, "DELETE FROM complaints WHERE id = $id");
    $_SESSION['sys_msg'] = "Complaint Deleted"; $_SESSION['sys_msg_color'] = "green";
    header("Location: admin_dashboard.php"); exit();
}

if (isset($_GET['mark_complete'])) {
    $id = intval($_GET['mark_complete']);
    
    // --- 1. EMAIL NOTIFICATION LOGIC START ---
    
    // Fetch the specific complaint data
    $comp_query = mysqli_query($conn, "SELECT * FROM complaints WHERE id = $id");
    $comp_data = mysqli_fetch_assoc($comp_query);
    
    if ($comp_data) {
        // Find which column contains the email address (from dynamic_sections)
        $col_query = mysqli_query($conn, "SELECT column_name FROM dynamic_sections WHERE input_type = 'email' LIMIT 1");
        $col_row = mysqli_fetch_assoc($col_query);
        
        // If an email column exists and the complaint has an email value
        if ($col_row && !empty($comp_data[$col_row['column_name']])) {
            $to_email = $comp_data[$col_row['column_name']];
            $fixed_time = date("Y-m-d H:i:s"); // Current time
            
            $email_subject = "Update: Lab Issue Fixed";
            $email_body = "Your complaint regarding the Lab Issue has been resolved.\n\n";
            $email_body .= "--- Complaint Details ---\n";
            
            // Reconstruct the details list
            $sec_res = mysqli_query($conn, "SELECT * FROM dynamic_sections ORDER BY display_order ASC");
            while ($sec = mysqli_fetch_assoc($sec_res)) {
                $col = $sec['column_name'];
                $title = $sec['section_title'];
                $val = isset($comp_data[$col]) ? $comp_data[$col] : '-';
                $email_body .= "$title: $val\n";
            }
            
            $email_body .= "Other Details: " . $comp_data['other_details'] . "\n";
            $email_body .= "----------------\n";
            $email_body .= "Date Reported: " . $comp_data['created_at'] . "\n";
            $email_body .= "Status: Issue Fixed\n";
            $email_body .= "Issue Fixed At: " . $fixed_time . "\n";
            
            $headers = "From: no-reply@labsystem.com"; // Replace with your admin email
            
            // Send the email
            @mail($to_email, $email_subject, $email_body, $headers);
        }
    }
    // --- EMAIL NOTIFICATION LOGIC END ---

    // MODIFIED: Update status AND set issue_fixed_at to current timestamp
    mysqli_query($conn, "UPDATE complaints SET status = 'Completed', issue_fixed_at = NOW() WHERE id = $id");
    
    $_SESSION['sys_msg'] = "Status Updated to Completed (User Notified)"; $_SESSION['sys_msg_color'] = "green";
    header("Location: admin_dashboard.php"); exit();
}

include 'header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1><strong>Admin Dashboard</strong></h1>
    <h3 style="color:#777; font-weight:400;">Welcome, <?php echo $_SESSION['admin_user']; ?>!</h3>
</div>

<?php
$sections = [];
$res = mysqli_query($conn, "SELECT * FROM dynamic_sections ORDER BY display_order ASC");
while ($row = mysqli_fetch_assoc($res)) { $sections[] = $row; }
$sql = "SELECT * FROM complaints ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
$num_complaints = mysqli_num_rows($result);
?>

<?php if ($num_complaints > 0): ?>
    <table>
        <tr>
            <?php foreach ($sections as $sec) { echo "<th>" . $sec['section_title'] . "</th>"; } ?>
            <th>Other Details</th>
            <th class="col-status">Status</th>
            <th>Date Reported</th>
            <th>Issue Fixed At</th>
            <th>Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <?php foreach ($sections as $sec) {
                    $col = $sec['column_name'];
                    $val = (!empty($row[$col])) ? $row[$col] : "-";
                    echo "<td>" . $val . "</td>";
                } ?>
                <td><?php echo (!empty($row['other_details'])) ? $row['other_details'] : "-"; ?></td>
                <td class='col-status'>
                    <?php if($row['status'] == 'Pending'): ?>
                        <span class='status-pending'>Pending</span>
                        <a href='admin_dashboard.php?mark_complete=<?php echo $row['id']; ?>' class='btn-complete' title='Mark as Completed'>Mark as Completed</a>
                    <?php else: ?>
                        <span class='status-completed'>Completed</span>
                    <?php endif; ?>
                </td>
                <td><?php echo $row['created_at']; ?></td>
                <td>
                    <?php 
                    if (!empty($row['issue_fixed_at'])) {
                        echo $row['issue_fixed_at']; 
                    } else {
                        echo "-";
                    }
                    ?>
                </td>
                <td><a href='admin_dashboard.php?delete_complaint=<?php echo $row['id']; ?>' class='btn-delete' onclick='return confirm("Are you sure?");'>Delete</a></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <div style="text-align:center; padding:40px; border:1px solid #eee; border-radius:8px; background:#fff; color:#777;">
        <h3>No Complaints Found</h3>
    </div>
<?php endif; ?>

</div>
</body>
</html>