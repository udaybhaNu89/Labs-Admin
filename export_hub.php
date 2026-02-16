<?php
require 'auth_session.php';
include 'header.php';
?>

<div class="admin-card">
    <h2 style="color:var(--primary); margin-top:0;">Export Hub</h2>
    <p style="color:#666; margin-bottom:20px; font-size:14px;">Select an action:</p>
    <a href="export_labs.php" class="btn-block">Export All Labs Data</a>
    <a href="export_lab_systems.php" class="btn-block secondary">Export Lab Systems Data</a>
    <a href="export_systems.php" class="btn-block">Export Lab Wise Individual System Data</a>
</div>

</div>
</body>
</html>