<?php
include 'auth_session.php';
include 'db.php';

// Handle Add Position
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_position'])) {
    $pos_name = trim($_POST['position_name']);
    if (!empty($pos_name)) {
        $stmt = $conn->prepare("INSERT INTO positions (name) VALUES (?)");
        $stmt->bind_param("s", $pos_name);
        $stmt->execute();
    }
    header("Location: leadership.php");
    exit();
}

// Handle Delete Position
if (isset($_GET['delete_pos'])) {
    $id = $_GET['delete_pos'];
    $conn->query("DELETE FROM positions WHERE id=$id");
    header("Location: leadership.php");
    exit();
}

// Handle Add Leader
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_leader'])) {
    $name = $_POST['name'];
    $role = $_POST['role'];
    $category = $_POST['category']; // This is now the Position Name
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    
    // Image Upload
    $image = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../"; // Upload to root
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = basename($_FILES["image"]["name"]);
        }
    }

    $stmt = $conn->prepare("INSERT INTO leadership (name, role, category, email, phone, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $role, $category, $email, $phone, $image);
    $stmt->execute();
    header("Location: leadership.php");
    exit();
}

// Handle Delete Leader
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM leadership WHERE id=$id");
    header("Location: leadership.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Leadership</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="admin-wrapper">
    <?php include 'sidebar.php'; ?>

    <div class="admin-main">
        <div class="admin-topbar">
            <h5>Manage Leadership</h5>
        </div>

        <div class="container-fluid mt-4">
            
            <div class="row mb-4">
                <!-- Manage Positions Card -->
                <div class="col-12">
                    <div class="card p-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="m-0">Manage Positions</h6>
                            <form method="POST" class="d-flex gap-2">
                                <input type="text" name="position_name" class="form-control form-control-sm" placeholder="New Position Name" required>
                                <button type="submit" name="add_position" class="btn btn-sm btn-success text-nowrap">+ Add Position</button>
                            </form>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <?php
                            $pos_res = $conn->query("SELECT * FROM positions ORDER BY name ASC");
                            while($pos = $pos_res->fetch_assoc()) {
                                echo '<span class="badge bg-secondary p-2 d-flex align-items-center gap-2">
                                        '.$pos['name'].'
                                        <a href="leadership.php?delete_pos='.$pos['id'].'" class="text-white text-decoration-none" onclick="return confirm(\'Delete this position?\')">&times;</a>
                                      </span>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Add Leader Form -->
                <div class="col-md-4">
                    <div class="card p-3">
                        <h6>Add New Leader</h6>
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Role Description</label>
                                <input type="text" name="role" class="form-control" placeholder="e.g. Head Elder, Senior Pastor" required>
                            </div>
                             <div class="mb-3">
                                <label>Position (Category)</label>
                                <select name="category" class="form-control" required>
                                    <option value="">Select Position...</option>
                                    <?php
                                    // Reset pointer
                                    $pos_res->data_seek(0);
                                    while($pos = $pos_res->fetch_assoc()) {
                                        // Use lowercase value for DB compatibility with existing checks if desired, 
                                        // or just store the Name as is. Let's store the lowercase version as 'category' in DB to keep existing frontend logic working nicely if it relies on 'pastor', 'elder'.
                                        // OR update the frontend to check specifically. 
                                        // For simplicity, let's store the raw name but try to map to our known keys if they match.
                                        
                                        // Actually, let's just store the Name directly.
                                        // Ideally we should update the public page to match 'Pastor' instead of 'pastor'.
                                        // effectively normalizing the data.
                                        
                                        // But the user just asked to change category to position. 
                                        // I will store the user-defined name as the value.
                                        echo '<option value="'.htmlspecialchars($pos['name']).'">'.htmlspecialchars($pos['name']).'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Email (Optional)</label>
                                <input type="text" name="email" class="form-control">
                            </div>
                             <div class="mb-3">
                                <label>Phone (Optional)</label>
                                <input type="text" name="phone" class="form-control">
                            </div>
                             <div class="mb-3">
                                <label>Image</label>
                                <input type="file" name="image" class="form-control">
                            </div>
                            <button type="submit" name="add_leader" class="btn btn-primary w-100">Add Leader</button>
                        </form>
                    </div>
                </div>

                <!-- List -->
                <div class="col-md-8">
                    <div class="card p-3">
                        <h6>Current Leadership</h6>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Position</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $result = $conn->query("SELECT * FROM leadership ORDER BY id DESC");
                                while($row = $result->fetch_assoc()){
                                    echo "<tr>";
                                    echo "<td>";
                                    if($row['image']) {
                                        echo "<img src='../{$row['image']}' width='50' height='50' style='object-fit:cover; border-radius:50%;'>";
                                    }
                                    echo "</td>";
                                    echo "<td>{$row['name']}</td>";
                                    echo "<td>{$row['role']}</td>";
                                    echo "<td><span class='badge bg-info'>{$row['category']}</span></td>";
                                    echo "<td>
                                        <a href='leadership.php?delete={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
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
