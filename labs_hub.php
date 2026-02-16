<?php
require 'auth_session.php';
include 'header.php';
?>

<div class="admin-card">
    <h2 style="color:var(--primary); margin-top:0;">Labs Info Hub</h2>
    <p style="color:#666; margin-bottom:20px; font-size:14px;">Select an action:</p>
    <a href="labs_info.php" class="btn-block">View Labs Data</a>
    <a href="labs_info_form.php" class="btn-block secondary">Create New Lab Form</a>
    <a href="labs_info_manager.php" class="btn-block">Labs Form Management</a>
    <a href="systems_info_form.php" class="btn-block secondary">Create Labs wise Systems Form</a>
    <a href="systems_info_manager.php" class="btn-block">Labs Wise Systems Form Manager</a>
</div>

</div>
</body>
</html>