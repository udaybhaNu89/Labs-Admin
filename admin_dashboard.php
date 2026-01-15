<?php
session_start();
include 'db.php';
if (!isset($_SESSION['admin_user'])) { header("Location: admin_login.php"); exit(); }

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    mysqli_query($conn, "DELETE FROM complaints WHERE id = $id");
    header("Location: admin_dashboard.php"); exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .btn-delete { background-color: #f44336; color: white; text-decoration: none; padding: 5px 10px; border-radius: 4px; font-size: 14px; }
    </style>
</head>
<body>
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Admin Dashboard</h1>
        <h3>Welcome, <?php echo $_SESSION['admin_user']; ?>!</h3>
    </div>

    <div class="nav" style="background-color: #333; padding: 10px;">
        <a href="admin_dashboard.php" style="color: white; margin-right: 20px;">View Complaints</a>
        <a href="complaint_config.php" style="color: white; margin-right: 20px;">Modify Complaint Page</a>
        <a href="create_admin.php" style="color: white; margin-right: 20px;">Create New Admin</a>
        <a href="logout.php" style="color: #ff6666; font-weight: bold;">Logout</a>
    </div>

    <hr>
    <h3>Current Complaints List</h3>

    <table>
        <tr>
            <th>Lab No</th>
            <th>PC No</th>
            
            <?php
            $dyn_secs = []; // Store them to use again in the row loop
            $res = mysqli_query($conn, "SELECT * FROM dynamic_sections");
            while ($sec = mysqli_fetch_assoc($res)) {
                $dyn_secs[] = $sec; // Save the section details
                echo "<th>" . $sec['section_title'] . "</th>";
            }
            ?>

            <th>Issue Description</th>
            <th>Status</th>
            <th>Reported Date</th>
            <th>Action</th>
        </tr>

        <?php
        $sql = "SELECT * FROM complaints ORDER BY id DESC";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['lab_number'] . "</td>";
                echo "<td>" . $row['pc_number'] . "</td>";

                // DYNAMIC DATA CELLS
                // Loop through the same sections to find the data in $row
                foreach ($dyn_secs as $sec) {
                    $col = $sec['column_name'];
                    // If the column exists in the row, show it, else show empty
                    echo "<td>" . (isset($row[$col]) ? $row[$col] : "-") . "</td>";
                }

                echo "<td>" . $row['issue_description'] . "</td>";
                echo "<td>" . $row['status'] . "</td>";
                echo "<td>" . $row['created_at'] . "</td>";
                echo "<td><a href='admin_dashboard.php?delete_id=" . $row['id'] . "' class='btn-delete'>Remove</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='10' style='text-align:center;'>No complaints found.</td></tr>";
        }
        ?>
    </table>
</body>
</html>