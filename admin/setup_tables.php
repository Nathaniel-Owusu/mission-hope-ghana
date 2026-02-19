<?php
include 'db.php';

// Create sms_history table
$sql = "CREATE TABLE IF NOT EXISTS sms_history (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    message TEXT NOT NULL,
    recipients VARCHAR(255) NOT NULL,
    status VARCHAR(50) DEFAULT 'Sent',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table sms_history created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Create settings table
$sql = "CREATE TABLE IF NOT EXISTS settings (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT
)";

if ($conn->query($sql) === TRUE) {
    echo "Table settings created successfully<br>";

    // Insert default settings if not exists
    $defaults = [
        'church_name' => 'Mission Hope SDA Church',
        'contact_email' => 'info@missionhope.org',
        'address' => '123 Faith Street, Accra, Ghana',
        'facebook' => 'facebook.com/missionhope',
        'youtube' => 'youtube.com/missionhope',
        'instagram' => 'instagram.com/missionhope'
    ];

    foreach ($defaults as $key => $value) {
        $check = $conn->query("SELECT * FROM settings WHERE setting_key='$key'");
        if ($check->num_rows == 0) {
            $conn->query("INSERT INTO settings (setting_key, setting_value) VALUES ('$key', '$value')");
        }
    }
    echo "Default settings checked/inserted<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

echo "Database setup complete.";
