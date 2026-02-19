<?php
// ===============================================
// DATABASE CONNECTION (UPDATED RESILIENT VERSION)
// ===============================================

// Disable strict error reporting to prevent 500 errors on PHP 8.1+
mysqli_report(MYSQLI_REPORT_OFF);

// LOCAL SETTINGS (XAMPP)
$server = "localhost";
$username = "root";
$password = "";
$dbname = "missionhope";

// HOSTINGER SETTINGS (Uncomment and use these when live on Hostinger)
/*
$server = "localhost"; 
$username = "u957056558_admin_user"; 
$password = "965321uhp]"; 
$dbname = "u957056558_missionhope"; 
*/

try {
    $conn = new mysqli($server, $username, $password, $dbname);

    if ($conn->connect_error) {
        throw new Exception($conn->connect_error);
    }
} catch (Exception $e) {
    // This prevents the 500 error and shows a useful message instead
    echo "<div style='padding:20px; background:#fff5f5; border:1px solid #feb2b2; margin:20px; font-family:sans-serif;'>";
    echo "<h3 style='color:#c53030;'>Database Connection Issue</h3>";
    echo "<p>The website is working, but it cannot talk to the database yet.</p>";
    echo "<p><b>Error:</b> " . $e->getMessage() . "</p>";
    echo "<p>Please check your credentials in <b>admin/db.php</b> on Hostinger.</p>";
    echo "</div>";
    exit;
}
