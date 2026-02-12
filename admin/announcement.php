<?php
include 'auth_session.php';
include 'db.php';

// Handle Add
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_announcement'])) {
    $title = $_POST['title'];
    $message = $_POST['message'];
    
    $stmt = $conn->prepare("INSERT INTO announcements (title, message) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $message);
    $stmt->execute();
    header("Location: announcement.php");
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM announcements WHERE id=$id");
    header("Location: announcement.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Announcements</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="admin-wrapper">
    <?php include 'sidebar.php'; ?>

    <div class="admin-main">
        <div class="admin-topbar">
            <h5>Manage Announcements</h5>
        </div>

        <div class="container-fluid mt-4">
            <div class="row">
                <!-- Add Form -->
                <div class="col-md-4">
                    <div class="card p-3">
                        <h6>Add New Announcement</h6>
                        <form method="POST">
                            <div class="mb-3">
                                <label>Title</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Message</label>
                                <textarea name="message" class="form-control" rows="4" required></textarea>
                            </div>
                            <button type="submit" name="add_announcement" class="btn btn-primary w-100">Post Announcement</button>
                        </form>
                    </div>
                </div>

                <!-- List -->
                <div class="col-md-8">
                    <div class="card p-3">
                        <h6>Current Announcements</h6>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Title</th>
                                    <th>Message</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $result = $conn->query("SELECT * FROM announcements ORDER BY id DESC");
                                while($row = $result->fetch_assoc()){
                                    echo "<tr>";
                                    echo "<td>{$row['created_at']}</td>";
                                    echo "<td>{$row['title']}</td>";
                                    echo "<td>" . nl2br(htmlspecialchars($row['message'])) . "</td>";
                                    echo "<td>
                                        <a href='announcement.php?delete={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
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
