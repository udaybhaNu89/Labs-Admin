<?php
session_start();
include 'db.php';
if (!isset($_SESSION['admin_user'])) { header("Location: admin_login.php"); exit(); }

// Handle Delete Logic
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    mysqli_query($conn, "DELETE FROM complaints WHERE id = $id");
    header("Location: admin_dashboard.php"); exit();
}

// Fetch dynamic sections in correct order for table headers
$sections = [];
$res = mysqli_query($conn, "SELECT * FROM dynamic_sections ORDER BY display_order ASC");
while ($row = mysqli_fetch_assoc($res)) { $sections[] = $row; }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* General Page Styling */
        body { font-family: sans-serif; padding: 20px; background-color: #f4f6f9; }
        
        /* Navigation Bar */
        .nav-bar { background-color: #34495e; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .nav-bar a { color: white; margin-right: 20px; text-decoration: none; font-weight: bold; }
        .nav-bar a:hover { text-decoration: underline; }
        .logout-link { color: #e74c3c !important; float: right; }

        /* Table Styling */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
            background: white; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
        }

        /* CENTER CONTENT HERE */
        th, td { 
            border: 1px solid #ddd; 
            padding: 12px; 
            text-align: center;    /* Centers text horizontally */
            vertical-align: middle; /* Centers text vertically */
        }

        th { background-color: #27ae60; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        tr:hover { background-color: #f1f1f1; }

        /* Delete Button */
        .btn-delete { 
            background-color: #e74c3c; 
            color: white; 
            text-decoration: none; 
            padding: 6px 12px; 
            border-radius: 4px; 
            font-size: 13px; 
            font-weight: bold;
        }
        .btn-delete:hover { background-color: #c0392b; }
    </style>
</head>
<body>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
        <h1 style="color:#2c3e50;">Admin Dashboard</h1>
        <h3 style="color:#7f8c8d;">Welcome, <?php echo $_SESSION['admin_user']; ?>!</h3>
    </div>

    <div class="nav-bar">
        <a href="admin_dashboard.php">View Complaints</a>
        <a href="complaint_config.php">Manage Options</a>
        <a href="create_admin.php">New Admin</a>
        <a href="logout.php" class="logout-link">Logout</a>
    </div>
    
    <h3>Current Complaints List</h3>
    
    <table>
        <tr>
            <?php foreach ($sections as $sec) { echo "<th>" . $sec['section_title'] . "</th>"; } ?>
            
            <th>Other Details</th>
            <th>Status</th>
            <th>Date</th>
            <th>Action</th>
        </tr>

        <?php
        $sql = "SELECT * FROM complaints ORDER BY id DESC";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                
                // 1. Dynamic Columns Data
                foreach ($sections as $sec) {
                    $col = $sec['column_name'];
                    // Show hyphen if empty
                    $val = (!empty($row[$col])) ? $row[$col] : "-";
                    echo "<td>" . $val . "</td>";
                }
                
                // 2. Other Details
                $other = (!empty($row['other_details'])) ? $row['other_details'] : "-";
                echo "<td>" . $other . "</td>";
                
                // 3. Standard Columns
                echo "<td>" . $row['status'] . "</td>";
                echo "<td>" . $row['created_at'] . "</td>";
                echo "<td><a href='admin_dashboard.php?delete_id=" . $row['id'] . "' class='btn-delete''>Delete</a></td>";
                echo "</tr>";
            }
        } else { 
            // Calculate colspan dynamically based on number of columns
            $colspan = count($sections) + 4; 
            echo "<tr><td colspan='$colspan' style='text-align:center; padding: 20px; color: #777;'>No complaints found.</td></tr>"; 
        }
        ?>
    </table>
</body>
</html>