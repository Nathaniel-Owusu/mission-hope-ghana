<?php
include 'db.php';
// include 'auth_session.php'; 

// Ensure settings table exists
$conn->query("CREATE TABLE IF NOT EXISTS settings (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT
)");

// Insert defaults if empty
$defaults = [
    'church_name' => 'Mission Hope SDA Church',
    'contact_email' => 'info@missionhope.org',
    'address' => '123 Faith Street, Accra, Ghana',
    'facebook' => 'facebook.com/missionhope',
    'youtube' => 'youtube.com/missionhope',
    'instagram' => 'instagram.com/missionhope'
];

foreach ($defaults as $key => $value) {
    $check = $conn->query("SELECT * FROM settings WHERE setting_key='$key'");
    if ($check->num_rows == 0) {
        $conn->query("INSERT INTO settings (setting_key, setting_value) VALUES ('$key', '$value')");
    }
}

// Handle Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_general'])) {
        $updates = [
            'church_name' => $_POST['church_name'],
            'contact_email' => $_POST['contact_email'],
            'address' => $_POST['address']
        ];
        foreach ($updates as $key => $val) {
            $val = $conn->real_escape_string($val);
            $conn->query("UPDATE settings SET setting_value='$val' WHERE setting_key='$key'");
        }
        $msg = "General settings updated.";
    } elseif (isset($_POST['update_social'])) {
        $updates = [
            'facebook' => $_POST['facebook'],
            'youtube' => $_POST['youtube'],
            'instagram' => $_POST['instagram']
        ];
        foreach ($updates as $key => $val) {
            $val = $conn->real_escape_string($val);
            $conn->query("UPDATE settings SET setting_value='$val' WHERE setting_key='$key'");
        }
        $msg = "Social media links updated.";
    } elseif (isset($_POST['change_password'])) {
        // Mock password change logic
        // In real app: verify old password, hash new password, update users table
        $msg = "Password updated successfully (Mock).";
    }
}

// Fetch all settings
$settings = [];
$res = $conn->query("SELECT * FROM settings");
while ($row = $res->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Settings | Mission Hope Admin</title>
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

        html {
            scroll-behavior: smooth;
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
                        <h2 class="text-3xl font-serif font-bold text-white mb-1">Settings</h2>
                        <p class="text-emerald-100 text-sm font-light tracking-wide">Manage global application preferences.</p>
                    </div>

                    <div class="glass-panel rounded-full px-4 py-2 flex items-center shadow-lg">
                        <ion-icon name="search-outline" class="text-slate-500 mr-2"></ion-icon>
                        <input type="text" id="searchInput" placeholder="Search settings..." class="bg-transparent border-none outline-none text-sm w-32 md:w-48 text-slate-700 placeholder-slate-500">
                    </div>

                    <button class="bg-white p-2.5 rounded-full shadow-lg text-emerald-800 hover:scale-105 transition-transform relative">
                        <ion-icon name="notifications" class="text-xl"></ion-icon>
                        <span class="absolute top-0 right-0 h-3 w-3 bg-red-500 rounded-full border-2 border-white"></span>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Left Column (Nav/Profile) -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Profile Card -->
                    <div class="bg-white rounded-2xl shadow-soft border border-slate-100 p-8 text-center relative overflow-hidden group">
                        <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-r from-emerald-800 to-[#022c22]"></div>
                        <div class="relative w-24 h-24 mx-auto bg-white rounded-full p-1 shadow-lg mb-4 group-hover:scale-105 transition-transform">
                            <img src="https://ui-avatars.com/api/?name=Admin+User&background=022c22&color=fff" alt="Admin" class="w-full h-full rounded-full object-cover">
                        </div>
                        <h3 class="font-bold text-lg text-slate-800">Admin User</h3>
                        <p class="text-sm text-slate-500 mb-6 font-medium">admin@missionhope.org</p>
                        <button class="text-brand-gold hover:text-emerald-700 text-xs font-bold uppercase tracking-widest transition-colors border border-slate-200 px-4 py-2 rounded-lg hover:bg-slate-50">Edit Profile</button>
                    </div>

                    <div class="bg-white rounded-2xl shadow-soft border border-slate-100 overflow-hidden sticky top-8">
                        <nav class="flex flex-col p-3 space-y-1">
                            <a href="#general" class="flex items-center px-4 py-3 rounded-xl bg-emerald-50 text-emerald-700 font-bold transition-colors">
                                <ion-icon name="options-outline" class="mr-3 text-lg"></ion-icon> General
                            </a>
                            <a href="#social" class="flex items-center px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 transition-colors font-medium">
                                <ion-icon name="share-social-outline" class="mr-3 text-lg"></ion-icon> Social Media
                            </a>
                            <a href="#security" class="flex items-center px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 transition-colors font-medium">
                                <ion-icon name="lock-closed-outline" class="mr-3 text-lg"></ion-icon> Security
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- Right Column (Forms) -->
                <div class="lg:col-span-2 space-y-8" id="settingsContainer">

                    <?php if (isset($msg)): ?>
                        <div class="bg-emerald-100 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-xl flex items-center gap-3 shadow-sm animate-fade-in">
                            <ion-icon name="checkmark-circle" class="text-xl"></ion-icon>
                            <span class="font-bold"><?php echo $msg; ?></span>
                        </div>
                    <?php endif; ?>

                    <!-- General Settings -->
                    <div id="general" class="setting-card bg-white rounded-2xl shadow-soft border border-slate-100 p-8">
                        <h3 class="font-bold text-slate-800 font-serif text-lg mb-6 border-b border-slate-100 pb-4">General Information</h3>
                        <form method="POST" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Church Name</label>
                                    <input type="text" name="church_name" value="<?php echo htmlspecialchars($settings['church_name'] ?? ''); ?>" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-brand-gold focus:ring-4 focus:ring-brand-gold/10 outline-none transition-all bg-slate-50 focus:bg-white text-sm font-medium">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Contact Email</label>
                                    <input type="email" name="contact_email" value="<?php echo htmlspecialchars($settings['contact_email'] ?? ''); ?>" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-brand-gold focus:ring-4 focus:ring-brand-gold/10 outline-none transition-all bg-slate-50 focus:bg-white text-sm font-medium">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Address</label>
                                <input type="text" name="address" value="<?php echo htmlspecialchars($settings['address'] ?? ''); ?>" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-brand-gold focus:ring-4 focus:ring-brand-gold/10 outline-none transition-all bg-slate-50 focus:bg-white text-sm font-medium">
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" name="update_general" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-8 rounded-xl transition-all shadow-lg shadow-emerald-600/20 hover:-translate-y-1">Save Changes</button>
                            </div>
                        </form>
                    </div>

                    <!-- Social Media -->
                    <div id="social" class="setting-card bg-white rounded-2xl shadow-soft border border-slate-100 p-8">
                        <h3 class="font-bold text-slate-800 font-serif text-lg mb-6 border-b border-slate-100 pb-4">Social Media Links</h3>
                        <form method="POST" class="space-y-6">
                            <div class="relative group">
                                <ion-icon name="logo-facebook" class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-600 text-xl group-hover:scale-110 transition-transform"></ion-icon>
                                <input type="text" name="facebook" value="<?php echo htmlspecialchars($settings['facebook'] ?? ''); ?>" placeholder="Facebook URL" class="w-full pl-12 pr-4 py-3 border border-slate-200 rounded-xl focus:border-brand-gold focus:ring-4 focus:ring-brand-gold/10 outline-none transition-all bg-slate-50 focus:bg-white text-sm font-medium">
                            </div>
                            <div class="relative group">
                                <ion-icon name="logo-youtube" class="absolute left-4 top-1/2 -translate-y-1/2 text-red-600 text-xl group-hover:scale-110 transition-transform"></ion-icon>
                                <input type="text" name="youtube" value="<?php echo htmlspecialchars($settings['youtube'] ?? ''); ?>" placeholder="YouTube URL" class="w-full pl-12 pr-4 py-3 border border-slate-200 rounded-xl focus:border-brand-gold focus:ring-4 focus:ring-brand-gold/10 outline-none transition-all bg-slate-50 focus:bg-white text-sm font-medium">
                            </div>
                            <div class="relative group">
                                <ion-icon name="logo-instagram" class="absolute left-4 top-1/2 -translate-y-1/2 text-pink-600 text-xl group-hover:scale-110 transition-transform"></ion-icon>
                                <input type="text" name="instagram" value="<?php echo htmlspecialchars($settings['instagram'] ?? ''); ?>" placeholder="Instagram URL" class="w-full pl-12 pr-4 py-3 border border-slate-200 rounded-xl focus:border-brand-gold focus:ring-4 focus:ring-brand-gold/10 outline-none transition-all bg-slate-50 focus:bg-white text-sm font-medium">
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" name="update_social" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-8 rounded-xl transition-all shadow-lg shadow-emerald-600/20 hover:-translate-y-1">Update Links</button>
                            </div>
                        </form>
                    </div>

                    <!-- Security -->
                    <div id="security" class="setting-card bg-white rounded-2xl shadow-soft border border-slate-100 p-8">
                        <h3 class="font-bold text-slate-800 font-serif text-lg mb-6 border-b border-slate-100 pb-4">Security</h3>
                        <form method="POST" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Current Password</label>
                                    <input type="password" name="old_password" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-brand-gold focus:ring-4 focus:ring-brand-gold/10 outline-none transition-all bg-slate-50 focus:bg-white text-sm font-medium">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">New Password</label>
                                    <input type="password" name="new_password" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-brand-gold focus:ring-4 focus:ring-brand-gold/10 outline-none transition-all bg-slate-50 focus:bg-white text-sm font-medium">
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" name="change_password" class="bg-slate-800 hover:bg-black text-white font-bold py-3 px-8 rounded-xl transition-all shadow-lg hover:shadow-xl hover:-translate-y-1">Change Password</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
    </div>
    </main>
    </div>
</body>

<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let cards = document.querySelectorAll('#settingsContainer .setting-card');

        cards.forEach(function(card) {
            let text = card.innerText.toLowerCase();
            if (text.includes(filter)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });
</script>
</body>

</html>