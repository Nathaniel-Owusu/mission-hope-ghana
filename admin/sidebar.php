<div class="w-64 bg-brand-dark text-white fixed h-full z-50 flex flex-col pt-6 transition-all shadow-xl">
    <h4 class="text-center text-brand-gold text-2xl font-serif font-bold mb-8 tracking-wide">Mission Hope</h4>

    <nav class="flex-1 space-y-1">
        <a href="dashboard.php" class="flex items-center px-6 py-3 text-white hover:bg-white/10 border-l-4 border-transparent hover:border-brand-gold transition-all <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? '!border-brand-gold bg-white/10' : ''; ?>">
            <ion-icon name="grid-outline" class="mr-3 text-xl"></ion-icon>
            <span class="font-medium">Dashboard</span>
        </a>
        <a href="announcement.php" class="flex items-center px-6 py-3 text-gray-300 hover:bg-white/5 hover:text-white border-l-4 border-transparent hover:border-brand-gold transition-all <?php echo basename($_SERVER['PHP_SELF']) == 'announcement.php' ? '!border-brand-gold bg-white/5 text-white' : ''; ?>">
            <ion-icon name="megaphone-outline" class="mr-3 text-xl"></ion-icon>
            <span>Announcements</span>
        </a>
        <a href="leadership.php" class="flex items-center px-6 py-3 text-gray-300 hover:bg-white/5 hover:text-white border-l-4 border-transparent hover:border-brand-gold transition-all <?php echo basename($_SERVER['PHP_SELF']) == 'leadership.php' ? '!border-brand-gold bg-white/5 text-white' : ''; ?>">
            <ion-icon name="people-outline" class="mr-3 text-xl"></ion-icon>
            <span>Leadership</span>
        </a>
        <a href="departments.php" class="flex items-center px-6 py-3 text-gray-300 hover:bg-white/5 hover:text-white border-l-4 border-transparent hover:border-brand-gold transition-all <?php echo basename($_SERVER['PHP_SELF']) == 'departments.php' ? '!border-brand-gold bg-white/5 text-white' : ''; ?>">
            <ion-icon name="business-outline" class="mr-3 text-xl"></ion-icon>
            <span>Departments</span>
        </a>
        <a href="events.php" class="flex items-center px-6 py-3 text-gray-300 hover:bg-white/5 hover:text-white border-l-4 border-transparent hover:border-brand-gold transition-all <?php echo basename($_SERVER['PHP_SELF']) == 'events.php' ? '!border-brand-gold bg-white/5 text-white' : ''; ?>">
            <ion-icon name="calendar-outline" class="mr-3 text-xl"></ion-icon>
            <span>Events</span>
        </a>
        <a href="media.php" class="flex items-center px-6 py-3 text-gray-300 hover:bg-white/5 hover:text-white border-l-4 border-transparent hover:border-brand-gold transition-all <?php echo basename($_SERVER['PHP_SELF']) == 'media.php' ? '!border-brand-gold bg-white/5 text-white' : ''; ?>">
            <ion-icon name="images-outline" class="mr-3 text-xl"></ion-icon>
            <span>Media</span>
        </a>
        <a href="messages.php" class="flex items-center px-6 py-3 text-gray-300 hover:bg-white/5 hover:text-white border-l-4 border-transparent hover:border-brand-gold transition-all <?php echo basename($_SERVER['PHP_SELF']) == 'messages.php' ? '!border-brand-gold bg-white/5 text-white' : ''; ?>">
            <ion-icon name="mail-outline" class="mr-3 text-xl"></ion-icon>
            <span>Messages</span>
        </a>
    </nav>

    <div class="px-6 py-4 mt-auto mb-4 border-t border-white/10 pt-4">
        <a href="../index.php" target="_blank" class="flex items-center text-sm text-brand-gold hover:text-white mb-3 transition-colors">
            <ion-icon name="open-outline" class="mr-2 text-lg"></ion-icon>
            View Website
        </a>
        <a href="logout.php" class="flex items-center text-red-400 hover:text-red-300 transition-all font-semibold">
            <ion-icon name="log-out-outline" class="mr-3 text-xl"></ion-icon>
            <span>Logout</span>
        </a>
    </div>
</div>