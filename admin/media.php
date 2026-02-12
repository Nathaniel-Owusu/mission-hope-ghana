<?php
include 'auth_session.php';
include 'db.php';

// Handle Upload
if (isset($_POST['upload'])) {
    $title_base = $_POST['title'];
    $type = $_POST['type']; // 'image' or 'video'
    
    // Increase limits for bulk upload
    ini_set('upload_max_filesize', '100M');
    ini_set('post_max_size', '100M');
    ini_set('max_file_uploads', '50');

    // Count total files
    $countfiles = count($_FILES['file']['name']);
    $success_count = 0;
    $error_count = 0;

    $target_dir = "../gallery_uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'webm', 'ogg'];

    // Loop through all files
    for($i=0;$i<$countfiles;$i++){
        if($_FILES['file']['error'][$i] == 0) {
            $filename = basename($_FILES['file']['name'][$i]);
            $target_file = $target_dir . $filename;
            $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

            if(in_array($fileType, $allowed)) {
                // Unique filename to avoid overwrite
                $unique_name = time() . "_$i" . "_" . $filename;
                $target_file = $target_dir . $unique_name;

                if (move_uploaded_file($_FILES['file']['tmp_name'][$i], $target_file)) {
                    // Store relative path
                    $db_path = "gallery_uploads/" . $unique_name;
                    $title = $countfiles > 1 ? "$title_base ($i)" : $title_base;
                    
                    $stmt = $conn->prepare("INSERT INTO media (title, type, file_path) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $title, $type, $db_path);
                    $stmt->execute();
                    $success_count++;
                } else {
                    $error_count++;
                }
            } else {
                $error_count++;
            }
        }
    }
    
    if($success_count > 0) {
        $msg = "Successfully uploaded $success_count file(s).";
    }
    if($error_count > 0) {
        $error = "Failed to upload $error_count file(s). Check file types or sizes.";
    }
}
// Delete logic remains same...
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $res = $conn->query("SELECT file_path FROM media WHERE id=$id");
    if($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $file_path = "../" . $row['file_path'];
        if(file_exists($file_path)) unlink($file_path);
    }
    $conn->query("DELETE FROM media WHERE id=$id");
    header("Location: media.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Media Gallery</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="admin-wrapper">
    <?php include 'sidebar.php'; ?>

    <div class="admin-main">
        <div class="admin-topbar">
            <h5>Media Gallery</h5>
        </div>

        <div class="container-fluid mt-4">
            
            <?php if(isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>
            <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

            <div class="row">
                <!-- Upload Form -->
                <div class="col-md-4">
                    <div class="card p-3">
                        <h6>Bulk Upload Media</h6>
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label>Batch Title / Caption</label>
                                <input type="text" name="title" class="form-control" placeholder="e.g. Youth Camp 2024" required>
                            </div>
                            <div class="mb-3">
                                <label>Type</label>
                                <select name="type" class="form-control">
                                    <option value="image">Photos</option>
                                    <option value="video">Videos</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Select Files (Max 50)</label>
                                <!-- multiple attribute allows selecting multiple files -->
                                <input type="file" name="file[]" class="form-control" multiple required>
                                <small class="text-muted">You can select multiple images or videos at once.</small>
                            </div>
                            <button type="submit" name="upload" class="btn btn-primary w-100">Upload All</button>
                        </form>
                    </div>
                    
                    <div class="alert alert-info mt-3 text-small">
                        <small><strong>Tip:</strong> Hold <code>Ctrl</code> (Windows) or <code>Cmd</code> (Mac) to select multiple files in the file dialog.</small>
                    </div>
                </div>

                <!-- Gallery List -->
                <div class="col-md-8">
                    <div class="card p-3">
                        <h6>Gallery Items</h6>
                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th style="width: 100px;">Preview</th>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result = $conn->query("SELECT * FROM media ORDER BY id DESC");
                                    while($row = $result->fetch_assoc()){
                                        echo "<tr>";
                                        echo "<td>";
                                        if($row['type'] == 'image') {
                                            echo "<img src='../{$row['file_path']}' style='width: 80px; height: 60px; object-fit: cover;'>";
                                        } else {
                                            echo "<span class='badge bg-secondary'>Video</span>";
                                        }
                                        echo "</td>";
                                        echo "<td>{$row['title']}</td>";
                                        echo "<td>" . ucfirst($row['type']) . "</td>";
                                        echo "<td>
                                            <a href='media.php?delete={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Delete this item?\")'>Delete</a>
                                        </td>";
                                        echo "</tr>";
                                    }
                                    if($result->num_rows == 0) echo "<tr><td colspan='4' class='text-center'>No media uploaded yet.</td></tr>";
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
