<?php
include 'db.php';
include 'auth_session.php';

// Handle Add Main Department
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_ministry'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $leader_name = $_POST['leader_name'];

    // Main Image Upload
    $image = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../uploads/ministries/";
        // Try to create directory if it doesn't exist
        if (!is_dir($target_dir)) {
            @mkdir($target_dir, 0755, true);
        }
        if (is_dir($target_dir) && is_writable($target_dir)) {
            $ext = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array($ext, $allowed)) {
                $unique_name = 'ministry_' . time() . '_' . uniqid() . '.' . $ext;
                $target_file = $target_dir . $unique_name;
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $image = "uploads/ministries/" . $unique_name;
                }
            }
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
    <title>Departments | Mission Hope Admin</title>
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
                        <h2 class="text-3xl font-serif font-bold text-white mb-1">Departments</h2>
                        <p class="text-emerald-100 text-sm font-light tracking-wide">Manage ministry departments and leaders.</p>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="glass-panel rounded-full px-4 py-2 flex items-center shadow-lg">
                            <ion-icon name="search-outline" class="text-slate-500 mr-2"></ion-icon>
                            <input type="text" id="searchInput" placeholder="Search ministries..." class="bg-transparent border-none outline-none text-sm w-48 text-slate-700 placeholder-slate-500">
                        </div>
                        <button class="bg-white p-2.5 rounded-full shadow-lg text-emerald-800 hover:scale-105 transition-transform relative">
                            <ion-icon name="notifications" class="text-xl"></ion-icon>
                            <span class="absolute top-0 right-0 h-3 w-3 bg-red-500 rounded-full border-2 border-white"></span>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Add Department Form -->
                    <div class="lg:col-span-1">
                        <div class="bg-white p-6 rounded-2xl shadow-soft border border-slate-100 sticky top-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                    <ion-icon name="add-circle-outline" class="text-xl"></ion-icon>
                                </div>
                                <h3 class="font-bold text-slate-800 text-lg">Add New Ministry</h3>
                            </div>

                            <form method="POST" enctype="multipart/form-data" class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Ministry Name</label>
                                    <input type="text" name="title" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-gold focus:bg-white outline-none transition-all text-sm font-medium" placeholder="e.g. Women's Ministry" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Description</label>
                                    <textarea name="description" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-gold focus:bg-white outline-none transition-all text-sm font-medium resize-none h-32" placeholder="Brief description of the ministry..." required></textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Lead Coordinator</label>
                                    <input type="text" name="leader_name" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-gold focus:bg-white outline-none transition-all text-sm font-medium" placeholder="Full Name">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Cover Image</label>
                                    <input type="file" name="image" class="block w-full text-xs text-slate-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-full file:border-0
                                      file:text-xs file:font-bold file:uppercase
                                      file:bg-emerald-50 file:text-emerald-700
                                      hover:file:bg-emerald-100
                                    " />
                                </div>
                                <button type="submit" name="add_ministry" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-emerald-600/30 transition-all hover:-translate-y-1 mt-2">
                                    Create Ministry
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- List -->
                    <div class="lg:col-span-2">
                        <div class="bg-white p-6 rounded-2xl shadow-soft border border-slate-100">
                            <div class="flex justify-between items-center mb-6 pl-2">
                                <h3 class="font-bold text-slate-800 text-lg">Active Ministries</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="ministryList">
                                <?php
                                $result = $conn->query("SELECT * FROM ministries");
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $bg_image = $row['image'] ? "../" . htmlspecialchars($row['image']) : '../ministries.jpg';
                                ?>
                                        <div class="ministry-item bg-white group rounded-xl border border-slate-200 overflow-hidden hover:shadow-lg transition-all relative">
                                            <div class="h-32 bg-slate-100 relative overflow-hidden">
                                                <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-110" style="background-image: url('<?php echo $bg_image; ?>');"></div>
                                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 to-transparent"></div>
                                                <div class="absolute bottom-3 left-4 text-white">
                                                    <h4 class="font-bold text-lg leading-tight item-title"><?php echo htmlspecialchars($row['title']); ?></h4>
                                                    <p class="text-xs text-slate-300 font-medium mt-0.5">Lead: <?php echo htmlspecialchars($row['leader_name'] ?: 'TBA'); ?></p>
                                                </div>

                                                <a href="departments.php?delete=<?php echo $row['id']; ?>" class="absolute top-2 right-2 bg-white/20 hover:bg-red-500 text-white p-1.5 rounded-lg backdrop-blur-sm transition-colors opacity-0 group-hover:opacity-100" onclick="return confirm('Delete this ministry?')">
                                                    <ion-icon name="trash-outline"></ion-icon>
                                                </a>
                                            </div>
                                            <div class="p-4">
                                                <p class="text-xs text-slate-500 leading-relaxed line-clamp-3 mb-3">
                                                    <?php echo htmlspecialchars($row['description']); ?>
                                                </p>
                                                <div class="flex items-center justify-between mt-auto pt-3 border-t border-slate-50">
                                                    <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wide bg-emerald-50 px-2 py-1 rounded inline-block">Active Ministry</span>
                                                </div>
                                            </div>
                                        </div>
                                <?php
                                    }
                                } else {
                                    echo '<div class="col-span-2 py-12 text-center text-slate-400 italic bg-slate-50 rounded-xl border border-dashed border-slate-200">No ministries found. Add one to get started.</div>';
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
            let items = document.querySelectorAll('#ministryList .ministry-item');

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