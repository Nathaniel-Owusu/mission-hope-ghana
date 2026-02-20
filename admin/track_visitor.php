<?php
include_once 'db.php';

// Ensure visitor_logs table exists
$conn->query("CREATE TABLE IF NOT EXISTS visitor_logs (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL,
    page_url VARCHAR(255) NOT NULL,
    user_agent TEXT,
    visited_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Get Visitor Info
$ip_address = $_SERVER['REMOTE_ADDR'];
$page_url = $_SERVER['REQUEST_URI'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];

// Only log if this IP has never visited before
$check = $conn->prepare("SELECT id FROM visitor_logs WHERE ip_address = ? LIMIT 1");
$check->bind_param("s", $ip_address);
$check->execute();
$check->store_result();

if ($check->num_rows === 0) {
    $stmt = $conn->prepare("INSERT INTO visitor_logs (ip_address, page_url, user_agent) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $ip_address, $page_url, $user_agent);
    $stmt->execute();
}
