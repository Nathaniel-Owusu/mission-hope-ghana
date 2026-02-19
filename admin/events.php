<?php
// admin/events.php
session_start();
// include 'auth_session.php'; // Uncomment when auth is ready
include 'db.php';

// Handle Add Event
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_event'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $date_val = $_POST['date']; // YYYY-MM-DD
    $time_str = $conn->real_escape_string($_POST['time']);
    $location = $conn->real_escape_string($_POST['location']);
    $description = $conn->real_escape_string($_POST['description']);

    // Process Date for display
    $timestamp = strtotime($date_val);
    $month_short = strtoupper(date("M", $timestamp)); // JAN
    $day_num = date("d", $timestamp); // 27
    $date_str = date("F j, Y", $timestamp); // January 27, 2024

    // Handle Image Upload
    $image = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../gallery_uploads/"; // Ensure this directory exists and is writable
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = "gallery_uploads/" . $new_filename; // Store relative path for frontend
        }
    }

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO events (title, date_str, time_str, description, image, month_short, day_num) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $title, $date_str, $time_str, $description, $image, $month_short, $day_num);

    if ($stmt->execute()) {
        header("Location: events.php");
        exit();
    } else {
        $error = "Error: " . $stmt->error;
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM events WHERE id=$id");
    header("Location: events.php");
    exit();
}

// Fetch Events
$result = $conn->query("SELECT * FROM events ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Events Manager | Mission Hope Admin</title>
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
                        <h2 class="text-3xl font-serif font-bold text-white mb-1">Events Manager</h2>
                        <p class="text-emerald-100 text-sm font-light tracking-wide">Plan and schedule church events.</p>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="glass-panel rounded-full px-4 py-2 flex items-center shadow-lg">
                            <ion-icon name="search-outline" class="text-slate-500 mr-2"></ion-icon>
                            <input type="text" id="searchInput" placeholder="Search events..." class="bg-transparent border-none outline-none text-sm w-48 text-slate-700 placeholder-slate-500">
                        </div>
                        <button class="bg-white p-2.5 rounded-full shadow-lg text-emerald-800 hover:scale-105 transition-transform relative">
                            <ion-icon name="notifications" class="text-xl"></ion-icon>
                            <span class="absolute top-0 right-0 h-3 w-3 bg-red-500 rounded-full border-2 border-white"></span>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <!-- Add Event Form -->
                    <div class="lg:col-span-1">
                        <div class="bg-white p-6 rounded-2xl shadow-soft border border-slate-100 sticky top-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                    <ion-icon name="calendar-outline" class="text-xl"></ion-icon>
                                </div>
                                <h3 class="font-bold text-slate-800 text-lg">Create New Event</h3>
                            </div>

                            <form method="POST" enctype="multipart/form-data" class="space-y-4">
                                <input type="hidden" name="add_event" value="1">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Event Title</label>
                                    <input type="text" name="title" placeholder="e.g. Youth Camp 2026" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-gold focus:bg-white outline-none transition-all text-sm font-medium" required>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Date</label>
                                        <input type="date" name="date" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-gold focus:bg-white outline-none transition-all text-sm font-medium" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Time</label>
                                        <input type="time" name="time" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-gold focus:bg-white outline-none transition-all text-sm font-medium" required>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Location</label>
                                    <input type="text" name="location" placeholder="e.g. Church Auditorium" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-gold focus:bg-white outline-none transition-all text-sm font-medium">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Description</label>
                                    <textarea name="description" rows="3" placeholder="Brief details..." class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-gold focus:bg-white outline-none transition-all text-sm font-medium resize-none"></textarea>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Event Image</label>
                                    <input type="file" name="image" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:uppercase file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" />
                                </div>

                                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-emerald-600/30 transition-all hover:-translate-y-1 mt-2 flex items-center justify-center gap-2">
                                    <ion-icon name="add-circle" class="text-lg"></ion-icon>
                                    <span>Publish Event</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Events List -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-2xl shadow-soft border border-slate-100 overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                                <h3 class="font-bold text-slate-800 text-lg">Upcoming Events</h3>
                                <div class="flex gap-2">
                                    <button class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-200 text-slate-400 transition-colors"><ion-icon name="filter"></ion-icon></button>
                                </div>
                            </div>

                            <div class="divide-y divide-slate-100" id="eventList">
                                <?php if ($result->num_rows > 0): ?>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <!-- Event Item -->
                                        <div class="event-item p-6 flex items-start gap-5 hover:bg-emerald-50/30 transition-colors group relative overflow-hidden">
                                            <div class="absolute w-1 h-full left-0 top-0 bg-brand-gold opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                            <div class="w-16 h-16 bg-emerald-50 rounded-xl flex flex-col items-center justify-center text-emerald-800 flex-shrink-0 border border-emerald-100 shadow-sm group-hover:scale-105 transition-transform">
                                                <span class="text-[10px] font-bold uppercase tracking-widest text-emerald-600 mb-0.5"><?php echo htmlspecialchars($row['month_short']); ?></span>
                                                <span class="text-2xl font-bold font-serif"><?php echo htmlspecialchars($row['day_num']); ?></span>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex justify-between items-start">
                                                    <div>
                                                        <h4 class="font-bold text-lg text-slate-800 mb-1 group-hover:text-emerald-700 transition-colors item-title"><?php echo htmlspecialchars($row['title']); ?></h4>
                                                        <div class="flex items-center gap-4 mb-2">
                                                            <span class="text-xs font-semibold text-slate-500 flex items-center gap-1.5 item-time">
                                                                <ion-icon name="time-outline"></ion-icon> <?php echo htmlspecialchars($row['time_str']); ?>
                                                            </span>
                                                            <span class="text-xs font-semibold text-slate-500 flex items-center gap-1.5 item-date">
                                                                <ion-icon name="location-outline"></ion-icon> <?php echo htmlspecialchars($row['date_str']); ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-all translate-x-4 group-hover:translate-x-0">
                                                        <a href="events.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this event?');" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition-colors shadow-sm"><ion-icon name="trash-outline"></ion-icon></a>
                                                    </div>
                                                </div>
                                                <p class="text-sm text-slate-600 line-clamp-2 leading-relaxed item-desc"><?php echo htmlspecialchars($row['description']); ?></p>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <div class="p-8 text-center text-gray-400 italic">
                                        <p>No upcoming events found.</p>
                                    </div>
                                <?php endif; ?>
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
            let items = document.querySelectorAll('#eventList .event-item');

            items.forEach(function(item) {
                let title = item.querySelector('.item-title').innerText.toLowerCase();
                let desc = item.querySelector('.item-desc').innerText.toLowerCase();
                let date = item.querySelector('.item-date').innerText.toLowerCase();
                if (title.includes(filter) || desc.includes(filter) || date.includes(filter)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    </script>
</body>

</html>