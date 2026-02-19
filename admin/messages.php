<?php
include 'db.php';
// include 'auth_session.php'; // Uncomment when auth is ready

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM messages WHERE id=$id");
    header("Location: messages.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Inbox | Mission Hope Admin</title>
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
                        <h2 class="text-3xl font-serif font-bold text-white mb-1">Messages</h2>
                        <p class="text-emerald-100 text-sm font-light tracking-wide">View and manage incoming inquiries.</p>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="glass-panel rounded-full px-4 py-2 flex items-center shadow-lg">
                            <ion-icon name="search-outline" class="text-slate-500 mr-2"></ion-icon>
                            <input type="text" id="searchInput" placeholder="Search mail..." class="bg-transparent border-none outline-none text-sm w-48 text-slate-700 placeholder-slate-500">
                        </div>
                        <button class="bg-white p-2.5 rounded-full shadow-lg text-emerald-800 hover:scale-105 transition-transform relative">
                            <ion-icon name="notifications" class="text-xl"></ion-icon>
                            <span class="absolute top-0 right-0 h-3 w-3 bg-red-500 rounded-full border-2 border-white"></span>
                        </button>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-soft border border-slate-100 overflow-hidden min-h-[500px] flex flex-col">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-50">Filter by Date</button>
                            <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-50">Mark all Read</button>
                        </div>
                        <span class="text-xs text-slate-400 font-medium">Showing latest messages</span>
                    </div>

                    <div class="overflow-x-auto flex-1">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-xs text-slate-400 font-bold uppercase tracking-widest border-b border-slate-100 bg-slate-50/30">
                                    <th class="py-4 px-6 w-48">From</th>
                                    <th class="py-4 px-6">Subject / Message</th>
                                    <th class="py-4 px-6 w-32">Date</th>
                                    <th class="py-4 px-6 w-24 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-slate-50" id="messageList">
                                <?php
                                $result = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                ?>
                                        <tr class="message-item hover:bg-blue-50/30 transition-colors group cursor-pointer relative">
                                            <td class="py-4 px-6 align-top">
                                                <div class="flex items-center gap-3">
                                                    <div class="h-8 w-8 rounded-full bg-gradient-to-tr from-emerald-400 to-cyan-500 text-white flex items-center justify-center font-bold text-xs shadow-md">
                                                        <?php echo strtoupper(substr($row['first_name'], 0, 1)); ?>
                                                    </div>
                                                    <div>
                                                        <p class="font-bold text-slate-800 text-sm item-sender"><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></p>
                                                        <p class="text-xs text-slate-400"><?php echo htmlspecialchars($row['email']); ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4 px-6 align-top">
                                                <p class="font-bold text-slate-700 mb-1 item-subject"><?php echo htmlspecialchars($row['subject']); ?></p>
                                                <p class="text-slate-500 text-xs leading-relaxed line-clamp-2 max-w-xl item-content">
                                                    <?php echo htmlspecialchars($row['message']); ?>
                                                </p>
                                            </td>
                                            <td class="py-4 px-6 text-xs text-slate-400 font-medium align-top pt-5">
                                                <?php echo date('M d, Y', strtotime($row['created_at'])); ?><br>
                                                <span class="text-[10px] opacity-70"><?php echo date('h:i A', strtotime($row['created_at'])); ?></span>
                                            </td>
                                            <td class="py-4 px-6 text-right align-top pt-4">
                                                <a href="messages.php?delete=<?php echo $row['id']; ?>" class="opacity-0 group-hover:opacity-100 transition-opacity p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg inline-flex" onclick="return confirm('Delete this message permanently?')">
                                                    <ion-icon name="trash-outline" class="text-lg"></ion-icon>
                                                </a>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                    echo '<tr><td colspan="4" class="py-12 text-center text-slate-400 italic">No messages found.</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let items = document.querySelectorAll('#messageList .message-item');

            items.forEach(function(item) {
                let sender = item.querySelector('.item-sender').innerText.toLowerCase();
                let subject = item.querySelector('.item-subject').innerText.toLowerCase();
                let content = item.querySelector('.item-content').innerText.toLowerCase();

                if (sender.includes(filter) || subject.includes(filter) || content.includes(filter)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    </script>
</body>

</html>