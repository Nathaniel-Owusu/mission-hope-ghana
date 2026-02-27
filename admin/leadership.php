<?php
include 'db.php';
include 'auth_session.php';

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
        $target_dir = "../uploads/leaders/";
        if (!is_dir($target_dir)) {
            @mkdir($target_dir, 0755, true);
        }
        if (is_dir($target_dir) && is_writable($target_dir)) {
            $ext = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array($ext, $allowed)) {
                $unique_name = 'leader_' . time() . '_' . uniqid() . '.' . $ext;
                $target_file = $target_dir . $unique_name;
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $image = "uploads/leaders/" . $unique_name;
                }
            }
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

// Fetch Positions
$pos_res = $conn->query("SELECT * FROM positions ORDER BY name ASC");

// Fetch Leaders
$result = $conn->query("SELECT * FROM leadership ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Leadership | Mission Hope Admin</title>
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
                        <h2 class="text-3xl font-serif font-bold text-white mb-1">Leadership Team</h2>
                        <p class="text-emerald-100 text-sm font-light tracking-wide">Manage your church leaders and staff.</p>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="glass-panel rounded-full px-4 py-2 flex items-center shadow-lg">
                            <ion-icon name="search-outline" class="text-slate-500 mr-2"></ion-icon>
                            <input type="text" id="searchInput" placeholder="Search leaders..." class="bg-transparent border-none outline-none text-sm w-48 text-slate-700 placeholder-slate-500">
                        </div>
                        <button class="bg-white p-2.5 rounded-full shadow-lg text-emerald-800 hover:scale-105 transition-transform relative">
                            <ion-icon name="notifications" class="text-xl"></ion-icon>
                            <span class="absolute top-0 right-0 h-3 w-3 bg-red-500 rounded-full border-2 border-white"></span>
                        </button>
                    </div>
                </div>

                <!-- Manage Positions Card -->
                <div class="bg-white rounded-2xl shadow-soft p-6 card-hover border-slate-100 border-t-4 border-brand-gold mb-8">
                    <div class="flex justify-between items-center mb-4 border-b border-slate-100 pb-2">
                        <h3 class="font-bold text-lg text-slate-800">Manage Positions</h3>
                        <form method="POST" class="flex gap-2">
                            <input type="text" name="position_name" class="px-4 py-2 text-sm rounded-lg bg-slate-50 border border-slate-200 focus:border-brand-gold outline-none" placeholder="New Position Name" required>
                            <button type="submit" name="add_position" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider transition-colors shadow-lg shadow-emerald-600/20">+ Add</button>
                        </form>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <?php
                        if ($pos_res->num_rows > 0) {
                            while ($pos = $pos_res->fetch_assoc()) {
                                echo '<span class="bg-slate-50 text-slate-600 px-3 py-1.5 rounded-full text-xs font-bold border border-slate-200 flex items-center gap-2">
                                        ' . htmlspecialchars($pos['name']) . '
                                        <a href="leadership.php?delete_pos=' . $pos['id'] . '" class="text-slate-400 hover:text-red-500 transition-colors" onclick="return confirm(\'Delete this position?\')"><ion-icon name="close-circle"></ion-icon></a>
                                      </span>';
                            }
                        } else {
                            echo '<span class="text-slate-400 text-sm italic">No positions added yet.</span>';
                        }
                        ?>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Add Leader Form -->
                    <div class="lg:col-span-1">
                        <div class="bg-gradient-to-br from-[#022c22] to-emerald-900 p-6 rounded-2xl shadow-lg border border-emerald-800 sticky top-8 relative overflow-hidden group">
                            <div class="absolute inset-0 opacity-20" style="background-image: url('../church%202.jpeg'); background-size: cover;"></div>
                            <div class="relative z-10">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-10 h-10 rounded-lg bg-white/10 text-white flex items-center justify-center backdrop-blur-sm border border-white/10">
                                        <ion-icon name="person-add-outline" class="text-xl"></ion-icon>
                                    </div>
                                    <h3 class="font-bold text-white text-lg">Add New Leader</h3>
                                </div>

                                <form method="POST" enctype="multipart/form-data" class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-bold text-emerald-200 uppercase tracking-widest mb-1.5">Name</label>
                                        <input type="text" name="name" class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white placeholder-emerald-200/50 focus:border-brand-gold focus:bg-emerald-900/50 outline-none transition-all text-sm font-medium" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-emerald-200 uppercase tracking-widest mb-1.5">Role Description</label>
                                        <input type="text" name="role" class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white placeholder-emerald-200/50 focus:border-brand-gold focus:bg-emerald-900/50 outline-none transition-all text-sm font-medium" placeholder="e.g. Senior Pastor" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-emerald-200 uppercase tracking-widest mb-1.5">Position</label>
                                        <select name="category" class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white placeholder-emerald-200/50 focus:border-brand-gold focus:bg-emerald-900/50 outline-none transition-all text-sm font-medium" required>
                                            <option value="" class="text-slate-800">Select Position...</option>
                                            <?php
                                            if (isset($pos_res)) {
                                                $pos_res->data_seek(0);
                                                while ($pos = $pos_res->fetch_assoc()) {
                                                    echo '<option value="' . htmlspecialchars($pos['name']) . '" class="text-slate-800">' . htmlspecialchars($pos['name']) . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-emerald-200 uppercase tracking-widest mb-1.5">Email</label>
                                            <input type="text" name="email" class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white placeholder-emerald-200/50 focus:border-brand-gold focus:bg-emerald-900/50 outline-none transition-all text-sm font-medium">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-emerald-200 uppercase tracking-widest mb-1.5">Phone</label>
                                            <input type="text" name="phone" class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white placeholder-emerald-200/50 focus:border-brand-gold focus:bg-emerald-900/50 outline-none transition-all text-sm font-medium">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-emerald-200 uppercase tracking-widest mb-1.5">Profile Image</label>
                                        <input type="file" name="image" class="block w-full text-xs text-emerald-200
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-full file:border-0
                                          file:text-xs file:font-bold file:uppercase
                                          file:bg-white/10 file:text-white
                                          hover:file:bg-white/20
                                          cursor-pointer
                                        " />
                                    </div>
                                    <button type="submit" name="add_leader" class="w-full bg-white text-emerald-900 hover:bg-emerald-50 font-bold py-3 rounded-xl shadow-lg transition-all hover:-translate-y-1 mt-2">
                                        Save Leader
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- List -->
                    <div class="lg:col-span-2">
                        <div class="bg-white p-6 rounded-2xl shadow-soft border border-slate-100">
                            <div class="flex justify-between items-center mb-6 pl-2">
                                <h3 class="font-bold text-slate-800 text-lg">Current Leadership</h3>
                                <div class="flex gap-2">
                                    <button class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-50 text-slate-400 transition-colors"><ion-icon name="filter"></ion-icon></button>
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="text-xs text-slate-400 font-bold uppercase tracking-widest border-b border-slate-100">
                                            <th class="py-4 px-4">Profile</th>
                                            <th class="py-4 px-4">Name/Role</th>
                                            <th class="py-4 px-4">Contact</th>
                                            <th class="py-4 px-4 text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm" id="leadershipList">
                                        <?php if ($result->num_rows > 0): ?>
                                            <?php while ($row = $result->fetch_assoc()): ?>
                                                <tr class="leadership-item border-b border-slate-50 last:border-0 hover:bg-slate-50/80 transition-colors group">
                                                    <td class="py-4 px-4">
                                                        <?php if ($row['image']): ?>
                                                            <div class="h-12 w-12 rounded-full overflow-hidden border-2 border-white shadow-md">
                                                                <img src="../<?php echo htmlspecialchars($row['image']); ?>" class="w-full h-full object-cover">
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 font-bold text-lg">
                                                                <?php echo strtoupper(substr($row['name'], 0, 1)); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="py-4 px-4">
                                                        <p class="font-bold text-slate-800 item-name"><?php echo htmlspecialchars($row['name']); ?></p>
                                                        <p class="text-xs text-emerald-600 font-medium item-role"><?php echo htmlspecialchars($row['role']); ?></p>
                                                        <span class="inline-block mt-1 px-2 py-0.5 rounded-md bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-wide border border-slate-200"><?php echo htmlspecialchars($row['category']); ?></span>
                                                    </td>
                                                    <td class="py-4 px-4">
                                                        <div class="text-xs text-slate-500 space-y-1">
                                                            <?php if ($row['email']): ?>
                                                                <div class="flex items-center gap-2"><ion-icon name="mail-outline"></ion-icon> <?php echo htmlspecialchars($row['email']); ?></div>
                                                            <?php endif; ?>
                                                            <?php if ($row['phone']): ?>
                                                                <div class="flex items-center gap-2"><ion-icon name="call-outline"></ion-icon> <?php echo htmlspecialchars($row['phone']); ?></div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                    <td class="py-4 px-4 text-right">
                                                        <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                            <!-- <button class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center hover:bg-indigo-100 transition-colors"><ion-icon name="create-outline"></ion-icon></button> -->
                                                            <a href="leadership.php?delete=<?php echo $row['id']; ?>" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-100 transition-colors" onclick="return confirm('Delete this leader?')"><ion-icon name="trash-outline"></ion-icon></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="py-8 text-center text-slate-400 italic">No leaders found.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
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
            let items = document.querySelectorAll('#leadershipList .leadership-item');

            items.forEach(function(item) {
                let name = item.querySelector('.item-name').innerText.toLowerCase();
                let role = item.querySelector('.item-role').innerText.toLowerCase();
                if (name.includes(filter) || role.includes(filter)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    </script>
</body>

</html>