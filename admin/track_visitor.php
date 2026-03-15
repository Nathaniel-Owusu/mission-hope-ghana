<?php
// Use absolute path for include to handle different entry points
require_once __DIR__ . '/db.php';

// Check if database connection is available
if (!isset($conn) || !$conn) {
    error_log("Database connection not found in track_visitor.php");
    return;
}

// Ensure visitor_logs table exists
$table_sql = "CREATE TABLE IF NOT EXISTS visitor_logs (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL,
    page_url VARCHAR(255) NOT NULL,
    user_agent TEXT,
    visited_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($table_sql);

// Get Visitor Info
$ip_address = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
$page_url = $_SERVER['REQUEST_URI'] ?? '/';
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';

// Only log if this IP has never visited before
$check_sql = "SELECT id FROM visitor_logs WHERE ip_address = ? LIMIT 1";
$check = $conn->prepare($check_sql);

if ($check) {
    $check->bind_param("s", $ip_address);
    $check->execute();
    $check->store_result();

    if ($check->num_rows === 0) {
        $stmt = $conn->prepare("INSERT INTO visitor_logs (ip_address, page_url, user_agent) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sss", $ip_address, $page_url, $user_agent);
            $stmt->execute();
            $stmt->close();
        }
    }
    $check->close();
}
