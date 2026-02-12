<?php
include "db.php";
$id = $_GET['id'];
$conn->query("DELETE FROM announcements WHERE id=$id");
header("Location: announcements.php");
?>
