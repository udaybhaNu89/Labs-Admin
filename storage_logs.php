<?php
require 'auth_session.php';

if (isset($_GET['delete_storage'])) {
    $id = intval($_GET['delete_storage']);
    mysqli_query($conn, "DELETE FROM storage_unit WHERE id = $id");
    $_SESSION['sys_msg'] = "Log Entry Deleted"; $_SESSION['sys_msg_color'] = "green";
    header("Location: storage_logs.php"); exit();
}

include 'header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1>Storage Inventory Log</h1>
    <p><a href="storage_hub.php" class="btn-outline">&larr; Back to Hub</a></p>
</div>

<?php
$sections = [];
$res = mysqli_query($conn, "SELECT * FROM storage_sections ORDER BY display_order ASC");
while ($row = mysqli_fetch_assoc($res)) { $sections[] = $row; }
$sql = "SELECT * FROM storage_unit ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
$num_records = mysqli_num_rows($result);
?>

<?php if ($num_records > 0): ?>
    <table>
        <tr>
            <?php foreach ($sections as $sec) { echo "<th>" . $sec['section_title'] . "</th>"; } ?>
            <th>Date Logged</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <?php foreach ($sections as $sec) {
                    $col = $sec['column_name'];
                    $val = (!empty($row[$col])) ? $row[$col] : "-";
                    echo "<td>" . $val . "</td>";
                } ?>
                <td><?php echo $row['created_at']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <div style="text-align:center; padding:40px; border:1px solid #eee; border-radius:8px; background:#fff; color:#777;">
        <h3>No Records Found</h3>
    </div>
<?php endif; ?>

</div>
</body>
</html>