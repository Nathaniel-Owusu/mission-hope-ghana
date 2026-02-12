<?php
// Database Configuration
// Keep the local settings when working on your computer
// Update the 'Live Server' settings when you upload to your hosting provider

$server = "localhost";
$username = "root";
$password = "";
$dbname = "missionhope";

// Detect if we are on a live server (You can change 'localhost' to your actual domain logic if needed)
// For now, you can manually uncomment the lines below when uploading to your hosting provider:

/*
$server = "localhost"; // Usually 'localhost' on shared hosting too
$username = "u123456789_missionhope"; // Your hosting database username
$password = "YourDBPassword123!"; // Your hosting database password
$dbname = "u123456789_missionhope"; // Your hosting database name
*/

$conn = new mysqli($server, $username, $password, $dbname);

if ($conn->connect_error) {
    // In production, you might not want to show the full error to visitors
    // die("Connection failed: " . $conn->connect_error);
    die("Database Connection Failed. Please try again later.");
}
