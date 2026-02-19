<?php
include 'db.php';

// Add is_urgent column if it doesn't exist
$check_col = $conn->query("SHOW COLUMNS FROM announcements LIKE 'is_urgent'");
if ($check_col->num_rows == 0) {
    $conn->query("ALTER TABLE announcements ADD COLUMN is_urgent TINYINT(1) DEFAULT 0");
}

$msg = "";
$error = "";

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($conn->query("DELETE FROM announcements WHERE id=$id")) {
        header("Location: announcement.php");
        exit();
    } else {
        $error = "Error deleting announcement: " . $conn->error;
    }
}

// Handle Add Announcement
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_announcement'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $message = $conn->real_escape_string($_POST['message']);
    $is_urgent = isset($_POST['is_urgent']) ? 1 : 0;

    $stmt = $conn->prepare("INSERT INTO announcements (title, message, is_urgent) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $title, $message, $is_urgent);

    if ($stmt->execute()) {
        $msg = "Announcement posted successfully.";
    } else {
        $error = "Error posting announcement: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Announcements | Mission Hope Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            dark: '#0f392b',
                            main: '#1b4d3e',
                            light: '#2d6a52',
                            accent: '#4a8c5a',
                            gold: '#d4a373',
                            goldlight: '#eac298',
                            cream: '#fcfbf7',
                            surface: '#ffffff',
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    },
                    boxShadow: {
                        'soft': '0 10px 40px -10px rgba(0,0,0,0.08)',
                        'glow': '0 0 20px rgba(45, 106, 82, 0.15)',
                        'card': '0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02)'
                    },
                    backgroundImage: {
                        'sidebar-gradient': 'linear-gradient(180deg, #0f392b 0%, #1b4d3e 100%)',
                    }
                }
            }
        }
    </script>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <style>
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="bg-[#f3f5f8] font-sans text-gray-800 antialiased selection:bg-brand-gold selection:text-white">

    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Area -->
        <main class="flex-1 flex flex-col h-full relative overflow-y-auto">
            <div class="fixed top-0 left-0 w-full h-96 bg-gradient-to-b from-brand-light/5 to-transparent -z-10"></div>

            <!-- Header -->
            <header class="sticky top-0 z-40 px-8 py-5">
                <div class="glass rounded-2xl shadow-sm px-6 py-4 flex justify-between items-center transition-all hover:shadow-md">
                    <div class="flex items-center gap-4">
                        <button class="md:hidden text-gray-500 hover:text-brand-main">
                            <ion-icon name="menu-outline" class="text-2xl"></ion-icon>
                        </button>
                        <div>
                            <h1 class="text-xl font-bold text-brand-dark font-serif">Announcements</h1>
                            <p class="text-xs text-gray-500 font-medium mt-0.5">Manage church updates</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-6">
                        <button class="relative text-gray-400 hover:text-brand-gold transition-colors">
                            <ion-icon name="notifications-outline" class="text-xl"></ion-icon>
                            <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                        </button>
                        <div class="h-8 w-px bg-gray-200"></div>
                        <div class="flex items-center gap-3 cursor-pointer group">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-bold text-gray-700 group-hover:text-brand-main transition-colors">Admin User</p>
                                <p class="text-xs text-gray-400">Super Administrator</p>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-brand-gold/20 text-brand-gold flex items-center justify-center border-2 border-transparent group-hover:border-brand-gold transition-all">
                                <ion-icon name="person" class="text-lg"></ion-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div class="px-8 pb-8 animate-fade-in">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <!-- Add Announcement Form -->
                    <div class="lg:col-span-1">
                        <div class="bg-white p-8 rounded-2xl shadow-soft border border-gray-100 sticky top-28">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-lg bg-brand-main/10 text-brand-main flex items-center justify-center">
                                    <ion-icon name="create-outline" class="text-xl"></ion-icon>
                                </div>
                                <h2 class="text-lg font-bold text-gray-800 font-serif">New Announcement</h2>
                            </div>

                            <?php if ($msg): ?>
                                <div class="bg-emerald-100 text-emerald-700 px-4 py-2 rounded mb-4 text-xs font-bold"><?php echo $msg; ?></div>
                            <?php endif; ?>
                            <?php if ($error): ?>
                                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-xs font-bold"><?php echo $error; ?></div>
                            <?php endif; ?>

                            <form method="POST" class="space-y-5">
                                <input type="hidden" name="add_announcement" value="1">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Title</label>
                                    <input type="text" name="title" placeholder="e.g. Board Meeting" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-brand-gold focus:ring-4 focus:ring-brand-gold/10 outline-none transition-all bg-gray-50 focus:bg-white" required>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Message</label>
                                    <textarea name="message" rows="5" placeholder="Details of the announcement..." class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-brand-gold focus:ring-4 focus:ring-brand-gold/10 outline-none transition-all bg-gray-50 focus:bg-white resize-none" required></textarea>
                                </div>

                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" name="is_urgent" id="urgent" class="w-5 h-5 rounded border-gray-300 text-brand-gold focus:ring-brand-gold cursor-pointer">
                                    <span class="text-sm font-medium text-gray-600 group-hover:text-brand-main transition-colors">Mark as Urgent</span>
                                </label>

                                <button type="submit" class="w-full bg-brand-main hover:bg-brand-dark text-white font-bold py-3.5 rounded-xl transition-all shadow-lg shadow-brand-main/30 hover:shadow-xl hover:-translate-y-1 flex items-center justify-center gap-2">
                                    <ion-icon name="send" class="text-lg"></ion-icon>
                                    <span>Post Announcement</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Announcements List -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden">
                            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 backdrop-blur-sm">
                                <h6 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Active Announcements</h6>
                                <div class="flex gap-2">
                                    <!-- Search functionality could be added here later -->
                                    <button class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-400 transition-colors"><ion-icon name="filter"></ion-icon></button>
                                    <button class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-400 transition-colors"><ion-icon name="search"></ion-icon></button>
                                </div>
                            </div>

                            <div class="divide-y divide-gray-100">
                                <?php
                                $res = $conn->query("SELECT * FROM announcements ORDER BY created_at DESC");
                                if ($res->num_rows > 0) {
                                    while ($row = $res->fetch_assoc()) {
                                        $urgentBadge = $row['is_urgent'] ? '<span class="text-xs font-semibold text-brand-gold bg-brand-gold/10 px-2 py-1 rounded">Urgent</span>' : '';
                                        $borderClass = $row['is_urgent'] ? 'bg-brand-gold' : 'bg-brand-light';
                                ?>
                                        <div class="p-6 hover:bg-brand-cream/30 transition-colors group cursor-pointer relative overflow-hidden">
                                            <div class="absolute left-0 top-0 bottom-0 w-1 <?php echo $borderClass; ?> opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                            <div class="flex justify-between items-start mb-2">
                                                <div class="flex items-center gap-3">
                                                    <span class="w-2.5 h-2.5 rounded-full <?php echo $borderClass; ?>"></span>
                                                    <h4 class="font-bold text-lg text-gray-800"><?php echo htmlspecialchars($row['title']); ?></h4>
                                                </div>
                                                <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-all translate-x-4 group-hover:translate-x-0">
                                                    <!-- Edit could go here -->
                                                    <!-- <button class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center transition-colors"><ion-icon name="create-outline"></ion-icon></button> -->
                                                    <a href="announcement.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this announcement?')" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition-colors"><ion-icon name="trash-outline"></ion-icon></a>
                                                </div>
                                            </div>
                                            <p class="text-gray-600 mb-4 text-sm leading-relaxed pl-5.5"><?php echo nl2br(htmlspecialchars($row['message'])); ?></p>
                                            <div class="flex items-center gap-4 pl-5.5">
                                                <span class="text-xs font-semibold text-gray-400 bg-gray-100 px-2 py-1 rounded">
                                                    <ion-icon name="calendar-outline" class="mr-1"></ion-icon> <?php echo date('M d, Y', strtotime($row['created_at'])); ?>
                                                </span>
                                                <?php echo $urgentBadge; ?>
                                            </div>
                                        </div>
                                <?php
                                    }
                                } else {
                                    echo '<div class="p-8 text-center text-gray-500 italic">No active announcements.</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>