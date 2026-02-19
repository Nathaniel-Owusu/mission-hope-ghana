<?php
include 'db.php';
// include 'auth_session.php'; 

// Ensure tables exist
$conn->query("CREATE TABLE IF NOT EXISTS sms_history (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    message TEXT NOT NULL,
    recipients VARCHAR(255) NOT NULL,
    status VARCHAR(50) DEFAULT 'Sent',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$conn->query("CREATE TABLE IF NOT EXISTS members (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    group_name VARCHAR(100) DEFAULT 'General',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$success_msg = "";
$error_msg = "";

// Handle Delete Member
if (isset($_GET['delete_member'])) {
    $id = intval($_GET['delete_member']);
    $conn->query("DELETE FROM members WHERE id=$id");
    header("Location: sms.php"); // Refresh to clear query param
    exit();
}

// Handle Add Member
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_member'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $group_name = $conn->real_escape_string($_POST['group_name']); // Now allows custom input too

    // Check for duplicates
    $check = $conn->query("SELECT id FROM members WHERE phone = '$phone'");
    if ($check->num_rows > 0) {
        $error_msg = "This phone number is already in the contact list.";
    } elseif (!empty($name) && !empty($phone)) {
        $sql = "INSERT INTO members (name, phone, group_name) VALUES ('$name', '$phone', '$group_name')";
        if ($conn->query($sql)) {
            // Redirect to prevent resubmission
            header("Location: sms.php?status=added");
            exit();
        } else {
            $error_msg = "Error adding contact: " . $conn->error;
        }
    } else {
        $error_msg = "Name and Phone are required.";
    }
}

if (isset($_GET['status'])) {
    if ($_GET['status'] == 'added') $success_msg = "Contact added successfully.";
    if ($_GET['status'] == 'sent') $success_msg = "Broadcast sent successfully!";
}

// Handle Send SMS
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_sms'])) {
    $message = $conn->real_escape_string($_POST['message']);
    $group = $conn->real_escape_string($_POST['recipient_group']);

    // Prepare recipients array
    $recipients_array = [];

    if ($group === 'custom') {
        $custom_list = explode(',', $conn->real_escape_string($_POST['custom_numbers']));
        foreach ($custom_list as $num) {
            $clean_num = trim($num);
            if (!empty($clean_num)) {
                $recipients_array[] = $clean_num;
            }
        }
        $recipients_str = implode(", ", $recipients_array);
    } else {
        $recipients_str = $group; // Default label
        $result = $conn->query("SELECT phone FROM members WHERE group_name = '$group'");
        while ($row = $result->fetch_assoc()) {
            if (!empty($row['phone'])) {
                $recipients_array[] = trim($row['phone']);
            }
        }
        if (!empty($recipients_array)) {
            $recipients_str = count($recipients_array) . " contacts from " . $group;
        }
    }

    if (empty($message) || empty($recipients_array)) {
        $status = 'Failed';
        $error_msg = "Message or recipients cannot be empty.";
    } else {
        // ARKESEL SMS API INTEGRATION
        $api_key = 'adv_370c7214d97fa75decbc19cbbd34cf5e68c9733c56ca9524409f9bdb28cc90e5';
        $sender_id = 'MissionHope'; // Must be registered with Arkesel
        $url = "https://sms.arkesel.com/api/v2/sms/send";

        $data = [
            'sender' => $sender_id,
            'message' => $message,
            'recipients' => $recipients_array
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'api-key: ' . $api_key,
            'Content-Type: application/json'
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);

        // Decode response to check status
        $resp_json = json_decode($response, true);

        if ($http_code == 200 && isset($resp_json['status']) && $resp_json['status'] == 'success') {
            $status = 'Sent';
        } else {
            $status = 'Failed';
            // Extract error message
            $api_msg = isset($resp_json['message']) ? $resp_json['message'] : 'Unknown error';
            if ($curl_error) $api_msg .= " (Network: $curl_error)";
            $error_msg = "API Error: " . $api_msg;
        }
    }

    // Save to History
    $safe_recipients = $conn->real_escape_string($recipients_str);
    $safe_status = $conn->real_escape_string($status);

    $sql = "INSERT INTO sms_history (message, recipients, status) VALUES ('$message', '$safe_recipients', '$safe_status')";
    if ($conn->query($sql) === TRUE) {
        if ($status == 'Sent') {
            header("Location: sms.php?status=sent");
            exit();
        }
    } else {
        if (empty($error_msg)) $error_msg = "Database Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>SMS Blast | Mission Hope Admin</title>
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
                        <h2 class="text-3xl font-serif font-bold text-white mb-1">SMS Broadcasting</h2>
                        <p class="text-emerald-100 text-sm font-light tracking-wide">Send mass messages to your congregation.</p>
                    </div>

                    <div class="flex items-center gap-4">
                        <!-- Credits Badge -->
                        <div class="hidden md:flex items-center gap-2 bg-white/10 px-3 py-1.5 rounded-lg border border-white/20 text-white backdrop-blur-sm">
                            <ion-icon name="wallet-outline" class="text-brand-gold"></ion-icon>
                            <span class="text-xs font-bold">CREDITS: <span class="text-brand-gold">2,450</span></span>
                        </div>

                        <div class="glass-panel rounded-full px-4 py-2 flex items-center shadow-lg">
                            <ion-icon name="search-outline" class="text-slate-500 mr-2"></ion-icon>
                            <input type="text" id="searchInput" placeholder="Search history..." class="bg-transparent border-none outline-none text-sm w-32 md:w-48 text-slate-700 placeholder-slate-500">
                        </div>

                        <button class="bg-white p-2.5 rounded-full shadow-lg text-emerald-800 hover:scale-105 transition-transform relative">
                            <ion-icon name="notifications" class="text-xl"></ion-icon>
                            <span class="absolute top-0 right-0 h-3 w-3 bg-red-500 rounded-full border-2 border-white"></span>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <!-- Compose SMS -->
                    <div class="lg:col-span-2">
                        <?php if (isset($success_msg)): ?>
                            <div class="bg-emerald-100 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-4 flex items-center gap-2">
                                <ion-icon name="checkmark-circle"></ion-icon> <?php echo $success_msg; ?>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($error_msg)): ?>
                            <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 flex items-center gap-2">
                                <ion-icon name="alert-circle"></ion-icon> <?php echo $error_msg; ?>
                            </div>
                        <?php endif; ?>

                        <div class="bg-white p-8 rounded-2xl shadow-soft border border-slate-100">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                    <ion-icon name="chatbox-ellipses-outline" class="text-xl"></ion-icon>
                                </div>
                                <h2 class="text-lg font-bold text-slate-800 font-serif">Compose Broadcast</h2>
                            </div>

                            <!-- Contact Management Section -->
                            <div class="mt-8 bg-white p-8 rounded-2xl shadow-soft border border-slate-100">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                                        <ion-icon name="people-outline" class="text-xl"></ion-icon>
                                    </div>
                                    <h2 class="text-lg font-bold text-slate-800 font-serif">Manage Contacts</h2>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                                    <!-- Add Contact Form -->
                                    <div class="md:col-span-1 border-r border-slate-100 pr-0 md:pr-8">
                                        <h3 class="text-sm font-bold text-slate-700 mb-4">Add New Contact</h3>
                                        <form method="POST" action="" class="space-y-4">
                                            <input type="hidden" name="add_member" value="1">
                                            <div>
                                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Name</label>
                                                <input type="text" name="name" placeholder="John Doe" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:border-brand-gold outline-none" required>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Phone</label>
                                                <input type="text" name="phone" placeholder="0501234567" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:border-brand-gold outline-none" required>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Category</label>
                                                <input type="text" name="group_name" placeholder="Type new or select..." list="category_list" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:border-brand-gold outline-none bg-white" required>
                                                <datalist id="category_list">
                                                    <option value="Member">Church Member</option>
                                                    <option value="Visitor">Visitor</option>
                                                    <?php
                                                    $cats = $conn->query("SELECT DISTINCT group_name FROM members WHERE group_name NOT IN ('Member', 'Visitor') ORDER BY group_name");
                                                    while ($c = $cats->fetch_assoc()) {
                                                        echo '<option value="' . htmlspecialchars($c['group_name']) . '"></option>';
                                                    }
                                                    ?>
                                                </datalist>
                                                <p class="text-[10px] text-slate-400 mt-1">Select from list or type a new category to create it.</p>
                                            </div>
                                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg text-sm transition-colors">Add Contact</button>
                                        </form>
                                    </div>

                                    <!-- Contact List -->
                                    <div class="md:col-span-2">
                                        <div class="flex justify-between items-center mb-4">
                                            <h3 class="text-sm font-bold text-slate-700">Contact List</h3>
                                            <input type="text" id="contactSearch" placeholder="Search contacts..." class="bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs outline-none focus:border-blue-400 w-48">
                                        </div>

                                        <div class="overflow-x-auto border border-slate-100 rounded-lg max-h-[400px]">
                                            <table class="w-full text-sm text-left text-slate-600">
                                                <thead class="text-xs text-slate-500 uppercase bg-slate-50 sticky top-0">
                                                    <tr>
                                                        <th class="px-4 py-3">Name</th>
                                                        <th class="px-4 py-3">Phone</th>
                                                        <th class="px-4 py-3">Category</th>
                                                        <th class="px-4 py-3">Date Added</th>
                                                        <th class="px-4 py-3 text-right">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-slate-100" id="contactTableBody">
                                                    <?php
                                                    $members = $conn->query("SELECT * FROM members ORDER BY created_at DESC");
                                                    if ($members->num_rows > 0) {
                                                        while ($m = $members->fetch_assoc()) {
                                                            $badgeColor = ($m['group_name'] === 'Visitor') ? 'bg-amber-50 text-amber-600' : 'bg-blue-50 text-blue-600';
                                                            echo '<tr class="hover:bg-slate-50 transition-colors group">';
                                                            echo '<td class="px-4 py-3 font-medium text-slate-800">' . htmlspecialchars($m['name']) . '</td>';
                                                            echo '<td class="px-4 py-3 font-mono text-xs">' . htmlspecialchars($m['phone']) . '</td>';
                                                            echo '<td class="px-4 py-3"><span class="' . $badgeColor . ' px-2 py-0.5 rounded text-[10px] font-bold uppercase">' . htmlspecialchars($m['group_name']) . '</span></td>';
                                                            echo '<td class="px-4 py-3 text-xs text-slate-400">' . date('M d, Y', strtotime($m['created_at'])) . '</td>';
                                                            echo '<td class="px-4 py-3 text-right">
                                                                <a href="sms.php?delete_member=' . $m['id'] . '" onclick="return confirm(\'Delete this contact?\')" class="text-red-400 hover:text-red-600 transition-colors"><ion-icon name="trash-outline"></ion-icon></a>
                                                              </td>';
                                                            echo '</tr>';
                                                        }
                                                    } else {
                                                        echo '<tr><td colspan="5" class="px-4 py-8 text-center text-slate-400 italic">No contacts added yet.</td></tr>';
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form method="POST" action="" class="space-y-6">
                            <input type="hidden" name="send_sms" value="1">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Sender ID</label>
                                    <input type="text" value="MissionHope" readonly class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-100 text-slate-500 cursor-not-allowed font-bold tracking-wide outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Recipients Group</label>
                                    <div class="relative">
                                        <select name="recipient_group" id="recipient_group" onchange="toggleCustomNumbers()" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-brand-gold focus:ring-4 focus:ring-brand-gold/10 outline-none transition-all bg-white appearance-none text-sm font-medium">
                                            <option value="custom">Custom Numbers</option>
                                            <?php
                                            $groups_res = $conn->query("SELECT DISTINCT group_name FROM members ORDER BY group_name");
                                            if ($groups_res->num_rows > 0) {
                                                while ($g = $groups_res->fetch_assoc()) {
                                                    echo '<option value="' . htmlspecialchars($g['group_name']) . '">' . htmlspecialchars($g['group_name']) . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                        <ion-icon name="chevron-down-outline" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></ion-icon>
                                    </div>
                                </div>
                            </div>

                            <div id="custom-numbers-field" class="hidden">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Phone Numbers (Comma separated)</label>
                                <input type="text" name="custom_numbers" placeholder="e.g. 0501234567, 0244123456" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-brand-gold focus:ring-4 focus:ring-brand-gold/10 outline-none transition-all bg-slate-50 focus:bg-white text-sm">
                            </div>

                            <div>
                                <div class="flex justify-between mb-2">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest">Message</label>
                                    <span class="text-xs text-slate-400" id="char-count">0 / 160 characters (1 SMS)</span>
                                </div>
                                <textarea name="message" id="sms-message" rows="5" placeholder="Type your message here..." class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-brand-gold focus:ring-4 focus:ring-brand-gold/10 outline-none transition-all bg-slate-50 focus:bg-white resize-none font-mono text-sm" required></textarea>
                            </div>

                            <div class="p-4 bg-yellow-50 rounded-xl border border-yellow-100 flex items-start gap-3">
                                <ion-icon name="information-circle" class="text-yellow-500 text-xl flex-shrink-0 mt-0.5"></ion-icon>
                                <p class="text-xs text-yellow-700 leading-relaxed">
                                    <strong>Note:</strong> Messages over 160 characters will be charged as 2 SMS credits. Please keep your broadcast concise for better readability and cost efficiency.
                                </p>
                            </div>

                            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 rounded-xl transition-all shadow-lg shadow-emerald-600/30 hover:shadow-xl hover:-translate-y-1 flex items-center justify-center gap-2">
                                <ion-icon name="paper-plane" class="text-lg"></ion-icon>
                                <span>Send Broadcast</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- History / Stats -->
                <div class="lg:col-span-1 space-y-6">

                    <!-- Quick Top Up (Mock) -->
                    <div class="bg-gradient-to-br from-[#022c22] to-emerald-800 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-yellow-400 rounded-full mix-blend-overlay blur-3xl opacity-20 -mr-10 -mt-10"></div>
                        <h3 class="font-serif font-bold text-lg mb-1">SMS Balance</h3>
                        <h2 class="text-4xl font-bold mb-4 font-mono">2,450</h2>
                        <button class="w-full py-2.5 bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/20 rounded-xl text-sm font-semibold transition-all">
                            Top Up Credits
                        </button>
                    </div>

                    <!-- Recent History -->
                    <div class="bg-white rounded-2xl shadow-soft border border-slate-100 overflow-hidden text-sm">
                        <div class="p-5 border-b border-slate-100 bg-slate-50/50">
                            <h6 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Recent Broadcasts</h6>
                        </div>
                        <div class="divide-y divide-slate-100 max-h-[400px] overflow-y-auto" id="historyList">
                            <?php
                            $history_res = $conn->query("SELECT * FROM sms_history ORDER BY created_at DESC LIMIT 5");
                            if ($history_res->num_rows > 0) {
                                while ($item = $history_res->fetch_assoc()) {
                                    $statusClass = $item['status'] == 'Sent' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600';
                            ?>
                                    <div class="history-item p-4 hover:bg-slate-50 transition-colors">
                                        <div class="flex justify-between items-start mb-1">
                                            <span class="font-bold text-slate-700 truncate w-32 item-message"><?php echo htmlspecialchars(substr($item['message'], 0, 20)) . '...'; ?></span>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase item-status <?php echo $statusClass; ?>"><?php echo htmlspecialchars($item['status']); ?></span>
                                        </div>
                                        <p class="text-slate-500 text-xs mb-2 line-clamp-2"><?php echo htmlspecialchars($item['message']); ?></p>
                                        <div class="flex justify-between text-[10px] text-slate-400">
                                            <span>To: <?php echo htmlspecialchars(substr($item['recipients'], 0, 15)); ?></span>
                                            <span><?php echo date('M d, H:i', strtotime($item['created_at'])); ?></span>
                                        </div>
                                    </div>
                            <?php
                                }
                            } else {
                                echo '<div class="p-4 text-center text-slate-400 italic">No history yet.</div>';
                            }
                            ?>
                        </div>
                        <div class="p-3 bg-slate-50 text-center border-t border-slate-100">
                            <button class="text-emerald-600 text-xs font-bold hover:text-emerald-700 transition-colors">View All History</button>
                        </div>
                    </div>

                </div>

            </div>
    </div>
    </main>
    </div>

    <script>
        function toggleCustomNumbers() {
            const select = document.getElementById('recipient_group');
            const customField = document.getElementById('custom-numbers-field');
            if (select.value === 'custom') {
                customField.classList.remove('hidden');
            } else {
                customField.classList.add('hidden');
            }
        }

        // Simple script for character count
        const textarea = document.getElementById('sms-message');
        const countDisplay = document.getElementById('char-count');

        if (textarea) {
            textarea.addEventListener('input', function() {
                const current = this.value.length;
                const credits = Math.ceil(current / 160) || 1;
                countDisplay.innerText = `${current} / 160 characters (${credits} SMS)`;

                if (current > 160) {
                    countDisplay.classList.add('text-orange-500');
                } else {
                    countDisplay.classList.remove('text-orange-500');
                }
            });
        }

        // Search Filter for History
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let items = document.querySelectorAll('#historyList .history-item');

            items.forEach(function(item) {
                let message = item.querySelector('.item-message').innerText.toLowerCase();
                let status = item.querySelector('.item-status').innerText.toLowerCase();
                if (message.includes(filter) || status.includes(filter)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Search Filter for Contacts
        const contactSearch = document.getElementById('contactSearch');
        if (contactSearch) {
            contactSearch.addEventListener('keyup', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#contactTableBody tr');

                rows.forEach(function(row) {
                    if (row.innerText.toLowerCase().includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    </script>
</body>

</html>