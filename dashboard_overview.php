<?php
require 'auth_session.php';
include 'header.php';

// ====================================================
// 1. CALCULATE STATS (COMPLAINTS)
// ====================================================
$stats_sql = "SELECT 
            COUNT(*) as total_complaints,
            SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as total_pending,
            SUM(CASE WHEN status LIKE 'Partially Completed%' THEN 1 ELSE 0 END) as total_partial,
            SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) as total_completed
        FROM complaints_log main
        WHERE id = (
            SELECT MAX(sub.id)
            FROM complaints_log sub
            WHERE sub.parent_id = main.parent_id
        )";

$stats_res = mysqli_query($conn, $stats_sql);
$stats = mysqli_fetch_assoc($stats_res);

$count_total   = $stats['total_complaints'] ?? 0;
$count_pending = $stats['total_pending'] ?? 0;
$count_partial = $stats['total_partial'] ?? 0;
$count_completed = $stats['total_completed'] ?? 0;

// --- ACTIVE COMPLAINTS (Pending + Partial) ---
$count_active = $count_pending + $count_partial;

// ====================================================
// 2. CALCULATE STATS (LABS)
// ====================================================
$labs_sql = "SELECT COUNT(*) as total_labs FROM labs_unit";
$labs_res = mysqli_query($conn, $labs_sql);
$labs_data = mysqli_fetch_assoc($labs_res);
$count_labs = $labs_data['total_labs'] ?? 0;

// ====================================================
// 3. DETERMINE CURRENT VIEW
// ====================================================
$view = isset($_GET['view']) ? $_GET['view'] : ''; 

$table_title = "";
$is_labs_view = false;

if($view == 'all') $table_title = "All Complaints Log";
elseif($view == 'active') $table_title = "Active Complaints (Pending & Partial)";
elseif($view == 'completed') $table_title = "Completed Complaints Log";
elseif($view == 'labs') {
    $table_title = "Total Labs Information";
    $is_labs_view = true;
}

// ====================================================
// 4. FETCH TABLE DATA
// ====================================================
$table_rows = [];
$sections = []; 

if ($view) {
    if ($is_labs_view) {
        // --- A. LABS DATA FETCHING ---
        
        // 1. Fetch Dynamic Headers (Sorted by Priority from Manager)
        $sec_res = mysqli_query($conn, "SELECT * FROM labs_sections ORDER BY display_order ASC");
        while ($row = mysqli_fetch_assoc($sec_res)) { $sections[] = $row; }
        
        // 2. Fetch All Rows (No SQL Order - We sort in PHP for Natural Order)
        $data_sql = "SELECT * FROM labs_unit";
        $data_res = mysqli_query($conn, $data_sql);
        while($row = mysqli_fetch_assoc($data_res)) {
            $table_rows[] = $row;
        }

        // 3. Apply Natural Sort by 'lab_name'
        // This ensures 'Lab 2' comes before 'Lab 10'
        usort($table_rows, function($a, $b) {
            $nameA = isset($a['lab_name']) ? $a['lab_name'] : '';
            $nameB = isset($b['lab_name']) ? $b['lab_name'] : '';
            return strnatcasecmp($nameA, $nameB);
        });

    } else {
        // --- B. COMPLAINTS DATA FETCHING ---
        $sec_res = mysqli_query($conn, "SELECT * FROM dynamic_sections ORDER BY display_order ASC");
        while ($row = mysqli_fetch_assoc($sec_res)) { $sections[] = $row; }

        $data_sql = "SELECT * FROM complaints_log c1
                     WHERE c1.id = (
                        SELECT MAX(c2.id)
                        FROM complaints_log c2
                        WHERE c2.parent_id = c1.parent_id
                     )";

        if ($view == 'active') {
            $data_sql .= " AND (status = 'Pending' OR status LIKE 'Partially Completed%')";
        } elseif ($view == 'completed') {
            $data_sql .= " AND status = 'Completed'";
        }

        $data_sql .= " ORDER BY c1.id DESC";
        
        $data_res = mysqli_query($conn, $data_sql);
        while($row = mysqli_fetch_assoc($data_res)) {
            $table_rows[] = $row;
        }
    }
}
?>

<style>
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 25px;
        margin-top: 30px;
        margin-bottom: 40px;
    }
    .card-link { text-decoration: none; color: inherit; display: block; }
    .stat-card {
        background: #fff; padding: 30px; border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-left: 6px solid #ccc;
        transition: transform 0.2s, box-shadow 0.2s; cursor: pointer;
    }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
    .stat-title { color: #7f8c8d; font-size: 15px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; margin-bottom: 15px; }
    .stat-number { font-size: 42px; font-weight: 700; color: #2c3e50; }
    
    .card-total { border-left-color: #3498db; } .card-total .stat-number { color: #3498db; }
    .card-active { border-left-color: #e67e22; } .card-active .stat-number { color: #e67e22; }
    .card-completed { border-left-color: #27ae60; } .card-completed .stat-number { color: #27ae60; }
    .card-labs { border-left-color: #9b59b6; } .card-labs .stat-number { color: #9b59b6; }
    
    .status-pending { color: #e67e22; font-weight: bold; background: #fff3e0; padding: 4px 8px; border-radius: 4px; font-size: 12px; }
    .status-completed { color: #27ae60; font-weight: bold; background: #e8f5e9; padding: 4px 8px; border-radius: 4px; font-size: 12px; }
    .status-partial { color: #f39c12; font-weight: bold; background: #fef9e7; padding: 4px 8px; border-radius: 4px; font-size: 12px; border: 1px solid #f39c12; }
    
    .active-card .stat-card { background-color: #f8f9fa; border-bottom: 4px solid #333; }
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
    <h1><strong>Dashboard Overview</strong></h1>
    <h3 style="color:#555; font-weight:400; margin:0; font-size:20px;">
        Welcome, <?php echo isset($_SESSION['admin_user']) ? htmlspecialchars($_SESSION['admin_user']) : 'Admin'; ?>!
    </h3>
</div>

<hr>

<div class="stats-container">
    
    <a href="dashboard_overview.php?view=labs" class="card-link <?php echo ($view=='labs')?'active-card':''; ?>">
        <div class="stat-card card-labs">
            <div class="stat-title">Total Labs / Info</div>
            <div class="stat-number"><?php echo $count_labs; ?></div>
        </div>
    </a>

    <a href="dashboard_overview.php?view=all" class="card-link <?php echo ($view=='all')?'active-card':''; ?>">
        <div class="stat-card card-total">
            <div class="stat-title">Total Complaints</div>
            <div class="stat-number"><?php echo $count_total; ?></div>
        </div>
    </a>

    <a href="dashboard_overview.php?view=active" class="card-link <?php echo ($view=='active')?'active-card':''; ?>">
        <div class="stat-card card-active">
            <div class="stat-title">Active Complaints</div>
            <div class="stat-number"><?php echo $count_active; ?></div>
        </div>
    </a>

    <a href="dashboard_overview.php?view=completed" class="card-link <?php echo ($view=='completed')?'active-card':''; ?>">
        <div class="stat-card card-completed">
            <div class="stat-title">Completed</div>
            <div class="stat-number"><?php echo $count_completed; ?></div>
        </div>
    </a>

</div>

<?php if ($view): ?>
    <div id="data-table-section">
        <h2 style="color:var(--primary); margin-bottom:15px; border-left:5px solid var(--primary); padding-left:15px;">
            <?php echo $table_title; ?>
        </h2>

        <?php if (count($table_rows) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <?php if ($is_labs_view): ?>
                            <?php foreach ($sections as $sec) { echo "<th>" . $sec['section_title'] . "</th>"; } ?>
                        
                        <?php else: ?>
                            <?php foreach ($sections as $sec) { echo "<th>" . $sec['section_title'] . "</th>"; } ?>
                            <th>Complaint Description</th>
                            <th>Status</th>
                            <th>Date Reported</th>
                            <th>Last Update</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($table_rows as $row): ?>
                        <tr>
                            <?php if ($is_labs_view): ?>
                                <?php foreach ($sections as $sec) {
                                    $col = $sec['column_name'];
                                    echo "<td>" . ((!empty($row[$col])) ? $row[$col] : "-") . "</td>";
                                } ?>

                            <?php else: ?>
                                <?php foreach ($sections as $sec) {
                                    $col = $sec['column_name'];
                                    echo "<td>" . ((!empty($row[$col])) ? $row[$col] : "-") . "</td>";
                                } ?>
                                
                                <td><?php echo (!empty($row['other_details'])) ? $row['other_details'] : "-"; ?></td>
                                
                                <td>
                                    <?php 
                                    $s = $row['status'];
                                    if($s == 'Pending') echo "<span class='status-pending'>Pending</span>";
                                    elseif($s == 'Completed') echo "<span class='status-completed'>Completed</span>";
                                    elseif(strpos($s, 'Partially Completed') === 0) echo "<span class='status-partial'>".htmlspecialchars($s)."</span>";
                                    else echo $s;
                                    ?>
                                </td>
                                
                                <td><?php echo $row['created_at']; ?></td>
                                
                                <td>
                                    <?php 
                                    if(!empty($row['issue_fixed_at'])) echo $row['issue_fixed_at'] . " (Fixed)";
                                    elseif(!empty($row['partially_completed_at'])) echo $row['partially_completed_at'] . " (Partial)";
                                    else echo "-";
                                    ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div style="text-align:center; padding:30px; border:1px solid #eee; background:#fff; color:#999; border-radius:8px;">
                <h4>No records found.</h4>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<script>
    setTimeout(function() {
        window.location.reload();
    }, 45000); // 45000 milliseconds = 45 seconds
</script>

</div> 
</body>
</html>