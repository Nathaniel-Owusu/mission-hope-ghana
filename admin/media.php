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
    for ($i = 0; $i < $countfiles; $i++) {
        if ($_FILES['file']['error'][$i] == 0) {
            $filename = basename($_FILES['file']['name'][$i]);
            $target_file = $target_dir . $filename;
            $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            if (in_array($fileType, $allowed)) {
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

    if ($success_count > 0) {
        $msg = "Successfully uploaded $success_count file(s).";
    }
    if ($error_count > 0) {
        $error = "Failed to upload $error_count file(s). Check file types or sizes.";
    }
}
// Delete logic remains same...
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $res = $conn->query("SELECT file_path FROM media WHERE id=$id");
    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $file_path = "../" . $row['file_path'];
        if (file_exists($file_path)) unlink($file_path);
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            dark: '#1b4d3e',
                            DEFAULT: '#2d6a52',
                            light: '#4a8c5a',
                            gold: '#d4a373',
                            cream: '#fcfbf7'
                        }
                    },
                    fontFamily: {
                        sans: ['Open Sans', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>

<body class="bg-gray-50 font-sans text-gray-800 antialiased selection:bg-brand-gold selection:text-white">

    <div class="flex min-h-screen">
        <?php include 'sidebar.php'; ?>

        <div class="flex-1 ml-64 p-8">
            <h5 class="text-2xl font-serif font-bold text-brand-dark mb-6">Media Gallery</h5>

            <?php if (isset($msg)) echo "<div class='bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 shadow-sm'>$msg</div>"; ?>
            <?php if (isset($error)) echo "<div class='bg-red-100 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6 shadow-sm'>$error</div>"; ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Upload Form -->
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 sticky top-8">
                        <h6 class="font-bold text-lg mb-4 text-gray-800 border-b pb-2">Bulk Upload Media</h6>
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Batch Title / Caption</label>
                                <input type="text" name="title" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20 outline-none transition-all" placeholder="e.g. Youth Camp 2024" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                                <select name="type" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20 outline-none transition-all bg-white">
                                    <option value="image">Photos</option>
                                    <option value="video">Videos</option>
                                </select>
                            </div>
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Select Files (Max 50)</label>
                                <input type="file" name="file[]" class="block w-full text-sm text-gray-500
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-full file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-brand-light/10 file:text-brand-dark
                                  hover:file:bg-brand-light/20
                                " multiple required />
                                <p class="text-xs text-gray-400 mt-2">Hold <kbd class="font-mono bg-gray-100 px-1 rounded border">Ctrl</kbd> or <kbd class="font-mono bg-gray-100 px-1 rounded border">Cmd</kbd> to select multiple files.</p>
                            </div>
                            <button type="submit" name="upload" class="w-full bg-brand-DEFAULT hover:bg-brand-dark text-white font-bold py-2 px-4 rounded-lg shadow transition-colors">
                                Upload All
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Gallery List -->
                <div class="lg:col-span-2">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <h6 class="font-bold text-lg mb-4 text-gray-800 border-b pb-2">Gallery Items</h6>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="text-xs text-gray-500 uppercase tracking-wider border-b border-gray-100">
                                        <th class="py-3 px-2 font-semibold">Preview</th>
                                        <th class="py-3 px-2 font-semibold">Title</th>
                                        <th class="py-3 px-2 font-semibold">Type</th>
                                        <th class="py-3 px-2 font-semibold text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <?php
                                    $result = $conn->query("SELECT * FROM media ORDER BY id DESC");
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr class='border-b last:border-0 hover:bg-gray-50 transition-colors'>";
                                            echo "<td class='py-3 px-2'>";
                                            if ($row['type'] == 'image') {
                                                echo "<img src='../{$row['file_path']}' class='w-20 h-16 object-cover rounded-md border border-gray-200 shadow-sm'>";
                                            } else {
                                                echo "<span class='bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs font-semibold border border-gray-200'>Video</span>";
                                            }
                                            echo "</td>";
                                            echo "<td class='py-3 px-2 font-medium text-gray-900'>" . htmlspecialchars($row['title']) . "</td>";
                                            echo "<td class='py-3 px-2 text-gray-500 capitalize'>" . htmlspecialchars($row['type']) . "</td>";
                                            echo "<td class='py-3 px-2 text-right'>
                                                <a href='media.php?delete={$row['id']}' class='bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1 rounded-md text-xs font-semibold transition-colors' onclick='return confirm(\"Delete this item?\")'>Delete</a>
                                            </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='4' class='py-4 text-center text-gray-500 italic'>No media uploaded yet.</td></tr>";
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