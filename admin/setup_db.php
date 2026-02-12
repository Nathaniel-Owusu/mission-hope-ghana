<?php
include 'db.php';

// Announcements Table
$sql = "CREATE TABLE IF NOT EXISTS announcements (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($sql) === TRUE) {
    echo "Table announcements created successfully<br>";
} else {
    echo "Error creating table announcements: " . $conn->error . "<br>";
}

// Leadership Table
$sql = "CREATE TABLE IF NOT EXISTS leadership (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    role VARCHAR(255) NOT NULL,
    category VARCHAR(50) NOT NULL COMMENT 'pastor, elder, deacon',
    image VARCHAR(255),
    email VARCHAR(255),
    phone VARCHAR(255)
)";
if ($conn->query($sql) === TRUE) {
    echo "Table leadership created successfully<br>";
} else {
    echo "Error creating table leadership: " . $conn->error . "<br>";
}

// Ministries Table
$sql = "CREATE TABLE IF NOT EXISTS ministries (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    leader_name VARCHAR(255),
    leader_role VARCHAR(255),
    leader_image VARCHAR(255)
)";
if ($conn->query($sql) === TRUE) {
    echo "Table ministries created successfully<br>";
} else {
    echo "Error creating table ministries: " . $conn->error . "<br>";
}

// Events Table
$sql = "CREATE TABLE IF NOT EXISTS events (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    date_str VARCHAR(50),
    time_str VARCHAR(50),
    description TEXT,
    image VARCHAR(255),
    month_short VARCHAR(10),
    day_num VARCHAR(10)
)";
if ($conn->query($sql) === TRUE) {
    echo "Table events created successfully<br>";
} else {
    echo "Error creating table events: " . $conn->error . "<br>";
}

// Messages Table
$sql = "CREATE TABLE IF NOT EXISTS messages (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    email VARCHAR(255),
    subject VARCHAR(255),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
// Media Table
$sql = "CREATE TABLE IF NOT EXISTS media (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    file_path VARCHAR(255) NOT NULL,
    type VARCHAR(50) NOT NULL, -- 'image' or 'video'
    title VARCHAR(255),
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($sql) === TRUE) {
    echo "Table media created successfully<br>";
} else {
    echo "Error creating table media: " . $conn->error . "<br>";
}


$conn->close();
?>
