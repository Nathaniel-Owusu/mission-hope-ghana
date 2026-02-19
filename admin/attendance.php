<?php
include 'db.php';

// Ensure tables exist
$conn->query("CREATE TABLE IF NOT EXISTS attendance_records (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    service_type VARCHAR(50) NOT NULL,
    attendees_count INT(11) NOT NULL,
    member_ids TEXT, 
    visitors_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$success_msg = "";
$error_msg = "";

// Handle Submit Attendance
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_attendance'])) {
    $date = $conn->real_escape_string($_POST['date']);
    $service_type = $conn->real_escape_string($_POST['service_type']);
    $visitors = intval($_POST['visitors_count']);

    $selected_members = isset($_POST['members']) ? $_POST['members'] : [];
    $attendees_count = count($selected_members);
    $member_ids_str = implode(",", $selected_members);

    // Check if duplicate for same date/service
    $check = $conn->query("SELECT id FROM attendance_records WHERE date = '$date' AND service_type = '$service_type'");
    if ($check->num_rows > 0) {
        $error_msg = "Attendance for this service on this date already exists.";
    } else {
        $sql = "INSERT INTO attendance_records (date, service_type, attendees_count, member_ids, visitors_count) 
                VALUES ('$date', '$service_type', $attendees_count, '$member_ids_str', $visitors)";

        if ($conn->query($sql)) {
            header("Location: attendance.php?status=saved");
            exit();
        } else {
            $error_msg = "Error saving attendance: " . $conn->error;
        }
    }
}

if (isset($_GET['status']) && $_GET['status'] == 'saved') {
    $success_msg = "Attendance record saved successfully!";
}

// Fetch Records History
$history = $conn->query("SELECT * FROM attendance_records ORDER BY date DESC LIMIT 10");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Attendance | Mission Hope Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            dark: '#052e16',
                            main: '#1b4d3e',
                            light: '#34d399',
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
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f2f5;
        }

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
        <?php include 'sidebar.php'; ?>

        <main class="flex-1 flex flex-col h-full bg-[#f0f2f5] overflow-y-auto relative">
            <div class="absolute top-0 left-0 w-full h-64 bg-[#022c22] z-0 rounded-b-[3rem]">
                <div class="absolute inset-0 opacity-20" style="background-image: url('../church%202.jpeg'); background-size: cover; background-position: center;"></div>
                <div class="absolute inset-0 bg-gradient-to-b from-transparent to-[#f0f2f5]/90"></div>
            </div>

            <div class="relative z-10 px-8 py-8 md:px-12">
                <div class="mb-10">
                    <h2 class="text-3xl font-serif font-bold text-white mb-1">Attendance Tracker</h2>
                    <p class="text-emerald-100 text-sm font-light tracking-wide">Monitor participation and service growth.</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Form Section -->
                    <div class="lg:col-span-2">
                        <?php if (!empty($success_msg)): ?>
                            <div class="bg-emerald-100 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-4 flex items-center gap-2">
                                <ion-icon name="checkmark-circle"></ion-icon> <?php echo $success_msg; ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($error_msg)): ?>
                            <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 flex items-center gap-2">
                                <ion-icon name="alert-circle"></ion-icon> <?php echo $error_msg; ?>
                            </div>
                        <?php endif; ?>

                        <div class="bg-white p-8 rounded-2xl shadow-soft border border-slate-100">
                            <form method="POST" action="">
                                <input type="hidden" name="submit_attendance" value="1">

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Service Date</label>
                                        <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>" class="w-full px-4 py-3 border border-slate-200 rounded-xl outline-none focus:border-brand-gold bg-slate-50" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Service Type</label>
                                        <select name="service_type" class="w-full px-4 py-3 border border-slate-200 rounded-xl outline-none focus:border-brand-gold bg-white" required>
                                            <option value="Sabbath Divine Service">Sabbath Divine Service</option>
                                            <option value="Wednesday Prayer">Wednesday Prayer Meeting</option>
                                            <option value="Friday Vespers">Friday Vespers</option>
                                            <option value="Special Event">Special Event</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-6">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Mark Members Present</label>
                                    <div class="border border-slate-200 rounded-xl p-4 max-h-60 overflow-y-auto bg-slate-50 grid grid-cols-1 md:grid-cols-2 gap-2">
                                        <?php
                                        $m_res = $conn->query("SELECT * FROM members WHERE group_name != 'Visitor' ORDER BY name ASC");
                                        if ($m_res->num_rows > 0) {
                                            while ($mem = $m_res->fetch_assoc()) {
                                                echo '<label class="flex items-center gap-3 p-2 bg-white rounded-lg border border-slate-100 hover:border-emerald-200 cursor-pointer transition-colors">';
                                                echo '<input type="checkbox" name="members[]" value="' . $mem['id'] . '" class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500 border-gray-300">';
                                                echo '<span class="text-sm text-slate-700 font-medium">' . htmlspecialchars($mem['name']) . '</span>';
                                                echo '</label>';
                                            }
                                        } else {
                                            echo '<p class="text-slate-400 text-sm italic col-span-2 text-center">No members found. Please add members in SMS section first.</p>';
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="mb-6">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Additional Visitors Count</label>
                                    <input type="number" name="visitors_count" min="0" value="0" class="w-full px-4 py-3 border border-slate-200 rounded-xl outline-none focus:border-brand-gold bg-slate-50" placeholder="e.g. 5">
                                    <p class="text-[10px] text-slate-400 mt-1">Enter total number of visitors present (if not added by name).</p>
                                </div>

                                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 rounded-xl transition-all shadow-lg shadow-emerald-600/30 flex items-center justify-center gap-2">
                                    <ion-icon name="checkmark-done-circle" class="text-xl"></ion-icon>
                                    <span>Save Attendance Record</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- History Section -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-2xl shadow-soft border border-slate-100 overflow-hidden">
                            <div class="p-5 border-b border-slate-100 bg-slate-50/50">
                                <h3 class="font-bold text-slate-800 text-lg">Recent Records</h3>
                            </div>
                            <div class="divide-y divide-slate-100">
                                <?php if ($history->num_rows > 0): ?>
                                    <?php while ($rec = $history->fetch_assoc()):
                                        $total = $rec['attendees_count'] + $rec['visitors_count'];
                                    ?>
                                        <div class="p-4 hover:bg-slate-50 transition-colors">
                                            <div class="flex justify-between items-start mb-1">
                                                <span class="font-bold text-slate-700 text-sm"><?php echo htmlspecialchars($rec['service_type']); ?></span>
                                                <span class="bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded text-[10px] font-bold"><?php echo $total; ?> Present</span>
                                            </div>
                                            <div class="flex justify-between text-xs text-slate-400">
                                                <span><?php echo date('M d, Y', strtotime($rec['date'])); ?></span>
                                                <span><?php echo $rec['attendees_count']; ?> Mem, <?php echo $rec['visitors_count']; ?> Vis</span>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <div class="p-6 text-center text-slate-400 italic text-sm">No attendance records yet.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>