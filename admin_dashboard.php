<?php
// Start the session (Must be at the very top)
session_start();
include 'db.php';

// GATEKEEPER CHECK:
// If the user is NOT logged in, kick them back to login page
if (!isset($_SESSION['admin_user'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h1>Admin Dashboard</h1>
    
    <div class="nav">
        <a href="admin_dashboard.php">View Complaints</a> | 
        <a href="#">Create New Admin</a> | 
        
        <a href="logout.php" style="color: red;">Logout</a>
    </div>

    <hr>

    <h3>Current Complaints</h3>

    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Lab No</th>
            <th>PC No</th>
            <th>Issue</th>
            <th>Status</th>
            <th>Date</th>
        </tr>

        <?php
        $sql = "SELECT * FROM complaints ORDER BY id DESC";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['lab_number'] . "</td>";
                echo "<td>" . $row['pc_number'] . "</td>";
                echo "<td>" . $row['issue_description'] . "</td>";
                echo "<td>" . $row['status'] . "</td>";
                echo "<td>" . $row['created_at'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No complaints found.</td></tr>";
        }
        ?>
    </table>

</body>
</html>