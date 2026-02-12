<?php
include 'auth_session.php';
include 'db.php';

// Handle Add
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_ministry'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $leader_name = $_POST['leader_name'];
    
    // Main Image Upload
    $image = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../"; 
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = basename($_FILES["image"]["name"]);
        }
    }

    $stmt = $conn->prepare("INSERT INTO ministries (title, description, leader_name, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $description, $leader_name, $image);
    $stmt->execute();
    header("Location: departments.php");
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM ministries WHERE id=$id");
    header("Location: departments.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Departments</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="admin-wrapper">
    <?php include 'sidebar.php'; ?>

    <div class="admin-main">
        <div class="admin-topbar">
            <h5>Manage Departments (Ministries)</h5>
        </div>

        <div class="container-fluid mt-4">
            <div class="row">
                <!-- Add Form -->
                <div class="col-md-4">
                    <div class="card p-3">
                        <h6>Add New Ministry</h6>
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label>Ministry Title</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Description</label>
                                <textarea name="description" class="form-control" rows="4"></textarea>
                            </div>
                             <div class="mb-3">
                                <label>Leader Name</label>
                                <input type="text" name="leader_name" class="form-control">
                            </div>
                             <div class="mb-3">
                                <label>Cover Image</label>
                                <input type="file" name="image" class="form-control">
                            </div>
                            <button type="submit" name="add_ministry" class="btn btn-primary w-100">Add Ministry</button>
                        </form>
                    </div>
                </div>

                <!-- List -->
                <div class="col-md-8">
                    <div class="card p-3">
                        <h6>Current Ministries</h6>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Leader</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $result = $conn->query("SELECT * FROM ministries");
                                while($row = $result->fetch_assoc()){
                                    echo "<tr>";
                                    echo "<td>";
                                    if($row['image']) {
                                        echo "<img src='../{$row['image']}' width='50' height='50' style='object-fit:cover; border-radius:5px;'>";
                                    }
                                    echo "</td>";
                                    echo "<td>{$row['title']}</td>";
                                    echo "<td>{$row['leader_name']}</td>";
                                    echo "<td>
                                        <a href='departments.php?delete={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
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
