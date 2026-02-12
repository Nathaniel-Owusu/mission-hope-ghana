<?php
include 'admin/db.php';

// Create positions table
$sql = "CREATE TABLE IF NOT EXISTS positions (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
)";

if ($conn->query($sql) === TRUE) {
    echo "Table positions created successfully.<br>";
    
    // Insert defaults
    $defaults = [
        'Pastor',
        'Elder',
        'Deacon',
        'Deaconess',
        'Church Clerk',
        'Treasurer',
        'Department Director',
        'Music Leader',
        'Youth Leader'
    ];
    
    $stmt = $conn->prepare("INSERT IGNORE INTO positions (name) VALUES (?)");
    foreach($defaults as $pos) {
        $stmt->bind_param("s", $pos);
        $stmt->execute();
    }
    echo "Default positions inserted.<br>";
    
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}
?>
