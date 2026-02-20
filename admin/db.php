<?php
// DATABASE CONNECTION

// Default Localhost Settings
$server = "localhost";
$username = "root";
$password = "";
$dbname = "missionhope";

// Detect Environment
// Check if the host contains 'localhost' or '127.0.0.1' (handles ports like :8080)
$is_localhost = false;
$whitelist = array('127.0.0.1', '::1', 'localhost');

if (isset($_SERVER['HTTP_HOST'])) {
    foreach ($whitelist as $local_alias) {
        if (strpos($_SERVER['HTTP_HOST'], $local_alias) !== false) {
            $is_localhost = true;
            break;
        }
    }
} else {
    // CLI or fallback
    $is_localhost = true;
}

// Logic to switch credentials if NOT localhost (e.g., Hostinger)
if (!$is_localhost) {
    // HOSTINGER CREDENTIALS
    // These specific credentials will be used when you upload to the live server.
    $server = "localhost";
    $username = "u957056558_php_missionHop";
    $password = "churchMH2026";
    $dbname = "u957056558_php_missionHop";

    // Disable error reporting on live site for security
    mysqli_report(MYSQLI_REPORT_OFF);
} else {
    // Enable error reporting for local development
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
}

try {
    $conn = new mysqli($server, $username, $password, $dbname);
    $conn->set_charset("utf8mb4");

    // Create Admin Activity Logs Table (if it doesn't exist)
    $conn->query("CREATE TABLE IF NOT EXISTS admin_activity_logs (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        admin_id INT(11) DEFAULT NULL,
        action VARCHAR(255) NOT NULL,
        details TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
} catch (Exception $e) {
    // Graceful error handling
    if ($is_localhost) {
        die("<h3>Local Connection Failed</h3><p>" . $e->getMessage() . "</p>");
    } else {
        // Generic error message for production users
        error_log($e->getMessage());
        die("<div style='text-align:center; padding:50px; font-family:sans-serif;'>
                <h2 style='color:#e53e3e'>Service Temporarily Unavailable</h2>
                <p>We are experiencing technical difficulties. Please try again later.</p>
             </div>");
    }
}

// Helper function to log admin activities
if (!function_exists('logActivity')) {
    function logActivity($conn, $admin_id, $action, $details = "")
    {
        $stmt = $conn->prepare("INSERT INTO admin_activity_logs (admin_id, action, details) VALUES (?, ?, ?)");
        // Use 0 or NULL if admin_id is not set
        $aid = $admin_id ? $admin_id : 0;
        $stmt->bind_param("iss", $aid, $action, $details);
        $stmt->execute();
    }
}
