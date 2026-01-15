<?php
session_start();
include 'db.php';

// Security Check
if (!isset($_SESSION['admin_user'])) { header("Location: admin_login.php"); exit(); }

// ==========================================
// 1. LOGIC TO CREATE A BRAND NEW SECTION
// ==========================================
if (isset($_POST['create_new_section'])) {
    $title = $_POST['section_title']; // e.g., "Department"
    
    // Create a safe database column name (e.g., "department")
    // Remove spaces, lowercase, add prefix to avoid conflicts
    $col_name = "dyn_" . strtolower(str_replace(' ', '_', $title)); 
    $option_table = "opts_" . $col_name; // Table to store options: opts_dyn_department

    // A. Add to Master List
    mysqli_query($conn, "INSERT INTO dynamic_sections (section_title, column_name) VALUES ('$title', '$col_name')");

    // B. Create a new Table to store options for this section
    $sql_create = "CREATE TABLE $option_table (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100))";
    mysqli_query($conn, $sql_create);

    // C. Add a new Column to the main Complaints table
    $sql_alter = "ALTER TABLE complaints ADD COLUMN $col_name VARCHAR(255)";
    mysqli_query($conn, $sql_alter);

    header("Location: complaint_config.php");
    exit();
}

// ==========================================
// 2. LOGIC TO ADD/REMOVE OPTIONS INSIDE SECTIONS
// ==========================================

// Add Option Logic
if (isset($_POST['add_option'])) {
    $table_name = "opts_" . $_POST['target_col']; // e.g., opts_dyn_department
    $val = $_POST['new_val'];
    mysqli_query($conn, "INSERT INTO $table_name (name) VALUES ('$val')");
}

// Remove Option Logic
if (isset($_GET['del_opt_id']) && isset($_GET['target'])) {
    $table_name = "opts_" . $_GET['target'];
    $id = $_GET['del_opt_id'];
    mysqli_query($conn, "DELETE FROM $table_name WHERE id=$id");
    header("Location: complaint_config.php");
    exit();
}

// Standard Logic (Labs/PCs/Issues)
if (isset($_POST['add_lab'])) { mysqli_query($conn, "INSERT INTO labs (name) VALUES ('".$_POST['new_lab']."')"); }
if (isset($_POST['add_pc'])) { mysqli_query($conn, "INSERT INTO pcs (name) VALUES ('".$_POST['new_pc']."')"); }
if (isset($_POST['add_issue'])) { mysqli_query($conn, "INSERT INTO issue_types (name) VALUES ('".$_POST['new_issue']."')"); }

// Standard Delete Logic
if (isset($_GET['del_lab'])) { mysqli_query($conn, "DELETE FROM labs WHERE id=".$_GET['del_lab']); header("Location: complaint_config.php"); }
if (isset($_GET['del_pc'])) { mysqli_query($conn, "DELETE FROM pcs WHERE id=".$_GET['del_pc']); header("Location: complaint_config.php"); }
if (isset($_GET['del_issue'])) { mysqli_query($conn, "DELETE FROM issue_types WHERE id=".$_GET['del_issue']); header("Location: complaint_config.php"); }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modify Complaint Page</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .column { float: left; width: 30%; padding: 15px; background: white; border: 1px solid #ddd; margin: 1%; border-radius: 5px; box-sizing: border-box; }
        .row:after { content: ""; display: table; clear: both; }
        .header-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 2px solid #f2f2f2; padding-bottom: 10px; }
        .header-row h3 { margin: 0; }
        .btn-toggle { background-color: #008CBA; color: white; border: none; padding: 6px 12px; font-size: 13px; cursor: pointer; border-radius: 3px; }
        .button-group { display: flex; gap: 5px; margin-top: 5px; }
        .btn-add { flex: 1; background-color: #28a745; color: white; border: none; padding: 8px; cursor: pointer; border-radius: 3px; }
        .btn-cancel { flex: 1; background-color: #6c757d; color: white; border: none; padding: 8px; cursor: pointer; border-radius: 3px; }
        ul { padding: 0; margin-top: 10px; list-style-type: none; }
        li { background: #fff; border-bottom: 1px solid #eee; padding: 8px 5px; display: flex; justify-content: space-between; align-items: center; }
        .btn-remove { background-color: #ff4d4d; color: white; text-decoration: none; font-size: 11px; padding: 4px 10px; border-radius: 3px; border: 1px solid #cc0000; font-family: sans-serif; }
        
        /* New Section Box */
        .create-section-box { background: #e3f2fd; padding: 20px; border: 2px dashed #2196F3; margin-bottom: 20px; text-align: center; }
    </style>
    <script>
        function toggleForm(formId) {
            var x = document.getElementById(formId);
            x.style.display = (x.style.display === "none") ? "block" : "none";
        }
    </script>
</head>
<body>

    <h1>Manage Complaint Page Options</h1>
    <a href="admin_dashboard.php">Back to Dashboard</a>
    <hr>

    <div class="create-section-box">
        <h3>Need a new category?</h3>
        <button class="btn-toggle" onclick="toggleForm('new_sec_form')">+ Create New Section</button>
        
        <div id="new_sec_form" style="display:none; margin-top:15px; width: 50%; margin-left: auto; margin-right: auto;">
            <form method="POST">
                <input type="text" name="section_title" placeholder="e.g. Department, Priority, Room Type" required>
                <div class="button-group">
                    <input type="submit" name="create_new_section" value="Create Section" class="btn-add">
                    <button type="button" class="btn-cancel" onclick="toggleForm('new_sec_form')">Cancel</button>
                </div>
            </form>
            <p style="font-size:12px; color: #666;">(This will add a new column to the database and dashboard)</p>
        </div>
    </div>

    <div class="row">
        
        <div class="column">
            <div class="header-row"><h3>Labs</h3><button class="btn-toggle" onclick="toggleForm('form_lab')">+ Add New</button></div>
            <div id="form_lab" style="display:none;"><form method="POST"><input type="text" name="new_lab" required><div class="button-group"><input type="submit" name="add_lab" value="Add" class="btn-add"><button type="button" class="btn-cancel" onclick="toggleForm('form_lab')">Cancel</button></div></form></div>
            <ul><?php $r=mysqli_query($conn,"SELECT * FROM labs"); while($row=mysqli_fetch_assoc($r)) echo "<li><span>".$row['name']."</span><a href='complaint_config.php?del_lab=".$row['id']."' class='btn-remove'>Remove</a></li>"; ?></ul>
        </div>

        <div class="column">
            <div class="header-row"><h3>PCs</h3><button class="btn-toggle" onclick="toggleForm('form_pc')">+ Add New</button></div>
            <div id="form_pc" style="display:none;"><form method="POST"><input type="text" name="new_pc" required><div class="button-group"><input type="submit" name="add_pc" value="Add" class="btn-add"><button type="button" class="btn-cancel" onclick="toggleForm('form_pc')">Cancel</button></div></form></div>
            <ul><?php $r=mysqli_query($conn,"SELECT * FROM pcs"); while($row=mysqli_fetch_assoc($r)) echo "<li><span>".$row['name']."</span><a href='complaint_config.php?del_pc=".$row['id']."' class='btn-remove'>Remove</a></li>"; ?></ul>
        </div>

        <div class="column">
            <div class="header-row"><h3>Issues</h3><button class="btn-toggle" onclick="toggleForm('form_issue')">+ Add New</button></div>
            <div id="form_issue" style="display:none;"><form method="POST"><input type="text" name="new_issue" required><div class="button-group"><input type="submit" name="add_issue" value="Add" class="btn-add"><button type="button" class="btn-cancel" onclick="toggleForm('form_issue')">Cancel</button></div></form></div>
            <ul><?php $r=mysqli_query($conn,"SELECT * FROM issue_types"); while($row=mysqli_fetch_assoc($r)) echo "<li><span>".$row['name']."</span><a href='complaint_config.php?del_issue=".$row['id']."' class='btn-remove'>Remove</a></li>"; ?></ul>
        </div>

        <?php
        $dyn_query = mysqli_query($conn, "SELECT * FROM dynamic_sections");
        while ($sec = mysqli_fetch_assoc($dyn_query)) {
            $title = $sec['section_title'];
            $col = $sec['column_name'];
            $table = "opts_" . $col;
            $form_id = "form_" . $col;
        ?>
            <div class="column">
                <div class="header-row">
                    <h3><?php echo $title; ?></h3>
                    <button class="btn-toggle" onclick="toggleForm('<?php echo $form_id; ?>')">+ Add New</button>
                </div>
                
                <div id="<?php echo $form_id; ?>" style="display:none; padding:10px;">
                    <form method="POST">
                        <input type="hidden" name="target_col" value="<?php echo $col; ?>">
                        <input type="text" name="new_val" placeholder="New Option" required>
                        <div class="button-group">
                            <input type="submit" name="add_option" value="Add" class="btn-add">
                            <button type="button" class="btn-cancel" onclick="toggleForm('<?php echo $form_id; ?>')">Cancel</button>
                        </div>
                    </form>
                </div>

                <ul>
                    <?php
                    // Fetch options from the specific dynamic table
                    $opts = mysqli_query($conn, "SELECT * FROM $table");
                    while ($opt_row = mysqli_fetch_assoc($opts)) {
                        echo "<li>
                                <span>" . $opt_row['name'] . "</span>
                                <a href='complaint_config.php?del_opt_id=".$opt_row['id']."&target=".$col."' class='btn-remove'>Remove</a>
                              </li>";
                    }
                    ?>
                </ul>
            </div>
        <?php } ?>

    </div>
</body>
</html>