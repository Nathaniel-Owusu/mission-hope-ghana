<?php
include 'admin/db.php';
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media Gallery | Mission Hope SDA Church</title>
    
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
    <nav id="navbar" class="fixed w-full z-50 transition-all duration-300 py-4 bg-white/90 backdrop-blur-md shadow-md">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <a href="index.php" class="flex items-center gap-2 group">
                <img src="sdalogo.png" alt="Mission Hope Logo" class="h-10 w-auto drop-shadow-lg transition-transform group-hover:rotate-6">
                <div class="hidden md:block text-brand-dark drop-shadow-md">
                    <span class="block text-xl font-serif font-bold leading-none tracking-wide">MISSION HOPE</span>
                    <span class="block text-[10px] uppercase tracking-[0.2em] opacity-90">Seventh-Day Adventist Church</span>
                </div>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="index.php" class="text-gray-800 hover:text-brand-gold font-medium transition-colors text-sm uppercase tracking-widest relative after:content-[''] after:absolute after:-bottom-2 after:left-0 after:w-0 after:h-0.5 after:bg-brand-gold after:transition-all hover:after:w-full">Home</a>
                <a href="about.html" class="text-gray-800 hover:text-brand-gold font-medium transition-colors text-sm uppercase tracking-widest relative after:content-[''] after:absolute after:-bottom-2 after:left-0 after:w-0 after:h-0.5 after:bg-brand-gold after:transition-all hover:after:w-full">About</a>
                <a href="ministries.php" class="text-gray-800 hover:text-brand-gold font-medium transition-colors text-sm uppercase tracking-widest relative after:content-[''] after:absolute after:-bottom-2 after:left-0 after:w-0 after:h-0.5 after:bg-brand-gold after:transition-all hover:after:w-full">Ministries</a>
                <a href="leadership.php" class="text-gray-800 hover:text-brand-gold font-medium transition-colors text-sm uppercase tracking-widest relative after:content-[''] after:absolute after:-bottom-2 after:left-0 after:w-0 after:h-0.5 after:bg-brand-gold after:transition-all hover:after:w-full">Leadership</a>
                <a href="gallery.php" class="text-brand-gold font-bold transition-colors text-sm uppercase tracking-widest relative after:content-[''] after:absolute after:-bottom-2 after:left-0 after:w-full after:h-0.5 after:bg-brand-gold after:transition-all">Gallery</a>
                <a href="contact.php" class="px-6 py-2 bg-brand-gold/90 hover:bg-brand-gold text-white rounded-full font-semibold transition-all shadow-lg hover:shadow-brand-gold/50 transform hover:-translate-y-0.5 backdrop-blur-sm shadow-brand-gold/50">Contact Us</a>
            </div>

            <!-- Mobile Button -->
            <button class="md:hidden text-brand-dark text-3xl focus:outline-none">
                <ion-icon name="menu-outline"></ion-icon>
            </button>
        </div>
    </nav>

    <!-- Header -->
    <header class="relative h-[40vh] min-h-[300px] flex items-center justify-center overflow-hidden parallax-section" style="background-image: url('IMG_1049.jpg');">
        <div class="absolute inset-0 bg-brand-dark/70 z-10"></div>
        <div class="relative z-20 container mx-auto px-6 text-center text-white mt-12">
            <h1 class="text-4xl md:text-6xl font-serif font-bold mb-4 animate-fade-up">Media Gallery</h1>
            <p class="text-lg md:text-xl text-gray-200 max-w-2xl mx-auto font-light leading-relaxed animate-fade-up delay-100">
                Capturing moments of fellowship, worship, and service.
            </p>
        </div>
    </header>

    <!-- Gallery Grid -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                $res = $conn->query("SELECT * FROM media ORDER BY id DESC");
                if($res->num_rows > 0) {
                    while($row = $res->fetch_assoc()) {
                        $file = $row['file_path'];
                        $type = $row['type'];
                        $title = htmlspecialchars($row['title']);
                ?>
                <div class="group relative overflow-hidden rounded-xl shadow-md border border-gray-100 bg-gray-50">
                    <?php if($type == 'image'): ?>
                        <div class="h-64 overflow-hidden relative cursor-pointer">
                           <img src="<?php echo $file; ?>" alt="<?php echo $title; ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                           <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-4">
                               <a href="<?php echo $file; ?>" download class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-brand-dark hover:text-brand-gold transition-colors" title="Download">
                                   <ion-icon name="download-outline" class="text-xl"></ion-icon>
                               </a>
                               <a href="<?php echo $file; ?>" target="_blank" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-brand-dark hover:text-brand-gold transition-colors" title="View Full">
                                   <ion-icon name="eye-outline" class="text-xl"></ion-icon>
                               </a>
                           </div>
                        </div>
                    <?php else: ?>
                        <div class="h-64 bg-black relative">
                            <video src="<?php echo $file; ?>" class="w-full h-full object-cover" controls></video>
                        </div>
                    <?php endif; ?>
                    
                    <div class="p-4 bg-white">
                        <h4 class="font-bold text-gray-800 text-sm"><?php echo $title; ?></h4>
                        <?php if($type == 'video'): ?>
                        <div class="mt-2 flex gap-2">
                             <a href="<?php echo $file; ?>" download class="text-xs font-bold text-brand-gold uppercase tracking-wider flex items-center gap-1 hover:text-brand-dark">
                                 Download Video <ion-icon name="download-outline"></ion-icon>
                             </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php
                    }
                } else {
                    echo '<p class="col-span-full text-center text-gray-500 italic py-12">No photos or videos shared yet.</p>';
                }
                ?>
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
                        <img src="sdalogo.png" alt="Logo" class="h-10 w-auto opacity-90">
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
                        <li><a href="gallery.php" class="text-brand-gold font-bold transition-colors">Gallery</a></li>
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
