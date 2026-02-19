<?php
include 'db.php';

// Create Users Table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'users' created successfully.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Add Default Admin User
$username = 'admin';
$password = 'missionhope2024';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Check if user exists
$check = $conn->query("SELECT id FROM users WHERE username='$username'");
if ($check->num_rows == 0) {
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
        echo "Default admin user created successfully.<br>";
        echo "Username: <b>admin</b><br>";
        echo "Password: <b>missionhope2024</b><br>";
    } else {
        echo "Error creating user: " . $stmt->error . "<br>";
    }
} else {
    echo "Admin user already exists.<br>";
}

echo "<br><a href='login.php'>Go to Login</a>";
