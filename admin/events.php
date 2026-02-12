<?php
include 'auth_session.php';
include 'db.php';

// Handle Add
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_event'])) {
    $title = $_POST['title'];
    $date_val = $_POST['date']; // 2024-01-27
    $time_str = $_POST['time'];
    $description = $_POST['description'];
    
    // Process Date
    $timestamp = strtotime($date_val);
    $date_str = date("F j, Y", $timestamp); // January 27, 2024
    $month_short = strtoupper(date("M", $timestamp)); // JAN
    $day_num = date("d", $timestamp); // 27
    
    // Image Upload
    $image = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../"; 
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = basename($_FILES["image"]["name"]);
        }
    }

    $stmt = $conn->prepare("INSERT INTO events (title, date_str, time_str, description, image, month_short, day_num) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $title, $date_str, $time_str, $description, $image, $month_short, $day_num);
    $stmt->execute();
    header("Location: events.php");
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM events WHERE id=$id");
    header("Location: events.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Events</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="admin-wrapper">
    <?php include 'sidebar.php'; ?>

    <div class="admin-main">
        <div class="admin-topbar">
            <h5>Manage Events</h5>
        </div>

        <div class="container-fluid mt-4">
            <div class="row">
                <!-- Add Form -->
                <div class="col-md-4">
                    <div class="card p-3">
                        <h6>Add New Event</h6>
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label>Event Title</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Date</label>
                                <input type="date" name="date" class="form-control" required>
                            </div>
                             <div class="mb-3">
                                <label>Time (e.g. 7:00 PM)</label>
                                <input type="text" name="time" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Description</label>
                                <textarea name="description" class="form-control" rows="3"></textarea>
                            </div>
                             <div class="mb-3">
                                <label>Event Image</label>
                                <input type="file" name="image" class="form-control">
                            </div>
                            <button type="submit" name="add_event" class="btn btn-primary w-100">Add Event</button>
                        </form>
                    </div>
                </div>

                <!-- List -->
                <div class="col-md-8">
                    <div class="card p-3">
                        <h6>Upcoming Events</h6>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Title</th>
                                    <th>Time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $result = $conn->query("SELECT * FROM events ORDER BY id DESC");
                                while($row = $result->fetch_assoc()){
                                    echo "<tr>";
                                    echo "<td>{$row['date_str']}</td>";
                                    echo "<td>{$row['title']}</td>";
                                    echo "<td>{$row['time_str']}</td>";
                                    echo "<td>
                                        <a href='events.php?delete={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                                    </td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
