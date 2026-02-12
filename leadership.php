<?php
include 'admin/db.php';
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leadership | Mission Hope SDA Church</title>
    
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
                <a href="leadership.php" class="text-brand-gold font-bold transition-colors text-sm uppercase tracking-widest relative after:content-[''] after:absolute after:-bottom-2 after:left-0 after:w-full after:h-0.5 after:bg-brand-gold after:transition-all">Leadership</a>
                <a href="gallery.php" class="text-gray-800 hover:text-brand-gold font-medium transition-colors text-sm uppercase tracking-widest relative after:content-[''] after:absolute after:-bottom-2 after:left-0 after:w-0 after:h-0.5 after:bg-brand-gold after:transition-all hover:after:w-full">Gallery</a>
                <a href="contact.php" class="px-6 py-2 bg-brand-gold/90 hover:bg-brand-gold text-white rounded-full font-semibold transition-all shadow-lg hover:shadow-brand-gold/50 transform hover:-translate-y-0.5 backdrop-blur-sm shadow-brand-gold/50">Contact Us</a>
            </div>

            <!-- Mobile Button -->
            <button class="md:hidden text-brand-dark text-3xl focus:outline-none">
                <ion-icon name="menu-outline"></ion-icon>
            </button>
        </div>
    </nav>

    <!-- Header -->
    <header class="relative h-[50vh] min-h-[400px] flex items-center justify-center overflow-hidden parallax-section" style="background-image: url('IMG_1138.JPG');">
        <div class="absolute inset-0 bg-brand-dark/80 z-10"></div>
        <div class="relative z-20 container mx-auto px-6 text-center text-white mt-12">
             <span class="inline-block py-1 px-4 border border-white/30 rounded-full bg-white/10 backdrop-blur-md text-xs font-bold tracking-[0.2em] uppercase mb-4 animate-fade-up">Servant Leaders</span>
            <h1 class="text-4xl md:text-6xl font-serif font-bold mb-4 animate-fade-up delay-100">Our Leadership</h1>
            <p class="text-lg md:text-xl text-gray-200 max-w-2xl mx-auto font-light leading-relaxed animate-fade-up delay-200">
                Dedicated men and women called to serve, guide, and shepherd the flock of God.
            </p>
        </div>
    </header>

    <!-- Pastoral Team -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                 <span class="text-brand-gold font-bold tracking-widest uppercase text-xs">Shepherds of the Flock</span>
                <h2 class="text-3xl md:text-4xl font-serif font-bold text-gray-900 mt-2">Our Pastors</h2>
            </div>
            
             <div class="grid grid-cols-1 md:grid-cols-2 gap-12 justify-center">
                <?php
                // Fetch all pastors (using LIKE to catch 'Church Pastor', 'District Pastor', 'Pastor')
                $pastors = $conn->query("SELECT * FROM leadership WHERE category LIKE '%Pastor%' OR role LIKE '%Pastor%' ORDER BY id ASC");
                if($pastors->num_rows > 0) {
                    while($pastor = $pastors->fetch_assoc()) {
                        $img = $pastor['image'] ? $pastor['image'] : 'odame.jpg';
                ?>
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 flex flex-col items-center text-center">
                    <div class="w-full h-80 relative bg-gray-100">
                        <img src="<?php echo $img; ?>" alt="<?php echo $pastor['name']; ?>" class="w-full h-full object-cover object-top">
                    </div>
                    <div class="p-8 md:p-10 w-full">
                        <h3 class="text-2xl font-serif font-bold text-gray-900 mb-2"><?php echo $pastor['name']; ?></h3>
                        <p class="text-brand-gold uppercase tracking-widest text-xs font-bold mb-6"><?php echo $pastor['category']; ?></p> <!-- Show Position not Role here if Role is description -->
                        <p class="text-gray-600 leading-relaxed mb-6">
                           <?php echo $pastor['role']; ?> <!-- Using 'role' field as short bio/description or title -->
                        </p>
                         <div class="flex justify-center gap-4">
                            <?php if($pastor['email']): ?>
                            <a href="mailto:<?php echo $pastor['email']; ?>" class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-brand-gold hover:text-white transition-all">
                                <ion-icon name="mail-outline"></ion-icon>
                            </a>
                            <?php endif; ?>
                             <?php if($pastor['phone']): ?>
                             <a href="tel:<?php echo $pastor['phone']; ?>" class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-brand-gold hover:text-white transition-all">
                                <ion-icon name="call-outline"></ion-icon>
                            </a>
                             <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php 
                    }
                } else {
                    // Fallback if no pastors found
                    echo '<p class="col-span-full text-center">No pastors listed.</p>';
                } 
                ?>
            </div>
        </div>
    </section>

    <!-- Leadership Values -->
    <section class="py-16 bg-brand-dark text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#d4a373 1px, transparent 1px); background-size: 20px 20px;"></div>
        <div class="container mx-auto px-6 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div class="p-6 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm hover:transform hover:-translate-y-1 transition-all duration-300">
                    <div class="w-12 h-12 mx-auto bg-brand-gold rounded-full flex items-center justify-center text-white mb-4 shadow-lg shadow-brand-gold/20">
                        <ion-icon name="heart-outline" class="text-2xl"></ion-icon>
                    </div>
                    <h3 class="text-xl font-serif font-bold mb-2">Servant Leadership</h3>
                    <p class="text-gray-300 text-sm leading-relaxed">Following Christ's example, we lead by serving others with humility and love.</p>
                </div>
                <div class="p-6 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm hover:transform hover:-translate-y-1 transition-all duration-300">
                    <div class="w-12 h-12 mx-auto bg-brand-gold rounded-full flex items-center justify-center text-white mb-4 shadow-lg shadow-brand-gold/20">
                        <ion-icon name="book-outline" class="text-2xl"></ion-icon>
                    </div>
                    <h3 class="text-xl font-serif font-bold mb-2">Biblical Authority</h3>
                    <p class="text-gray-300 text-sm leading-relaxed">Our guidance and decisions are firmly rooted in the unchanging truth of Scripture.</p>
                </div>
                <div class="p-6 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm hover:transform hover:-translate-y-1 transition-all duration-300">
                    <div class="w-12 h-12 mx-auto bg-brand-gold rounded-full flex items-center justify-center text-white mb-4 shadow-lg shadow-brand-gold/20">
                        <ion-icon name="flame-outline" class="text-2xl"></ion-icon>
                    </div>
                    <h3 class="text-xl font-serif font-bold mb-2">Spirit-Led Vision</h3>
                    <p class="text-gray-300 text-sm leading-relaxed">We seek the Holy Spirit's guidance in every step to fulfill God's mission.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Unified Leadership Team -->
    <section class="py-20 bg-brand-cream">
        <div class="container mx-auto px-6">
             <div class="text-center mb-16">
                 <span class="text-brand-gold font-bold tracking-widest uppercase text-xs">Serving Together</span>
                <h2 class="text-3xl md:text-4xl font-serif font-bold text-gray-900 mt-2">Church Leadership</h2>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                <?php
                // Fetch everyone ELSE (Not pastors)
                $leaders = $conn->query("SELECT * FROM leadership WHERE category NOT LIKE '%Pastor%' AND role NOT LIKE '%Pastor%' ORDER BY category ASC, name ASC");
                
                if($leaders->num_rows > 0) {
                    while($leader = $leaders->fetch_assoc()){
                        $img = $leader['image'] ? $leader['image'] : 'https://ui-avatars.com/api/?name='.urlencode($leader['name']).'&background=fcfbf7&color=d4a373';
                ?>
                <div class="bg-white p-6 rounded-xl shadow-sm text-center group hover:-translate-y-2 transition-transform duration-300 border border-transparent hover:border-brand-gold/20">
                    <div class="w-32 h-32 mx-auto rounded-full overflow-hidden mb-4 border-4 border-brand-cream group-hover:border-brand-gold transition-colors shadow-inner">
                        <img src="<?php echo $img; ?>" alt="<?php echo $leader['name']; ?>" class="w-full h-full object-cover">
                    </div>
                    <h4 class="font-bold text-lg text-gray-900 mb-1 leading-tight"><?php echo $leader['name']; ?></h4>
                    <p class="text-xs text-brand-gold uppercase tracking-widest font-bold mb-2"><?php echo $leader['category']; ?></p>
                    <p class="text-sm text-gray-500 italic"><?php echo $leader['role']; ?></p>
                </div>
                <?php 
                    }
                } else {
                     echo "<p class='col-span-full text-center text-gray-500 italic'>No other leaders listed yet.</p>";
                }
                ?>
            </div>
        </div>
    </section>



    <!-- Join the Team Call to Action -->
    <section class="py-20 relative overflow-hidden">
        <div class="absolute inset-0 bg-brand-dark">
             <img src="IMG_1022.jpg" class="w-full h-full object-cover opacity-20" alt="Background">
             <div class="absolute inset-0 bg-gradient-to-r from-brand-dark/90 to-brand-dark/70"></div>
        </div>
        
        <div class="container mx-auto px-6 relative z-10 text-center">
            <h2 class="text-3xl md:text-5xl font-serif font-bold text-white mb-6">God Has Given Everyone a Gift</h2>
            <p class="text-gray-300 max-w-2xl mx-auto mb-10 text-lg">
                The church functions best when every member plays their part. Whether you have a heart for kids, a talent for music, or a gift of hospitality, there is a place for you to serve.
            </p>
            <a href="contact.php" class="inline-block px-8 py-4 bg-brand-gold hover:bg-white hover:text-brand-dark text-white font-bold rounded-full transition-all shadow-[0_0_20px_rgba(212,163,115,0.3)] hover:shadow-[0_0_30px_rgba(255,255,255,0.4)] transform hover:-translate-y-1">
                Volunteer Today
            </a>
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
