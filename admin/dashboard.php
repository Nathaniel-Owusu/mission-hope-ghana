<?php
include 'auth_session.php';
include 'db.php';

// Get Leadership Members Count
$members_count = $conn->query("SELECT COUNT(*) as count FROM leadership")->fetch_assoc()['count'];

// Get Departments Count
$dept_count = $conn->query("SELECT COUNT(*) as count FROM ministries")->fetch_assoc()['count'];

// Get Announcements Count
$announce_count = $conn->query("SELECT COUNT(*) as count FROM announcements")->fetch_assoc()['count'];

// Get Events Count
$event_count = $conn->query("SELECT COUNT(*) as count FROM events")->fetch_assoc()['count'];

// Get Messages Count (Optional if you want to display it somewhere, or just use it)
// $msg_count = $conn->query("SELECT COUNT(*) as count FROM messages")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Mission Hope – Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CSS -->
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

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

    <!-- Icons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>

<body class="bg-gray-50 font-sans text-gray-800 antialiased selection:bg-brand-gold selection:text-white">

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Area -->
        <div class="flex-1 ml-64 p-8">

            <!-- Top Bar -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 mb-8 flex justify-between items-center">
                <h5 class="text-xl font-bold text-brand-dark font-serif">Admin Dashboard</h5>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-brand-light/20 text-brand-dark flex items-center justify-center">
                        <ion-icon name="person-outline"></ion-icon>
                    </div>
                    <span class="font-medium text-gray-600">Welcome, Admin</span>
                </div>
            </div>

            <!-- Dashboard Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                    <h6 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Total Members</h6>
                    <h2 class="text-3xl font-bold text-brand-dark"><?php echo $members_count; ?></h2>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                    <h6 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Departments</h6>
                    <h2 class="text-3xl font-bold text-brand-dark"><?php echo $dept_count; ?></h2>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                    <h6 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Announcements</h6>
                    <h2 class="text-3xl font-bold text-brand-dark"><?php echo $announce_count; ?></h2>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                    <h6 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Upcoming Events</h6>
                    <h2 class="text-3xl font-bold text-brand-dark"><?php echo $event_count; ?></h2>
                </div>

            </div>

            <!-- Recent Data -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h5 class="text-lg font-bold text-brand-dark font-serif mb-4 pb-2 border-b border-gray-100">Latest Announcements</h5>
                    <ul class="space-y-3">
                        <?php
                        $ann_sql = "SELECT * FROM announcements ORDER BY id DESC LIMIT 5";
                        $ann_result = $conn->query($ann_sql);

                        if ($ann_result->num_rows > 0) {
                            while ($row = $ann_result->fetch_assoc()) {
                                echo '<li class="flex items-start gap-3 text-gray-600">
                                        <ion-icon name="ellipse" class="text-[8px] text-brand-gold mt-2"></ion-icon>
                                        ' . htmlspecialchars($row['title']) . '
                                      </li>';
                            }
                        } else {
                            echo '<li class="text-gray-500 italic">No announcements yet.</li>';
                        }
                        ?>
                    </ul>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h5 class="text-lg font-bold text-brand-dark font-serif mb-4 pb-2 border-b border-gray-100">Recent Messages</h5>
                    <ul class="space-y-3">
                        <?php
                        $msg_sql = "SELECT * FROM messages ORDER BY created_at DESC LIMIT 5";
                        $msg_result = $conn->query($msg_sql);

                        if ($msg_result->num_rows > 0) {
                            while ($row = $msg_result->fetch_assoc()) {
                                echo '<li class="flex items-start gap-3 text-gray-600">
                                        <ion-icon name="mail-unread-outline" class="text-brand-light mt-1"></ion-icon>
                                        <span><span class="font-bold text-gray-800">' . htmlspecialchars($row['first_name']) . '</span> – ' . htmlspecialchars($row['subject']) . '</span>
                                      </li>';
                            }
                        } else {
                            echo '<li class="text-gray-500 italic">No messages yet.</li>';
                        }
                        ?>
                    </ul>
                </div>

            </div>

        </div>
    </div>

</body>

</html>