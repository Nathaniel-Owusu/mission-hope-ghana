<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!-- Mobile Toggle Button (Floating) -->
<button onclick="toggleSidebar()" class="md:hidden fixed bottom-6 right-6 z-[60] bg-amber-500 text-white p-4 rounded-full shadow-2xl hover:scale-110 transition-transform">
    <ion-icon name="menu-outline" class="text-2xl"></ion-icon>
</button>

<!-- Overlay for Mobile -->
<div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/50 z-40 hidden transition-opacity opacity-0 md:hidden"></div>

<aside id="sidebar" class="w-72 bg-[#022c22] text-white flex flex-col shadow-2xl z-50 h-[100dvh] fixed md:relative transition-transform duration-300 -translate-x-full md:translate-x-0">
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#34d399 1px, transparent 1px); background-size: 20px 20px;"></div>

    <!-- Mobile Close Button -->
    <button onclick="toggleSidebar()" class="md:hidden absolute top-4 right-4 text-white/50 hover:text-white">
        <ion-icon name="close-outline" class="text-2xl"></ion-icon>
    </button>

    <div class="p-8 pb-4 relative z-10">
        <div class="flex items-center gap-3">
            <img src="../currentlogo.png" alt="Logo" class="h-10 w-auto drop-shadow-[0_0_10px_rgba(255,255,255,0.3)]">
            <div>
                <h1 class="font-serif text-xl font-bold tracking-wide">Mission Hope</h1>
                <p class="text-[10px] text-emerald-400 uppercase tracking-[0.2em] font-bold">Admin Portal</p>
            </div>
        </div>
    </div>

    <div class="px-4 mb-2 relative z-10">
        <div class="h-px bg-gradient-to-r from-transparent via-white/10 to-transparent"></div>
    </div>

    <nav class="flex-1 overflow-y-auto sidebar-scroll px-4 space-y-1 py-4 relative z-10">
        <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Main Menu</p>

        <a href="dashboard.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active bg-white/10 text-white' : ''; ?> flex items-center px-4 py-3 rounded-lg text-slate-300 transition-all group">
            <ion-icon name="grid-outline" class="text-xl mr-3 group-hover:text-amber-400 <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'text-amber-400' : ''; ?>"></ion-icon>
            <span class="font-medium text-sm">Dashboard</span>
        </a>

        <a href="announcement.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'announcement.php' ? 'active bg-white/10 text-white' : ''; ?> flex items-center px-4 py-3 rounded-lg text-slate-300 transition-all group">
            <ion-icon name="megaphone-outline" class="text-xl mr-3 group-hover:text-amber-400 <?php echo basename($_SERVER['PHP_SELF']) == 'announcement.php' ? 'text-amber-400' : ''; ?>"></ion-icon>
            <span class="font-medium text-sm">Announcements</span>
        </a>

        <a href="leadership.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'leadership.php' ? 'active bg-white/10 text-white' : ''; ?> flex items-center px-4 py-3 rounded-lg text-slate-300 transition-all group">
            <ion-icon name="people-outline" class="text-xl mr-3 group-hover:text-amber-400 <?php echo basename($_SERVER['PHP_SELF']) == 'leadership.php' ? 'text-amber-400' : ''; ?>"></ion-icon>
            <span class="font-medium text-sm">Leadership</span>
        </a>

        <a href="departments.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'departments.php' ? 'active bg-white/10 text-white' : ''; ?> flex items-center px-4 py-3 rounded-lg text-slate-300 transition-all group">
            <ion-icon name="briefcase-outline" class="text-xl mr-3 group-hover:text-amber-400 <?php echo basename($_SERVER['PHP_SELF']) == 'departments.php' ? 'text-amber-400' : ''; ?>"></ion-icon>
            <span class="font-medium text-sm">Departments</span>
        </a>

        <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-6 mb-2">Management</p>

        <a href="events.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'events.php' ? 'active bg-white/10 text-white' : ''; ?> flex items-center px-4 py-3 rounded-lg text-slate-300 transition-all group">
            <ion-icon name="calendar-outline" class="text-xl mr-3 group-hover:text-amber-400 <?php echo basename($_SERVER['PHP_SELF']) == 'events.php' ? 'text-amber-400' : ''; ?>"></ion-icon>
            <span class="font-medium text-sm">Events</span>
        </a>

        <a href="media.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'media.php' ? 'active bg-white/10 text-white' : ''; ?> flex items-center px-4 py-3 rounded-lg text-slate-300 transition-all group">
            <ion-icon name="images-outline" class="text-xl mr-3 group-hover:text-amber-400 <?php echo basename($_SERVER['PHP_SELF']) == 'media.php' ? 'text-amber-400' : ''; ?>"></ion-icon>
            <span class="font-medium text-sm">Media Library</span>
        </a>

        <a href="sermons.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'sermons.php' ? 'active bg-white/10 text-white' : ''; ?> flex items-center px-4 py-3 rounded-lg text-slate-300 transition-all group">
            <ion-icon name="videocam-outline" class="text-xl mr-3 group-hover:text-amber-400 <?php echo basename($_SERVER['PHP_SELF']) == 'sermons.php' ? 'text-amber-400' : ''; ?>"></ion-icon>
            <span class="font-medium text-sm">Sermons</span>
        </a>

        <a href="messages.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'messages.php' ? 'active bg-white/10 text-white' : ''; ?> flex items-center px-4 py-3 rounded-lg text-slate-300 transition-all group">
            <div class="relative mr-3">
                <ion-icon name="mail-outline" class="text-xl group-hover:text-amber-400 <?php echo basename($_SERVER['PHP_SELF']) == 'messages.php' ? 'text-amber-400' : ''; ?>"></ion-icon>
            </div>
            <span class="font-medium text-sm">Inbox</span>
        </a>

        <a href="sms.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'sms.php' ? 'active bg-white/10 text-white' : ''; ?> flex items-center px-4 py-3 rounded-lg text-slate-300 transition-all group">
            <ion-icon name="chatbubbles-outline" class="text-xl mr-3 group-hover:text-amber-400 <?php echo basename($_SERVER['PHP_SELF']) == 'sms.php' ? 'text-amber-400' : ''; ?>"></ion-icon>
            <span class="font-medium text-sm">SMS Blast</span>
        </a>

        <a href="attendance.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'attendance.php' ? 'active bg-white/10 text-white' : ''; ?> flex items-center px-4 py-3 rounded-lg text-slate-300 transition-all group">
            <ion-icon name="stats-chart-outline" class="text-xl mr-3 group-hover:text-amber-400 <?php echo basename($_SERVER['PHP_SELF']) == 'attendance.php' ? 'text-amber-400' : ''; ?>"></ion-icon>
            <span class="font-medium text-sm">Attendance</span>
        </a>

        <a href="settings.php" class="sidebar-link <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active bg-white/10 text-white' : ''; ?> flex items-center px-4 py-3 rounded-lg text-slate-300 transition-all group">
            <ion-icon name="settings-outline" class="text-xl mr-3 group-hover:text-amber-400 <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'text-amber-400' : ''; ?>"></ion-icon>
            <span class="font-medium text-sm">Settings</span>
        </a>
    </nav>

    <div class="p-4 relative z-10 bg-[#02241c]">
        <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/5">
            <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-amber-400 to-orange-500 p-[2px]">
                <img src="https://ui-avatars.com/api/?name=Admin+User&background=022c22&color=fff" class="rounded-full h-full w-full border-2 border-[#022c22]" alt="Admin">
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-white truncate">Admin User</p>
                <p class="text-xs text-emerald-400 truncate">Super Admin</p>
            </div>
            <a href="logout.php" class="text-slate-400 hover:text-white" onclick="return confirm('Logout?');"><ion-icon name="log-out-outline" class="text-xl"></ion-icon></a>
        </div>
    </div>
</aside>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        if (sidebar.classList.contains('-translate-x-full')) {
            // Open
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            setTimeout(() => overlay.classList.remove('opacity-0'), 10);
        } else {
            // Close
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('opacity-0');
            setTimeout(() => overlay.classList.add('hidden'), 300);
        }
    }
</script>