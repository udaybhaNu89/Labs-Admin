<?php
$server = "127.0.0.1"; // CHANGED: Use IP to force network connection
$user = "root";
$pass = "nikson";      // Make sure this password matches what is in your Termux DB
$dbname = "lab_db";

// Optional: Enable error reporting to see issues clearly
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = mysqli_connect($server, $user, $pass, $dbname);
} catch (mysqli_sql_exception $e) {
    // This will catch the error and show a cleaner message
    die("Connection failed: " . $e->getMessage());
}
?>
