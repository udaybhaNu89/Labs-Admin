<?php
// Get current file name for active state
$current_page = basename($_SERVER['PHP_SELF']);

// Check for system messages from session (set by other pages)
$sys_msg = "";
$sys_msg_color = "";
if (isset($_SESSION['sys_msg'])) {
    $sys_msg = $_SESSION['sys_msg'];
    $sys_msg_color = isset($_SESSION['sys_msg_color']) ? $_SESSION['sys_msg_color'] : 'green';
    unset($_SESSION['sys_msg']); // Clear message after displaying once
    unset($_SESSION['sys_msg_color']);
}

// =========================================================================
// SECURITY & ACCESS CONTROL LAYER
// =========================================================================

// 1. Get Current User Permissions
// We assume $conn is available because header.php is included after db connection
if (isset($_SESSION['admin_user'])) {
    $safe_user = mysqli_real_escape_string($conn, $_SESSION['admin_user']);
    $perm_query = mysqli_query($conn, "SELECT permissions FROM admins WHERE username = '$safe_user' LIMIT 1");
    
    // Default to 'Partial' (Least Privilege Principle) if query fails or user not found
    $user_permission = 'Partial'; 
    
    if ($perm_query && mysqli_num_rows($perm_query) > 0) {
        $p_row = mysqli_fetch_assoc($perm_query);
        // Ensure strictly 'Full' or 'Partial'
        $user_permission = ($p_row['permissions'] === 'Full') ? 'Full' : 'Partial'; 
    }
} else {
    // If no session user, redirect to login (Fallback security)
    header("Location: login.php");
    exit();
}

// 2. Define Restricted Pages (Pages only FULL Admins can access)
$restricted_pages = [
    'labs_hub.php', 
    'labs_info_form.php', 
    'labs_info_manager.php', 
    'labs_info_logs.php', 
    'labs_info.php',
    'systems_info_manager.php',
    'systems_info_form.php',
    'manage_config.php',
    'invoices_hub.php', 
    'invoices_management.php', 
    'invoices_log.php',
    'export_hub.php',
    'export_labs.php',
    'export_lab_systems.php',
    'export_systems.php',
    'manage_admins.php'
];

// 3. Enforce Access Control (Broken Access Control Protection)
// If the user has 'Partial' permission AND acts on a restricted page -> DENY ACCESS
if ($user_permission === 'Partial' && in_array($current_page, $restricted_pages)) {
    // Optional: Set an error message before redirect
    $_SESSION['sys_msg'] = "Access Denied: You do not have permission to view that page.";
    $_SESSION['sys_msg_color'] = "red";
    
    // Redirect to a safe page
    header("Location: complaints_info.php");
    exit();
}
// =========================================================================
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <style>
        :root { --primary: #009688; --primary-dark: #00796b; --bg-body: #f4f6f9; --text-color: #333; --danger: #e74c3c; --success: #27ae60; --card-shadow: 0 4px 15px rgba(0,0,0,0.1); --border-radius: 8px; }
        
        /* 1. ENABLE SCROLLING ON BODY */
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0; 
            padding: 0; 
            background-color: var(--bg-body); 
            color: var(--text-color);
            overflow-x: auto; 
            overflow-y: auto; 
        }
        
        a { text-decoration: none; }

        /* 2. CUSTOM SCROLLBAR STYLING (3px) */
        ::-webkit-scrollbar { width: 3px; height: 3px; }
        ::-webkit-scrollbar-track { background: #f0f0f0; }
        ::-webkit-scrollbar-thumb { background-color: #c1c1c1; border-radius: 2px; }
        ::-webkit-scrollbar-thumb:hover { background-color: var(--primary); }
        ::-webkit-scrollbar-corner { background: #f0f0f0; }

        /* =========================================
           VERTICAL SIDEBAR (LEFT SIDE)
           ========================================= */
        .navbar { 
            background-color: #34495e; 
            width: 250px;              
            height: 100vh;             
            position: fixed;           
            top: 0;
            left: 0;                   
            display: flex; 
            flex-direction: column;    
            justify-content: flex-start; 
            padding: 20px 0; 
            box-shadow: 2px 0 5px rgba(0,0,0,0.1); 
            overflow-y: auto;          
            z-index: 1000;
        }

        /* Sidebar Scrollbar Override */
        .navbar::-webkit-scrollbar { width: 3px; }
        .navbar::-webkit-scrollbar-track { background: #2c3e50; }
        .navbar::-webkit-scrollbar-thumb { background-color: #546e7a; }

        .nav-links {
            width: 100%;
            display: flex;
            flex-direction: column;    
        }

        .nav-links a { 
            display: block;            
            color: #ecf0f1; 
            text-align: left;          
            padding: 15px 25px;        
            font-size: 15px; 
            font-weight: 500; 
            transition: all 0.3s;
            border-bottom: 1px solid rgba(255,255,255,0.05); 
            border-left: 5px solid transparent; 
        }

        .nav-links a:hover, .nav-links a.active { 
            background-color: #2c3e50; 
            border-left: 5px solid var(--primary); 
            color: var(--primary);
            padding-left: 30px; 
        }

        .logout-container {
            margin-top: auto;          
            padding: 20px 40px 40px 20px; 
            width: 100%;
            box-sizing: border-box;
        }

        .logout-btn { 
            display: block;
            width: 100%;
            text-align: center;
            color: #ff6b6b !important; 
            border: 1px solid #ff6b6b; 
            border-radius: 4px; 
            padding: 10px !important; 
            font-weight: bold; 
            transition: 0.3s; 
            background: transparent;
        }
        
        .logout-btn:hover { background: #ff6b6b; color: white !important; }

        /* =========================================
           TOP HEADING SECTION (FIXED WITH MARGIN)
           ========================================= */
        .main-header {
            margin-left: 250px;        
            background-color: #ffffff;
            padding: 15px 30px;
            display: flex;
            align-items: center;
            justify-content: center;   
            border-bottom: 1px solid #e0e0e0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .main-header img { height: 50px; width: auto; margin-right: 20px; }
        .main-header h2 { margin: 0; font-size: 22px; font-weight: 600; color: var(--primary); text-transform: uppercase; letter-spacing: 0.5px; }
        
        /* =========================================
           MAIN CONTENT ADJUSTMENT
           ========================================= */
        .container { 
            padding: 20px; 
            margin-left: 250px;        
            width: calc(100% - 250px); 
            box-sizing: border-box;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar { width: 100%; height: auto; position: relative; flex-direction: row; flex-wrap: wrap; justify-content: center; }
            .nav-links { flex-direction: row; justify-content: center; flex-wrap: wrap; }
            .nav-links a { border-bottom: none; border-left: none; padding: 10px; }
            .nav-links a:hover, .nav-links a.active { border-left: none; border-bottom: 3px solid var(--primary); padding-left: 10px; }
            
            .main-header { margin-left: 0; flex-direction: column; gap: 10px; text-align: center; }
            .main-header img { margin-right: 0; }
            
            .container { margin-left: 0; width: 100%; }
            .logout-container { margin-top: 10px; padding-bottom: 10px; width: auto; }
        }

        /* Global Components */
        h1 { color: var(--primary); font-weight: 300; margin-bottom: 20px; }
        hr { border: 0; height: 1px; background: #ddd; margin-bottom: 30px; }
        .column, .priority-box, .create-section-box, .admin-card, .form-card { background: white; padding: 25px; margin-bottom: 20px; border-radius: var(--border-radius); box-shadow: var(--card-shadow); border: 1px solid #eaeaea; width: 100%; box-sizing: border-box; }
        .admin-card { max-width: 450px; margin: 50px auto; padding: 40px; text-align: center; }

        input[type="text"], input[type="password"], input[type="number"], select, textarea { width: 100%; padding: 12px; margin: 8px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; background-color: #fafafa; font-size: 14px; transition: border-color 0.3s; }
        input:focus, select:focus, textarea:focus { border-color: var(--primary); background-color: #fff; outline: none; }
        
        .google-date-wrapper { background-color: #f5f5f5; border-bottom: 1px solid #80868b; border-radius: 4px 4px 0 0; margin-bottom: 15px; transition: background 0.3s, border-color 0.3s; }
        .google-date-wrapper:hover { background-color: #eceff1; }
        .google-date-wrapper:focus-within { background-color: #e3f2fd; border-bottom: 2px solid var(--primary); }
        .google-date-input { width: 100%; border: none; background: transparent; padding: 12px 12px; font-size: 14px; outline: none; color: #333; font-family: inherit; box-sizing: border-box; height: 45px; }

        label { display: flex; align-items: center; gap: 10px; font-size: 14px; color: #555; margin: 10px 0; justify-content: center; }
        input[type="checkbox"] { width: 18px; height: 18px; accent-color: var(--primary); cursor: pointer; }
        button, input[type="submit"], .btn-toggle, .btn-add, .btn-cancel, .btn-delete, .btn-safety-toggle, .btn-edit-trigger, .btn-show { border: none; border-radius: 4px; cursor: pointer; font-size: 13px; font-weight: 600; padding: 10px 15px; transition: background 0.3s; }
        .btn-block { display: block; width: 100%; padding: 12px 0; margin-bottom: 15px; background-color: var(--primary); color: white; text-decoration: none; border-radius: 4px; font-weight: bold; text-align: center; box-sizing: border-box; }
        .btn-block:hover { background-color: var(--primary-dark); }
        .btn-block.secondary { background-color: #34495e; }
        .btn-block.secondary:hover { background-color: #2c3e50; }
        .btn-toggle, .btn-add, .btn-edit-trigger { background-color: var(--primary); color: white; }
        .btn-toggle:hover, .btn-add:hover, .btn-edit-trigger:hover { background-color: var(--primary-dark); }
        .btn-cancel { background-color: #95a5a6; color: white; }
        .btn-delete, .btn-remove { background-color: var(--danger); color: white; padding: 6px 12px; border-radius: 4px; }
        .btn-delete:hover, .btn-remove:hover { background-color: #c0392b; }
        .btn-remove { font-size: 11px; padding: 4px 8px; margin-left: 10px; }
        .btn-show { background-color: #3498db; color: white; padding: 4px 8px; font-size: 11px; margin-left: 5px; }
        .btn-show:hover { background-color: #2980b9; }
        .btn-back { display: inline-block; padding: 8px 15px; border: 1px solid #ccc; background-color: white; color: #555; border-radius: 4px; font-size: 13px; font-weight: 600; transition: all 0.3s; text-decoration: none; }
        .btn-back:hover { border-color: var(--primary); color: var(--primary); background-color: #f0fdfc; }
        .btn-arrow { background: none; border: none; font-size: 18px; color: #7f8c8d; padding: 0 10px; cursor: pointer; }
        .btn-arrow:hover { color: var(--primary); }
        .button-group { display: flex; gap: 10px; margin-top: 10px; }
        
        table { width: 100%; border-collapse: collapse; background: white; border-radius: var(--border-radius); overflow: hidden; box-shadow: var(--card-shadow); }
        th { background-color: var(--primary); color: white; padding: 15px; font-weight: 500; text-transform: uppercase; font-size: 13px; }
        td { padding: 12px 15px; border-bottom: 1px solid #eee; text-align: center; color: #555; }
        tr:last-child td { border-bottom: none; }
        tr:hover { background-color: #f9f9f9; }
        .col-status { width: 180px; min-width: 180px; white-space: nowrap; }
        .status-pending { color: #e67e22; font-weight: bold; background: #fff3e0; padding: 4px 8px; border-radius: 4px; font-size: 12px; }
        .status-completed { color: var(--success); font-weight: bold; background: #e8f5e9; padding: 4px 8px; border-radius: 4px; font-size: 12px; }
        .btn-complete { background-color: var(--success); color: white; padding: 5px 10px; border-radius: 4px; font-size: 12px; margin-left: 5px; transition: 0.2s; text-decoration: none; display: inline-block;}
        .btn-complete:hover { background-color: #219150; }

        .header-row { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #f0f0f0; padding-bottom: 15px; margin-bottom: 15px; }
        .priority-row { display: flex; justify-content: space-between; align-items: center; background: #f9f9f9; padding: 10px 15px; margin-bottom: 8px; border: 1px solid #eee; border-radius: 4px; }
        .btn-up, .btn-down { background-color: #95a5a6; color: white; padding: 4px 10px; border-radius: 4px; margin-left: 2px; font-size: 12px; }
        .btn-disabled { background-color: #e0e0e0; color: #fff; padding: 4px 10px; border-radius: 4px; pointer-events: none; }
        .action-bar { background-color: #f8f9fa; padding: 15px; border-radius: 6px; margin-bottom: 15px; border: 1px solid #eee; display: flex; flex-direction: column; gap: 10px; }
        .rename-box { display: none; background: #fffbe6; padding: 10px; border: 1px solid #e6dbb9; margin-bottom: 5px; }
        .remove-wrapper { display: flex; gap: 10px; }
        .btn-action-remove { flex: 3; background-color: #e74c3c; color: white; padding: 10px; text-align: center; border-radius: 4px; font-size: 13px; cursor: pointer; }
        .btn-action-remove.disabled { background-color: #e0e0e0; color: #aaa; pointer-events: none; }
        .btn-safety-toggle { background-color: #34495e; color: white; flex: 1; }
        ul { padding: 0; margin: 0; list-style: none; }
        li { background: white; border-bottom: 1px solid #f0f0f0; padding: 10px 5px; display: flex; justify-content: space-between; align-items: center; font-size: 14px; color: #555; }
        .password-text { font-family: monospace; background: #eee; padding: 2px 6px; border-radius: 4px; color: #333; }
        .btn-outline { display: inline-block; padding: 8px 16px; border: 1px solid #ccc; background-color: white; color: #555; text-decoration: none; border-radius: 4px; font-size: 14px; font-weight: 500; transition: all 0.3s ease; }
        .btn-outline:hover { border-color: var(--primary); color: var(--primary); background-color: #f9f9f9; }
    </style>
    <script>
        function toggle(id) { var x=document.getElementById(id); x.style.display=(x.style.display==="none")?"block":"none"; }
        function toggleFlex(id) { var x=document.getElementById(id); x.style.display=(x.style.display==="none")?"flex":"none"; }
        function toggleSafety(remId, togId) {
            var r = document.getElementById(remId); var t = document.getElementById(togId);
            if (t.innerHTML === "ENABLE") { t.innerHTML = "DISABLE"; r.classList.remove("disabled"); r.style.pointerEvents="auto"; r.style.backgroundColor="#e74c3c"; } 
            else { t.innerHTML = "ENABLE"; r.classList.add("disabled"); r.style.pointerEvents="none"; r.style.backgroundColor="#999"; }
        }
        function toggleList(listId, btn) {
            var list = document.getElementById(listId);
            if (list.style.display === "none") { list.style.display = "block"; btn.innerHTML = "&#9660;"; } 
            else { list.style.display = "none"; btn.innerHTML = "&#9654;"; }
        }
        function togglePass(btn) {
            var parent = btn.parentElement;
            var masked = parent.querySelector('.masked');
            var real = parent.querySelector('.real');
            if (real.style.display === 'none') { real.style.display = 'inline'; masked.style.display = 'none'; btn.innerHTML = 'Hide'; } 
            else { real.style.display = 'none'; masked.style.display = 'inline'; btn.innerHTML = 'Show'; }
        }
    </script>
</head>
<body>

    <div class="navbar">
        <div class="nav-links">
            <h3 style="color: #ccc; font-size: 12px; padding: 0 25px; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 1px;">Menu</h3>
            
            <a href="dashboard_overview.php" class="<?php echo ($current_page=='dashboard_overview.php')?'active':''; ?>">Dashboard</a>
            <a href="complaints_info.php" class="<?php echo ($current_page=='complaints_info.php')?'active':''; ?>">Complaints</a>
            
            <?php if ($user_permission === 'Partial'): ?>
                <a href="invoices_form.php" class="<?php echo ($current_page=='invoices_form.php')?'active':''; ?>">Add Invoices</a>
            <?php endif; ?>
            
            <?php if ($user_permission === 'Full'): ?>
                <a href="manage_config.php" class="<?php echo ($current_page=='manage_config.php')?'active':''; ?>">Manage Complaint Options</a>
                <a href="labs_hub.php" class="<?php echo (in_array($current_page, ['labs_hub.php', 'labs_info_form.php', 'labs_info_manager.php', 'labs_info_logs.php', 'labs_info.php', 'systems_info_manager.php', 'systems_info_form.php']))?'active':''; ?>">Labs Hub</a>
                <a href="invoices_hub.php" class="<?php echo (in_array($current_page, ['invoices_hub.php', 'invoices_form.php', 'invoices_management.php', 'invoices_log.php']))?'active':''; ?>">Invoices Hub</a>
                <a href="export_hub.php" class="<?php echo (in_array($current_page, ['export_hub.php', 'export_labs.php', 'export_lab_systems.php', 'export_systems.php']))?'active':''; ?>">Export Hub</a>
                <a href="manage_admins.php" class="<?php echo ($current_page=='manage_admins.php')?'active':''; ?>">Manage Admins</a>
            <?php endif; ?>
        </div>

        <div class="logout-container">
            <a href="auth_session.php?action=logout" class="logout-btn">Logout</a>
        </div>
    </div>

    <div class="main-header">
        <img src="logo.png" alt="Company Logo">
        <h2>PSCMRCET LABS ADMIN PANEL</h2>
    </div>

    <div class="container">
    
        <?php if($sys_msg != ""): ?>
            <div style="text-align:center; margin-bottom:20px; padding:12px; border-radius:6px; 
                        background-color: <?php echo ($sys_msg_color=='red')?'#fce4ec':'#e8f5e9'; ?>; 
                        color: <?php echo ($sys_msg_color=='red')?'#c62828':'#2e7d32'; ?>; border:1px solid transparent;">
                <strong><?php echo $sys_msg; ?></strong>
            </div>
        <?php endif; ?>