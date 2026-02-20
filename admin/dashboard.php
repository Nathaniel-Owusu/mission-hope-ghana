<?php
// admin/dashboard.php
include 'db.php'; // Ensure database connection
include 'auth_session.php';

// --- Fetch Stats ---
$members_query = $conn->query("SELECT COUNT(*) as count FROM leadership"); // Placeholder table for members
$members_count = $members_query ? $members_query->fetch_assoc()['count'] : 0;

$depts_query = $conn->query("SELECT COUNT(*) as count FROM ministries");
$depts_count = $depts_query ? $depts_query->fetch_assoc()['count'] : 0;

$events_query = $conn->query("SELECT COUNT(*) as count FROM events");
$events_count = $events_query ? $events_query->fetch_assoc()['count'] : 0;

$msgs_query = $conn->query("SELECT COUNT(*) as count FROM messages");
$msgs_count = $msgs_query ? $msgs_query->fetch_assoc()['count'] : 0;

// --- Fetch Recent Messages ---
$recent_msgs = $conn->query("SELECT * FROM messages ORDER BY created_at DESC LIMIT 3");

// --- Fetch Upcoming Events ---
$upcoming_events = $conn->query("SELECT * FROM events ORDER BY id DESC LIMIT 2");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Mission Hope Admin</title>
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

    <!-- Charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
            <!-- Fancy Background Header -->
            <div class="absolute top-0 left-0 w-full h-80 bg-[#022c22] z-0 rounded-b-[3rem]">
                <div class="absolute inset-0 opacity-20" style="background-image: url('../church%202.jpeg'); background-size: cover; background-position: center;"></div>
                <div class="absolute inset-0 bg-gradient-to-b from-transparent to-[#f0f2f5]/90"></div>
            </div>

            <div class="relative z-10 px-8 py-8 md:px-12">

                <!-- Top Bar -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
                    <div>
                        <h2 class="text-3xl font-serif font-bold text-white mb-1">Dashboard</h2>
                        <p class="text-emerald-100 text-sm font-light tracking-wide" id="current-date">Welcome back to Mission Hope Administration</p>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="glass-panel rounded-full px-4 py-2 flex items-center shadow-lg">
                            <ion-icon name="search-outline" class="text-slate-500 mr-2"></ion-icon>
                            <input type="text" placeholder="Search..." class="bg-transparent border-none outline-none text-sm w-48 text-slate-700 placeholder-slate-500">
                        </div>
                        <button class="bg-white p-2.5 rounded-full shadow-lg text-emerald-800 hover:scale-105 transition-transform relative">
                            <ion-icon name="notifications" class="text-xl"></ion-icon>
                            <span class="absolute top-0 right-0 h-3 w-3 bg-red-500 rounded-full border-2 border-white"></span>
                        </button>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                    <!-- Stat 1 -->
                    <div class="bg-white rounded-2xl p-6 shadow-soft card-hover border-l-4 border-emerald-500 relative overflow-hidden">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Total Team</p>
                                <h3 class="text-3xl font-bold text-slate-800"><?php echo $members_count; ?></h3>
                            </div>
                            <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600">
                                <ion-icon name="people" class="text-2xl"></ion-icon>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-xs font-medium text-emerald-600">
                            <span class="flex items-center bg-emerald-100 px-2 py-0.5 rounded-full"><ion-icon name="arrow-up" class="mr-1"></ion-icon> Active</span>
                            <span class="ml-2 text-slate-400">Leadership</span>
                        </div>
                    </div>

                    <!-- Stat 2 -->
                    <div class="bg-white rounded-2xl p-6 shadow-soft card-hover border-l-4 border-amber-400 relative overflow-hidden">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Departments</p>
                                <h3 class="text-3xl font-bold text-slate-800"><?php echo $depts_count; ?></h3>
                            </div>
                            <div class="p-3 bg-amber-50 rounded-xl text-amber-500">
                                <ion-icon name="briefcase" class="text-2xl"></ion-icon>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-xs font-medium text-slate-500">
                            <span class="text-amber-500">Active</span>
                            <span class="ml-2 text-slate-400">Ministries</span>
                        </div>
                    </div>

                    <!-- Stat 3 -->
                    <div class="bg-white rounded-2xl p-6 shadow-soft card-hover border-l-4 border-blue-500 relative overflow-hidden">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Events</p>
                                <h3 class="text-3xl font-bold text-slate-800"><?php echo $events_count; ?></h3>
                            </div>
                            <div class="p-3 bg-blue-50 rounded-xl text-blue-500">
                                <ion-icon name="calendar" class="text-2xl"></ion-icon>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-xs font-medium text-blue-600">
                            <span>Scheduled</span>
                        </div>
                    </div>

                    <!-- Stat 4 -->
                    <div class="bg-white rounded-2xl p-6 shadow-soft card-hover border-l-4 border-purple-500 relative overflow-hidden">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Messages</p>
                                <h3 class="text-3xl font-bold text-slate-800"><?php echo $msgs_count; ?></h3>
                            </div>
                            <div class="p-3 bg-purple-50 rounded-xl text-purple-500">
                                <ion-icon name="chatbubbles" class="text-2xl"></ion-icon>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-xs font-medium text-red-500">
                            <span class="flex items-center bg-red-50 px-2 py-0.5 rounded-full">New</span>
                        </div>
                    </div>
                </div>

                <!-- Main Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <!-- Left (Chart & Tables) -->
                    <div class="lg:col-span-2 space-y-8">

                        <!-- Chart Section -->
                        <div class="bg-white rounded-2xl shadow-soft p-6 border-slate-100">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="font-bold text-slate-800 text-lg">Attendance Overview</h3>
                                <select class="text-xs bg-slate-50 border-none rounded-lg px-3 py-2 text-slate-600 outline-none cursor-pointer hover:bg-slate-100">
                                    <option>Last 6 Months</option>
                                    <option>Last Year</option>
                                </select>
                            </div>
                            <div class="relative h-64 w-full">
                                <canvas id="attendanceChart"></canvas>
                            </div>
                        </div>

                        <!-- Recent Messages -->
                        <div class="bg-white rounded-2xl shadow-soft border-slate-100 overflow-hidden">
                            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                                <h3 class="font-bold text-slate-800 text-lg">Inbox</h3>
                                <a href="messages.php" class="text-xs font-bold text-emerald-600 hover:text-emerald-700">View All</a>
                            </div>
                            <div class="divide-y divide-slate-100">
                                <?php if ($recent_msgs->num_rows > 0): ?>
                                    <?php while ($msg = $recent_msgs->fetch_assoc()): ?>
                                        <div class="px-6 py-4 hover:bg-slate-50 transition-colors flex items-center gap-4 cursor-pointer">
                                            <div class="h-10 w-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm">
                                                <?php echo strtoupper(substr($msg['first_name'], 0, 1) . substr($msg['last_name'], 0, 1)); ?>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex justify-between">
                                                    <h4 class="text-sm font-bold text-slate-800 truncate"><?php echo htmlspecialchars($msg['first_name'] . ' ' . $msg['last_name']); ?></h4>
                                                    <span class="text-xs text-slate-400"><?php echo date("M d", strtotime($msg['created_at'])); ?></span>
                                                </div>
                                                <p class="text-xs text-slate-500 truncate"><?php echo htmlspecialchars($msg['subject']); ?></p>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <div class="p-4 text-center text-gray-400 text-sm">No new messages.</div>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>

                    <!-- Right (Shortcuts & Calendar) -->
                    <div class="space-y-8">

                        <!-- Welcome / Action Card -->
                        <div class="bg-gradient-to-br from-[#022c22] to-emerald-900 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden group">
                            <div class="absolute inset-0 opacity-20" style="background-image: url('../church%202.jpeg'); background-size: cover;"></div>
                            <div class="relative z-10">
                                <h3 class="font-serif text-2xl font-bold mb-2">Hello, Admin</h3>
                                <p class="text-emerald-100 text-sm mb-6">You have administrative access. What would you like to do today?</p>
                                <div class="flex flex-col gap-3">
                                    <a href="sms.php" class="w-full py-2.5 px-4 bg-white text-emerald-900 rounded-lg text-sm font-bold text-center hover:bg-emerald-50 transition-colors shadow-lg">Send SMS Blast</a>
                                    <a href="announcement.php" class="w-full py-2.5 px-4 bg-emerald-800/50 backdrop-blur-md border border-emerald-500/30 text-white rounded-lg text-sm font-bold text-center hover:bg-emerald-800 transition-colors">Post Announcement</a>
                                </div>
                            </div>
                        </div>

                        <!-- Upcoming Events List -->
                        <div class="bg-white rounded-2xl shadow-soft p-6">
                            <h3 class="font-bold text-slate-800 text-lg mb-4">Upcoming Events</h3>

                            <div class="space-y-4">
                                <?php if ($upcoming_events->num_rows > 0): ?>
                                    <?php while ($evt = $upcoming_events->fetch_assoc()): ?>
                                        <div class="flex gap-4 group cursor-pointer">
                                            <div class="w-14 h-14 bg-amber-50 rounded-xl flex flex-col items-center justify-center text-amber-600 border border-amber-100 group-hover:bg-amber-100 transition-colors">
                                                <span class="text-[10px] font-bold uppercase"><?php echo htmlspecialchars($evt['month_short']); ?></span>
                                                <span class="text-xl font-bold leading-none"><?php echo htmlspecialchars($evt['day_num']); ?></span>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-slate-800 text-sm group-hover:text-emerald-700 transition-colors"><?php echo htmlspecialchars($evt['title']); ?></h4>
                                                <p class="text-xs text-slate-500 mb-1"><?php echo htmlspecialchars($evt['time_str']); ?></p>
                                                <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full">General</span>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <p class="text-sm text-gray-500">No upcoming events.</p>
                                <?php endif; ?>
                            </div>

                            <a href="events.php" class="block w-full mt-6 py-2 text-xs font-bold text-center text-slate-500 hover:text-emerald-600 border border-slate-200 rounded-xl hover:bg-slate-50 transition-all">View All Calendar</a>
                        </div>

                        <!-- Active Departments (Donut Chart placeholder) -->
                        <div class="bg-white rounded-2xl shadow-soft p-6">
                            <h3 class="font-bold text-slate-800 text-lg mb-4">Member Distribution</h3>
                            <div class="relative h-48 w-full flex items-center justify-center">
                                <canvas id="memberChart"></canvas>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </main>
    </div>

    <script>
        // Set Date
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        document.getElementById('current-date').innerText = new Date().toLocaleDateString('en-US', options);

        // Attendance Chart
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
        gradient.addColorStop(1, 'rgba(16, 185, 129, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5', 'Week 6'],
                datasets: [{
                    label: 'Attendance',
                    data: [120, 135, 125, 142, 138, 155],
                    borderColor: '#10b981',
                    backgroundColor: gradient,
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#10b981',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            borderDash: [2, 4],
                            color: '#f1f5f9'
                        },
                        ticks: {
                            font: {
                                size: 10
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 10
                            }
                        }
                    }
                }
            }
        });

        // Member Chart
        const memberCtx = document.getElementById('memberChart').getContext('2d');
        new Chart(memberCtx, {
            type: 'doughnut',
            data: {
                labels: ['Men', 'Women', 'Youth', 'Children'],
                datasets: [{
                    data: [30, 40, 20, 10],
                    backgroundColor: ['#0f766e', '#10b981', '#fbbf24', '#f59e0b'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            font: {
                                size: 10
                            },
                            boxWidth: 8
                        }
                    }
                },
                cutout: '70%'
            }
        });
    </script>
</body>

</html>