<?php
include "db.php"; // Connect to MySQL

// Get data from form
$title = $_POST['title'];
$message = $_POST['message'];

// Save to database
$sql = "INSERT INTO announcements (title, message) VALUES ('$title', '$message')";
if($conn->query($sql)){
    header("Location: announcements.php"); // Go back to admin page
}else{
    echo "Error: " . $conn->error;
}
?>
