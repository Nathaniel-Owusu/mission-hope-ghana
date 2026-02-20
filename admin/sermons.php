<?php
include 'db.php';
include 'auth_session.php';

// Ensure sermons table exists
$conn->query("CREATE TABLE IF NOT EXISTS sermons (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    preacher VARCHAR(255),
    type VARCHAR(50) DEFAULT 'video',
    file_path VARCHAR(255),
    external_link VARCHAR(255),
    description TEXT,
    date_preached DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Handle Add Sermon
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_sermon'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $type = $conn->real_escape_string($_POST['type']);
    $link = $conn->real_escape_string($_POST['external_link']);
    $desc = $conn->real_escape_string($_POST['description']);
    $preacher = $conn->real_escape_string($_POST['preacher']);
    $date = $_POST['date_preached'];

    $file_path = "";
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $target_dir = "../uploads/sermons/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        $filename = time() . "_" . basename($_FILES["file"]["name"]);
        $target_file = $target_dir . $filename;

        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            $file_path = "uploads/sermons/" . $filename;
        }
    }

    $stmt = $conn->prepare("INSERT INTO sermons (title, preacher, type, file_path, external_link, description, date_preached) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $title, $preacher, $type, $file_path, $link, $desc, $date);

    if ($stmt->execute()) {
        $msg = "Sermon added successfully.";
    } else {
        $error = "Error adding sermon: " . $conn->error;
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $res = $conn->query("SELECT file_path FROM sermons WHERE id=$id");
    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        if ($row['file_path'] && file_exists("../" . $row['file_path'])) {
            unlink("../" . $row['file_path']);
        }
    }
    $conn->query("DELETE FROM sermons WHERE id=$id");
    header("Location: sermons.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sermons | Mission Hope Admin</title>
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
                        <h2 class="text-3xl font-serif font-bold text-white mb-1">Sermon Archive</h2>
                        <p class="text-emerald-100 text-sm font-light tracking-wide">Manage sermons, audio, and documents.</p>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="glass-panel rounded-full px-4 py-2 flex items-center shadow-lg">
                            <ion-icon name="search-outline" class="text-slate-500 mr-2"></ion-icon>
                            <input type="text" id="searchInput" placeholder="Search sermons..." class="bg-transparent border-none outline-none text-sm w-48 text-slate-700 placeholder-slate-500">
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
                                <h2 class="text-lg font-bold text-slate-800 font-serif">Add New Sermon</h2>
                            </div>

                            <?php if (isset($msg)): ?>
                                <div class="bg-emerald-100 text-emerald-700 px-4 py-2 rounded mb-4 text-xs font-bold"><?php echo $msg; ?></div>
                            <?php endif; ?>
                            <?php if (isset($error)): ?>
                                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-xs font-bold"><?php echo $error; ?></div>
                            <?php endif; ?>

                            <form method="POST" enctype="multipart/form-data" class="space-y-4">
                                <input type="hidden" name="add_sermon" value="1">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Sermon Title</label>
                                    <input type="text" name="title" required placeholder="e.g. Divine Favor" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-gold focus:bg-white outline-none transition-all text-sm font-medium">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Preacher</label>
                                    <input type="text" name="preacher" placeholder="e.g. Pastor John Doe" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-gold focus:bg-white outline-none transition-all text-sm font-medium">
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Date Preached</label>
                                        <input type="date" name="date_preached" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-gold focus:bg-white outline-none transition-all text-sm font-medium text-slate-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Type</label>
                                        <select name="type" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-gold focus:bg-white outline-none transition-all text-sm font-medium appearance-none">
                                            <option value="video">Video</option>
                                            <option value="audio">Audio</option>
                                            <option value="image">Image</option>
                                            <option value="document">PDF Document</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">External Link (YouTube, etc.)</label>
                                    <input type="url" name="external_link" placeholder="https://youtube.com/..." class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-gold focus:bg-white outline-none transition-all text-sm font-medium">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Or Upload File</label>
                                    <input type="file" name="file" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:uppercase file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" />
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Description</label>
                                    <textarea name="description" rows="3" placeholder="Brief summary..." class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-gold focus:bg-white outline-none transition-all text-sm font-medium resize-none"></textarea>
                                </div>

                                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl transition-all shadow-lg shadow-emerald-600/30 hover:-translate-y-1 flex items-center justify-center gap-2">
                                    <ion-icon name="save" class="text-lg"></ion-icon>
                                    <span>Save Sermon</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Library Grid -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-2xl shadow-soft border border-slate-100 overflow-hidden">
                            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 backdrop-blur-sm">
                                <h6 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Recent Sermons</h6>
                            </div>

                            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6" id="libraryGrid">
                                <?php
                                $sermons_res = $conn->query("SELECT * FROM sermons ORDER BY date_preached DESC");
                                if ($sermons_res->num_rows > 0) {
                                    while ($row = $sermons_res->fetch_assoc()) {
                                        $icon = "videocam";
                                        if ($row['type'] == 'audio') $icon = 'mic';
                                        if ($row['type'] == 'document') $icon = 'document-text';

                                ?>
                                        <div class="sermon-item group relative rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-100 bg-white">
                                            <div class="relative h-40 bg-slate-900 group">
                                                <!-- Simple Gradient Placeholder -->
                                                <div class="absolute inset-0 bg-gradient-to-br from-emerald-900 to-slate-900 opacity-80"></div>
                                                <div class="absolute inset-0 flex items-center justify-center">
                                                    <div class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white text-2xl group-hover:scale-110 transition-transform cursor-pointer">
                                                        <ion-icon name="<?php echo $icon; ?>"></ion-icon>
                                                    </div>
                                                </div>
                                                <div class="absolute top-3 right-3 flex gap-2">
                                                    <span class="px-2 py-1 rounded-md bg-black/60 text-white text-[10px] font-bold uppercase tracking-wider backdrop-blur-sm"><?php echo ucfirst($row['type']); ?></span>
                                                </div>
                                            </div>
                                            <div class="p-5">
                                                <h4 class="font-bold text-slate-800 text-sm mb-1 truncate item-title"><?php echo htmlspecialchars($row['title']); ?></h4>
                                                <p class="text-xs text-emerald-600 font-bold mb-2 item-preacher"><?php echo htmlspecialchars($row['preacher']); ?></p>
                                                <p class="text-xs text-slate-500 mb-4 line-clamp-2"><?php echo htmlspecialchars($row['description']); ?></p>

                                                <div class="flex justify-between items-center pt-3 border-t border-slate-50">
                                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider"><?php echo date('M d, Y', strtotime($row['date_preached'])); ?></span>
                                                    <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                        <?php if ($row['external_link']): ?>
                                                            <a href="<?php echo htmlspecialchars($row['external_link']); ?>" target="_blank" class="text-slate-400 hover:text-brand-gold"><ion-icon name="link"></ion-icon></a>
                                                        <?php endif; ?>
                                                        <a href="sermons.php?delete=<?php echo $row['id']; ?>" class="text-slate-400 hover:text-red-500 transition-colors" onclick="return confirm('Delete this sermon?')"><ion-icon name="trash-outline"></ion-icon></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                <?php
                                    }
                                } else {
                                    echo '<div class="col-span-2 py-12 text-center text-slate-400 italic">No sermons added yet.</div>';
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
            let items = document.querySelectorAll('#libraryGrid .sermon-item');

            items.forEach(function(item) {
                let title = item.querySelector('.item-title').innerText.toLowerCase();
                let preacher = item.querySelector('.item-preacher').innerText.toLowerCase();
                if (title.includes(filter) || preacher.includes(filter)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    </script>
</body>

</html>