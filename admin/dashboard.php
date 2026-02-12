<?php
include 'auth_session.php';
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Mission Hope – Admin Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="admin-wrapper">

<?php include 'sidebar.php'; ?>

<!-- Main Area -->
<div class="admin-main">

<!-- Top Bar -->
<div class="admin-topbar d-flex justify-content-between align-items-center">
    <h5>Admin Dashboard</h5>
    <span>Welcome, Admin</span>
</div>

<!-- Dashboard Cards -->
<div class="row g-4 mt-3">

<?php
// Get Counts
$members_count = $conn->query("SELECT COUNT(*) as count FROM leadership")->fetch_assoc()['count']; // Using leadership as proxy or just 0
$dept_count = $conn->query("SELECT COUNT(*) as count FROM ministries")->fetch_assoc()['count'];
$announce_count = $conn->query("SELECT COUNT(*) as count FROM announcements")->fetch_assoc()['count'];
$event_count = $conn->query("SELECT COUNT(*) as count FROM events")->fetch_assoc()['count'];
$msg_count = $conn->query("SELECT COUNT(*) as count FROM messages")->fetch_assoc()['count'];
?>

<div class="col-md-3">
    <div class="card shadow-sm p-3">
        <h6>Leadership Members</h6>
        <h2><?php echo $members_count; ?></h2>
    </div>
</div>

<div class="col-md-3">
    <div class="card shadow-sm p-3">
        <h6>Departments</h6>
        <h2><?php echo $dept_count; ?></h2>
    </div>
</div>

<div class="col-md-3">
    <div class="card shadow-sm p-3">
        <h6>Announcements</h6>
        <h2><?php echo $announce_count; ?></h2>
    </div>
</div>

<div class="col-md-3">
    <div class="card shadow-sm p-3">
        <h6>Upcoming Events</h6>
        <h2><?php echo $event_count; ?></h2>
    </div>
</div>

</div>

<!-- Recent Data -->
<div class="row mt-4 g-4">

<div class="col-md-6">
    <div class="card p-3 shadow-sm">
        <h5>Latest Announcements</h5>
        <ul>
            <?php
            $result = $conn->query("SELECT * FROM announcements ORDER BY id DESC LIMIT 5");
            while($row = $result->fetch_assoc()){
                echo "<li>" . htmlspecialchars($row['title']) . "</li>";
            }
            ?>
        </ul>
    </div>
</div>

<div class="col-md-6">
    <div class="card p-3 shadow-sm">
        <h5>Recent Messages</h5>
        <ul>
             <?php
            $result = $conn->query("SELECT * FROM messages ORDER BY created_at DESC LIMIT 5");
            while($row = $result->fetch_assoc()){
                echo "<li>From: " . htmlspecialchars($row['first_name']) . " - " . htmlspecialchars($row['subject']) . "</li>";
            }
            if($result->num_rows == 0) echo "<li>No messages yet.</li>";
            ?>
        </ul>
    </div>
</div>

</div>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
