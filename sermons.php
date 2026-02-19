<?php
include 'admin/db.php';
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sermons | Mission Hope SDA Church</title>

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
                        sans: ['Outfit', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    }
                }
            }
        }
    </script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="index.css">

    <!-- Icons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <style>
        .parallax-section {
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
</head>

<body class="bg-brand-cream text-gray-800 antialiased selection:bg-brand-gold selection:text-white">

    <!-- Navigation -->
    <nav id="navbar" class="fixed w-full z-50 transition-all duration-300 py-3 md:py-4">
        <div class="container mx-auto px-4 md:px-6 flex justify-between items-center">
            <a href="index.php" class="flex items-center gap-2 group">
                <img src="currentlogo.png" alt="Mission Hope Logo" class="h-8 md:h-10 w-auto drop-shadow-lg transition-transform group-hover:rotate-6">
                <div class="text-white drop-shadow-md">
                    <span class="block text-base md:text-xl font-serif font-bold leading-none tracking-wide">MISSION HOPE</span>
                    <span class="block text-[7px] md:text-[10px] uppercase tracking-[0.2em] opacity-90">Seventh-Day Adventist Church</span>
                </div>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="index.php" class="text-white/90 hover:text-brand-gold font-medium transition-colors text-sm uppercase tracking-widest relative after:content-[''] after:absolute after:-bottom-2 after:left-0 after:w-0 after:h-0.5 after:bg-brand-gold after:transition-all hover:after:w-full">Home</a>
                <a href="about.html" class="text-white/90 hover:text-brand-gold font-medium transition-colors text-sm uppercase tracking-widest relative after:content-[''] after:absolute after:-bottom-2 after:left-0 after:w-0 after:h-0.5 after:bg-brand-gold after:transition-all hover:after:w-full">About</a>
                <a href="ministries.php" class="text-white/90 hover:text-brand-gold font-medium transition-colors text-sm uppercase tracking-widest relative after:content-[''] after:absolute after:-bottom-2 after:left-0 after:w-0 after:h-0.5 after:bg-brand-gold after:transition-all hover:after:w-full">Ministries</a>
                <a href="sermons.php" class="text-brand-gold font-bold transition-colors text-sm uppercase tracking-widest relative after:content-[''] after:absolute after:-bottom-2 after:left-0 after:w-full after:h-0.5 after:bg-brand-gold after:transition-all">Sermons</a>
                <a href="events.php" class="text-white/90 hover:text-brand-gold font-medium transition-colors text-sm uppercase tracking-widest relative after:content-[''] after:absolute after:-bottom-2 after:left-0 after:w-0 after:h-0.5 after:bg-brand-gold after:transition-all hover:after:w-full">Events</a>
                <a href="leadership.php" class="text-white/90 hover:text-brand-gold font-medium transition-colors text-sm uppercase tracking-widest relative after:content-[''] after:absolute after:-bottom-2 after:left-0 after:w-0 after:h-0.5 after:bg-brand-gold after:transition-all hover:after:w-full">Leadership</a>
                <a href="gallery.php" class="text-white/90 hover:text-brand-gold font-medium transition-colors text-sm uppercase tracking-widest relative after:content-[''] after:absolute after:-bottom-2 after:left-0 after:w-0 after:h-0.5 after:bg-brand-gold after:transition-all hover:after:w-full">Gallery</a>
                <a href="giving.html" class="text-white/90 hover:text-brand-gold font-medium transition-colors text-sm uppercase tracking-widest relative after:content-[''] after:absolute after:-bottom-2 after:left-0 after:w-0 after:h-0.5 after:bg-brand-gold after:transition-all hover:after:w-full">Giving</a>
                <a href="contact.php" class="px-6 py-2 bg-brand-gold/90 hover:bg-brand-gold text-white rounded-full font-semibold transition-all shadow-lg hover:shadow-brand-gold/50 transform hover:-translate-y-0.5 backdrop-blur-sm">Contact Us</a>
            </div>

            <!-- Mobile Button -->
            <button id="mobile-menu-btn" class="md:hidden text-white text-3xl focus:outline-none transition-transform active:scale-95">
                <ion-icon name="menu-outline"></ion-icon>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden absolute top-full left-0 w-full bg-brand-dark/95 backdrop-blur-md shadow-xl border-t border-white/10 transition-all duration-300 origin-top transform">
            <div class="flex flex-col p-6 space-y-4 text-center">
                <a href="index.php" class="text-white hover:text-brand-gold font-serif font-medium text-base tracking-widest transition-colors">Home</a>
                <a href="about.html" class="text-white hover:text-brand-gold font-serif font-medium text-base tracking-widest transition-colors">About</a>
                <a href="ministries.php" class="text-white hover:text-brand-gold font-serif font-medium text-base tracking-widest transition-colors">Ministries</a>
                <a href="sermons.php" class="text-brand-gold font-serif font-medium text-base tracking-widest transition-colors">Sermons</a>
                <a href="events.php" class="text-white hover:text-brand-gold font-serif font-medium text-base tracking-widest transition-colors">Events</a>
                <a href="leadership.php" class="text-white hover:text-brand-gold font-serif font-medium text-base tracking-widest transition-colors">Leadership</a>
                <a href="gallery.php" class="text-white hover:text-brand-gold font-serif font-medium text-base tracking-widest transition-colors">Gallery</a>
                <a href="giving.html" class="text-white hover:text-brand-gold font-serif font-medium text-base tracking-widest transition-colors">Giving</a>
                <a href="contact.php" class="inline-block px-8 py-3 bg-brand-gold text-white rounded-full font-bold shadow-lg hover:bg-white hover:text-brand-gold transition-all mx-auto text-sm">Contact Us</a>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <header class="relative h-[50vh] min-h-[400px] flex items-center justify-center overflow-hidden parallax-section" style="background-image: url('IMG_1022.jpg');">
        <div class="absolute inset-0 bg-brand-dark/80 z-10"></div>
        <div class="relative z-20 container mx-auto px-6 text-center text-white mt-12">
            <span class="inline-block py-1 px-4 border border-white/30 rounded-full bg-white/10 backdrop-blur-md text-xs font-bold tracking-[0.2em] uppercase mb-4 animate-fade-up">Message</span>
            <h1 class="text-4xl md:text-6xl font-serif font-bold mb-4 animate-fade-up delay-100">Sermons</h1>
            <p class="text-lg md:text-xl text-gray-200 max-w-2xl mx-auto font-light leading-relaxed animate-fade-up delay-200">
                Listen to the life-changing messages from God's word.
            </p>
        </div>
    </header>

    <!-- Latest Message -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <?php
            // Fetch Latest Sermon
            $latest_sql = "SELECT * FROM sermons ORDER BY date_preached DESC LIMIT 1";
            $latest_res = $conn->query($latest_sql);

            if ($latest_res->num_rows > 0) {
                $latest = $latest_res->fetch_assoc();
                $latest_img = "IMG_1022.jpg"; // Default
                if ($latest['type'] == 'video' && !empty($latest['external_link'])) {
                    // Try to get YouTube thumbnail if possible, or just use default. 
                    // For now, simpler to just use default or if they uploaded an image type.
                    // If we want a thumbnail, we'd need a field for it or extract it.
                    // The inputs have 'file' upload but usually for the video itself if local, or image if Type=Image.
                }
            ?>
                <div class="flex flex-col lg:flex-row gap-12 items-center">
                    <div class="lg:w-2/3 w-full">
                        <div class="relative overflow-hidden rounded-2xl shadow-2xl aspect-video group cursor-pointer bg-black">
                            <?php if ($latest['type'] == 'video'): ?>
                                <div class="absolute inset-0 flex items-center justify-center bg-black/50">
                                    <img src="<?php echo $latest_img; ?>" class="w-full h-full object-cover opacity-60">
                                    <a href="<?php echo htmlspecialchars($latest['external_link']); ?>" target="_blank" class="w-20 h-20 bg-brand-gold rounded-full flex items-center justify-center text-white text-3xl pl-1 shadow-[0_0_30px_rgba(212,163,115,0.5)] hover:scale-110 transition-transform duration-300 absolute z-10">
                                        <ion-icon name="play"></ion-icon>
                                    </a>
                                </div>
                            <?php elseif ($latest['type'] == 'audio'): ?>
                                <div class="absolute inset-0 flex items-center justify-center bg-brand-dark">
                                    <ion-icon name="mic-outline" class="text-9xl text-white/20"></ion-icon>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <audio controls class="w-3/4">
                                            <source src="<?php echo $latest['file_path']; ?>" type="audio/mpeg">
                                            Your browser does not support the audio element.
                                        </audio>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="lg:w-1/3 w-full">
                        <span class="text-brand-gold font-bold tracking-widest uppercase text-xs mb-2 block">Latest Message</span>
                        <h2 class="text-3xl md:text-4xl font-serif font-bold text-gray-900 mb-4"><?php echo htmlspecialchars($latest['title']); ?></h2>
                        <p class="text-gray-500 text-sm mb-6 flex items-center gap-2">
                            <ion-icon name="calendar-outline"></ion-icon> <?php echo date('l, F j, Y', strtotime($latest['date_preached'])); ?>
                            <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                            <ion-icon name="person-outline"></ion-icon> <?php echo htmlspecialchars($latest['preacher']); ?>
                        </p>
                        <p class="text-gray-600 leading-relaxed mb-8">
                            <?php echo htmlspecialchars($latest['description']); ?>
                        </p>
                        <div class="flex gap-4">
                            <?php if ($latest['external_link']): ?>
                                <a href="<?php echo htmlspecialchars($latest['external_link']); ?>" target="_blank" class="px-6 py-3 bg-brand-dark text-white rounded-lg font-bold hover:bg-brand-light transition-all shadow-lg text-sm">Watch Now</a>
                            <?php endif; ?>
                            <?php if ($latest['file_path']): ?>
                                <a href="<?php echo htmlspecialchars($latest['file_path']); ?>" download class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-bold hover:border-brand-gold hover:text-brand-gold transition-all text-sm flex items-center gap-2"><ion-icon name="download-outline"></ion-icon> Download</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="text-center py-12">
                    <h3 class="text-2xl font-serif font-bold text-gray-500">No sermons uploaded yet.</h3>
                    <p class="text-gray-400">Check back later for our latest messages.</p>
                </div>
            <?php } ?>
        </div>
    </section>

    <!-- Sermon Archive -->
    <section class="py-20 bg-brand-cream border-t border-brand-cream/50">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl md:text-4xl font-serif font-bold text-gray-900 text-center mb-16">Recent Sermons</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                // Fetch other sermons (Skip first 1)
                $archive_sql = "SELECT * FROM sermons ORDER BY date_preached DESC LIMIT 6 OFFSET 1";
                $archive_res = $conn->query($archive_sql);

                if ($archive_res->num_rows > 0) {
                    while ($row = $archive_res->fetch_assoc()) {
                ?>
                        <!-- Sermon Card -->
                        <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 group">
                            <div class="relative h-56 cursor-pointer bg-black">
                                <!-- Placeholder Image -->
                                <img src="https://images.unsplash.com/photo-1438232992991-995b7058bbb3?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Sermon" class="w-full h-full object-cover opacity-80">

                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-white text-xl border border-white/50">
                                        <ion-icon name="play"></ion-icon>
                                    </div>
                                </div>
                                <?php if ($row['type']): ?>
                                    <div class="absolute top-2 right-2 px-2 py-1 bg-black/60 text-white text-[10px] uppercase font-bold rounded backdrop-blur-sm">
                                        <?php echo $row['type']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="p-6">
                                <div class="text-xs text-brand-gold font-bold uppercase tracking-wider mb-2"><?php echo date('M d, Y', strtotime($row['date_preached'])); ?></div>
                                <h3 class="text-xl font-bold font-serif text-gray-900 mb-2 group-hover:text-brand-DEFAULT transition-colors truncate"><?php echo htmlspecialchars($row['title']); ?></h3>
                                <p class="text-gray-600 text-sm mb-4"><?php echo htmlspecialchars($row['preacher']); ?></p>
                                <div class="flex gap-3 pt-4 border-t border-gray-100">
                                    <?php if ($row['external_link']): ?>
                                        <a href="<?php echo $row['external_link']; ?>" target="_blank" class="text-gray-500 hover:text-brand-gold transition-colors text-lg" title="Watch"><ion-icon name="videocam-outline"></ion-icon></a>
                                    <?php endif; ?>
                                    <?php if ($row['file_path']): ?>
                                        <a href="<?php echo $row['file_path']; ?>" download class="text-gray-500 hover:text-brand-gold transition-colors text-lg" title="Download"><ion-icon name="download-outline"></ion-icon></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    if ($latest_res->num_rows <= 0) {
                        // Already showed "No sermons" above, maybe don't show anything here or show generic message
                    } else {
                        echo '<div class="col-span-full text-center text-gray-500 italic">No other sermons in the archive.</div>';
                    }
                }
                ?>

            </div>

            <div class="mt-16 text-center">
                <a href="#" class="inline-block px-8 py-3 border border-brand-dark text-brand-dark font-bold rounded-full hover:bg-brand-dark hover:text-white transition-all">
                    View Archive
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white pt-16 pb-8 border-t border-gray-800">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                <!-- Branding -->
                <div>
                    <div class="flex items-center gap-2 mb-6">
                        <img src="currentlogo.png" alt="Logo" class="h-10 w-auto opacity-90">
                        <span class="text-xl font-serif font-bold">Mission Hope</span>
                    </div>
                    <p class="text-gray-400 leading-relaxed mb-6 text-sm">
                        Proclaiming the everlasting gospel in the context of the Three Angels' messages of Revelation 14:6-12.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center hover:bg-brand-gold hover:text-white transition-all">
                            <ion-icon name="logo-facebook"></ion-icon>
                        </a>
                        <a href="#" class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center hover:bg-brand-gold hover:text-white transition-all">
                            <ion-icon name="logo-youtube"></ion-icon>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-base font-serif font-bold mb-4 text-gray-100">Quick Links</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="index.php" class="text-gray-400 hover:text-brand-gold transition-colors">Home</a></li>
                        <li><a href="about.html" class="text-gray-400 hover:text-brand-gold transition-colors">About Us</a></li>
                        <li><a href="ministries.php" class="text-gray-400 hover:text-brand-gold transition-colors">Ministries</a></li>
                        <li><a href="leadership.php" class="text-gray-400 hover:text-brand-gold transition-colors">Leadership</a></li>
                        <li><a href="gallery.php" class="text-gray-400 hover:text-brand-gold transition-colors">Gallery</a></li>
                        <li><a href="events.php" class="text-gray-400 hover:text-brand-gold transition-colors">Events</a></li>
                        <li><a href="sermons.php" class="text-brand-gold hover:text-brand-gold transition-colors">Sermons</a></li>
                        <li><a href="giving.html" class="text-gray-400 hover:text-brand-gold transition-colors">Giving</a></li>
                        <li><a href="contact.php" class="text-gray-400 hover:text-brand-gold transition-colors">Contact</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h4 class="text-base font-serif font-bold mb-4 text-gray-100">Contact Us</h4>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start gap-3 text-gray-400">
                            <ion-icon name="location-outline" class="text-lg text-brand-gold mt-1"></ion-icon>
                            <span>Takofiano P.O. Box 162,<br>Techiman, Ghana</span>
                        </li>
                        <li class="flex items-center gap-3 text-gray-400">
                            <ion-icon name="call-outline" class="text-lg text-brand-gold"></ion-icon>
                            <span>+233 20 123 4567</span>
                        </li>
                        <li class="flex items-center gap-3 text-gray-400">
                            <ion-icon name="mail-outline" class="text-lg text-brand-gold"></ion-icon>
                            <span>info@missionhopesda.org</span>
                        </li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div>
                    <h4 class="text-base font-serif font-bold mb-4 text-gray-100">Newsletter</h4>
                    <form class="flex flex-col gap-2">
                        <input type="email" placeholder="Email Address" class="bg-white/5 border border-gray-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-brand-gold transition-colors">
                        <button type="submit" class="bg-brand-gold text-white font-bold py-2 rounded-lg hover:bg-white hover:text-brand-gold transition-all text-sm">Subscribe</button>
                    </form>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 text-center text-gray-500 text-sm">
                <p>&copy; 2026 Mission Hope SDA Church. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('bg-white/90', 'backdrop-blur-md', 'shadow-md', 'py-3');
                navbar.classList.remove('py-6');
                // Change text colors for light background
                navbar.querySelectorAll('a').forEach(link => {
                    if (!link.classList.contains('bg-brand-gold')) { // Don't change button text
                        link.classList.remove('text-white/90');
                        link.classList.add('text-gray-800');
                    }
                });
                // Handle Logo Text Color
                const logoText = navbar.querySelector('.drop-shadow-md');
                if (logoText) {
                    logoText.classList.remove('text-white');
                    logoText.classList.add('text-brand-dark');
                }

                // Mobile button color
                const mobileBtn = document.getElementById('mobile-menu-btn');
                if (mobileBtn) mobileBtn.classList.remove('text-white');
                if (mobileBtn) mobileBtn.classList.add('text-brand-dark');

            } else {
                navbar.classList.remove('bg-white/90', 'backdrop-blur-md', 'shadow-md', 'py-3');
                navbar.classList.add('py-6');
                // Revert text colors
                navbar.querySelectorAll('a').forEach(link => {
                    if (!link.classList.contains('bg-brand-gold')) {
                        link.classList.add('text-white/90');
                        link.classList.remove('text-gray-800');
                    }
                });

                const logoText = navbar.querySelector('.drop-shadow-md');
                if (logoText) {
                    logoText.classList.add('text-white');
                    logoText.classList.remove('text-brand-dark');
                }

                // Mobile button color
                const mobileBtn = document.getElementById('mobile-menu-btn');
                if (mobileBtn) mobileBtn.classList.add('text-white');
                if (mobileBtn) mobileBtn.classList.remove('text-brand-dark');
            }
        });

        // Mobile Menu Toggle
        const mobileBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        if (mobileBtn && mobileMenu) {
            mobileBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
                const icon = mobileBtn.querySelector('ion-icon');
                if (mobileMenu.classList.contains('hidden')) {
                    icon.setAttribute('name', 'menu-outline');
                } else {
                    icon.setAttribute('name', 'close-outline');
                }
            });
        }

        // Use Intersection Observer for fade animations
        const observerOptions = {
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-up');
                    entry.target.style.opacity = 1;
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);
    </script>
</body>

</html>