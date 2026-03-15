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
    
    // Explicitly check for connection error as some PHP versions don't throw exceptions by default
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
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
        die("<div style='background:#fff5f5; border:1px solid #feb2b2; padding:20px; border-radius:8px; font-family:sans-serif;'>
                <h3 style='color:#c53030; margin-top:0;'>Local Connection Failed</h3>
                <p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
                <p>Please ensure your MySQL server is running and the database 'missionhope' exists.</p>
             </div>");
    } else {
        // Log the actual error for the admin
        error_log("Database Connection Error: " . $e->getMessage());
        
        // Generic error message for production users
        die("<div style='text-align:center; padding:50px; font-family:sans-serif; background:#f7fafc; min-height:100vh;'>
                <div style='max-width:500px; margin:0 auto; background:white; padding:40px; border-radius:15px; shadow:0 4px 6px rgba(0,0,0,0.1);'>
                    <h2 style='color:#e53e3e; margin-top:0;'>Service Temporarily Unavailable</h2>
                    <p style='color:#4a5568; line-height:1.6;'>We are experiencing technical difficulties connecting to our database. Our team has been notified.</p>
                    <p style='color:#718096; font-size:14px; margin-top:20px;'>Please try again in a few minutes.</p>
                    <a href='index.php' style='display:inline-block; margin-top:20px; padding:10px 20px; background:#2d6a52; color:white; text-decoration:none; border-radius:5px;'>Retry</a>
                </div>
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
