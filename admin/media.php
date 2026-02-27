<?php
include 'db.php';
include 'auth_session.php';

// Handle Upload
if (isset($_POST['upload'])) {
    $title_base = $_POST['title'];
    $type = $_POST['type']; // 'image' or 'video'
    $description = $_POST['description']; // Optional description

    // Count total files
    $countfiles = count($_FILES['file']['name']);
    $success_count = 0;
    $error_count = 0;

    $target_dir = "../gallery_uploads/";
    if (!is_dir($target_dir)) {
        @mkdir($target_dir, 0755, true);
    }

    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'mp4', 'webm', 'ogg'];

    if (!is_dir($target_dir) || !is_writable($target_dir)) {
        $error = "Upload folder is not accessible. Please contact your administrator.";
    } else {
        // Loop through all files
        for ($i = 0; $i < $countfiles; $i++) {
            if ($_FILES['file']['error'][$i] == 0) {
                $filename = basename($_FILES['file']['name'][$i]);
                $fileType = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                if (in_array($fileType, $allowed)) {
                    // Unique filename to avoid overwrite
                    $unique_name = time() . "_$i" . "_" . $filename;
                    $target_file = $target_dir . $unique_name;

                    if (move_uploaded_file($_FILES['file']['tmp_name'][$i], $target_file)) {
                        // Store relative path
                        $db_path = "gallery_uploads/" . $unique_name;
                        $title = $countfiles > 1 ? "$title_base ($i)" : $title_base;

                        $stmt = $conn->prepare("INSERT INTO media (title, type, file_path, description) VALUES (?, ?, ?, ?)");
                        $stmt->bind_param("ssss", $title, $type, $db_path, $description);
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
}
// Delete logic
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
    <title>Media Gallery | Mission Hope Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            dark: '#052e16',
                            /* Darker Green */
                            main: '#1b4d3e',
                            light: '#34d399',
                            /* Brighter accent */
                            accent: '#10b981',
                            gold: '#fbbf24',
                            surface: '#ffffff',
                            bg: '#f8fafc'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    },
                    boxShadow: {
                        'soft': '0 4px 20px -2px rgba(0,0,0,0.05)',
                        'glow': '0 0 15px rgba(16, 185, 129, 0.3)',
                    }
                }
            }
        }
    </script>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f2f5;
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }

        .sidebar-link.active {
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0) 100%);
            border-left: 4px solid #fbbf24;
            color: #fbbf24;
        }

        .sidebar-link:hover:not(.active) {
            background: rgba(255, 255, 255, 0.05);
            color: white;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px -10px rgba(0, 0, 0, 0.1);
        }

        /* Custom Scrollbar for sidebar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }
    </style>
</head>

<body class="text-slate-800">

    <div class="flex h-screen overflow-hidden">

        <!-- Modern Dark Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col h-full bg-[#f0f2f5] overflow-y-auto relative">
            <div class="absolute top-0 left-0 w-full h-80 bg-[#022c22] z-0 rounded-b-[3rem]">
                <div class="absolute inset-0 opacity-20" style="background-image: url('../church%202.jpeg'); background-size: cover; background-position: center;"></div>
                <div class="absolute inset-0 bg-gradient-to-b from-transparent to-[#f0f2f5]/90"></div>
            </div>

            <div class="relative z-10 px-8 py-8 md:px-12">

                <!-- Header -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
                    <div>
                        <h2 class="text-3xl font-serif font-bold text-white mb-1">Media Library</h2>
                        <p class="text-emerald-100 text-sm font-light tracking-wide">Upload and manage photos & videos.</p>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="glass-panel rounded-full px-4 py-2 flex items-center shadow-lg">
                            <ion-icon name="search-outline" class="text-slate-500 mr-2"></ion-icon>
                            <input type="text" id="searchInput" placeholder="Search media..." class="bg-transparent border-none outline-none text-sm w-48 text-slate-700 placeholder-slate-500">
                        </div>
                        <button class="bg-white p-2.5 rounded-full shadow-lg text-emerald-800 hover:scale-105 transition-transform relative">
                            <ion-icon name="notifications" class="text-xl"></ion-icon>
                            <span class="absolute top-0 right-0 h-3 w-3 bg-red-500 rounded-full border-2 border-white"></span>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Upload Form -->
                    <div class="lg:col-span-1">
                        <div class="bg-white p-6 rounded-2xl shadow-soft border border-slate-100 sticky top-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                    <ion-icon name="cloud-upload-outline" class="text-xl"></ion-icon>
                                </div>
                                <h3 class="font-bold text-slate-800 text-lg">Upload Media</h3>
                            </div>

                            <form method="POST" enctype="multipart/form-data" class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Collection Title</label>
                                    <input type="text" name="title" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-gold focus:bg-white outline-none transition-all text-sm font-medium" placeholder="e.g. Easter Service 2024" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Description (Optional)</label>
                                    <textarea name="description" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-gold focus:bg-white outline-none transition-all text-sm font-medium resize-none h-20" placeholder="Brief caption..."></textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">File Type</label>
                                    <select name="type" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-gold focus:bg-white outline-none transition-all text-sm font-medium">
                                        <option value="image">Photos</option>
                                        <option value="video">Videos</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Select Files</label>
                                    <div class="relative border-2 border-dashed border-slate-200 rounded-xl p-6 text-center hover:bg-slate-50 transition-colors cursor-pointer group">
                                        <input type="file" name="file[]" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" multiple required />
                                        <ion-icon name="images-outline" class="text-3xl text-slate-300 mb-2 group-hover:text-emerald-500 transition-colors"></ion-icon>
                                        <p class="text-xs font-bold text-slate-500">Click to upload or drag & drop</p>
                                        <p class="text-[10px] text-slate-400 mt-1">Images, MP4, WebM (Max 50 files)</p>
                                    </div>
                                </div>
                                <button type="submit" name="upload" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-emerald-600/30 transition-all hover:-translate-y-1 mt-2 flex items-center justify-center gap-2">
                                    <ion-icon name="cloud-upload" class="text-lg"></ion-icon>
                                    Start Upload
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Gallery Grid -->
                    <div class="lg:col-span-2">
                        <div class="bg-white p-6 rounded-2xl shadow-soft border border-slate-100">
                            <div class="flex justify-between items-center mb-6 pl-2">
                                <h3 class="font-bold text-slate-800 text-lg">Gallery Items</h3>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4" id="mediaGrid">
                                <?php
                                $result = $conn->query("SELECT * FROM media ORDER BY id DESC");
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                ?>
                                        <div class="media-item group relative aspect-square rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shadow-sm hover:shadow-md transition-all">
                                            <?php if ($row['type'] == 'image'): ?>
                                                <img src="../<?php echo htmlspecialchars($row['file_path']); ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                            <?php else: ?>
                                                <div class="w-full h-full flex items-center justify-center bg-slate-800 text-white">
                                                    <ion-icon name="play-circle" class="text-4xl"></ion-icon>
                                                </div>
                                            <?php endif; ?>

                                            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/80 to-transparent p-3 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                                <p class="text-white text-xs font-bold truncate item-title"><?php echo htmlspecialchars($row['title']); ?></p>
                                                <div class="flex justify-between items-center mt-2">
                                                    <span class="text-[10px] text-slate-300 uppercase tracking-widest"><?php echo htmlspecialchars($row['type']); ?></span>
                                                    <a href="media.php?delete=<?php echo $row['id']; ?>" class="text-red-400 hover:text-red-200" onclick="return confirm('Delete this item?')">
                                                        <ion-icon name="trash"></ion-icon>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                <?php
                                    }
                                } else {
                                    echo '<div class="col-span-3 py-12 text-center text-slate-400 italic bg-slate-50 rounded-xl border border-dashed border-slate-200">No media found. Upload something to get started.</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let items = document.querySelectorAll('#mediaGrid .media-item');

            items.forEach(function(item) {
                let title = item.querySelector('.item-title').innerText.toLowerCase();
                if (title.includes(filter)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    </script>
</body>

</html>