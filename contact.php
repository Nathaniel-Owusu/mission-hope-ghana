<?php
include 'admin/db.php';

$success_msg = "";
$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send_message'])) {
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $subject = $conn->real_escape_string($_POST['subject']);
    $message = $conn->real_escape_string($_POST['message']);

    $sql = "INSERT INTO messages (first_name, last_name, email, subject, message) VALUES ('$first_name', '$last_name', '$email', '$subject', '$message')";

    if ($conn->query($sql) === TRUE) {
        $success_msg = "Thank you! Your message has been sent successfully.";
    } else {
        $error_msg = "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Mission Hope SDA Church</title>
    
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
                <a href="gallery.php" class="text-gray-800 hover:text-brand-gold font-medium transition-colors text-sm uppercase tracking-widest relative after:content-[''] after:absolute after:-bottom-2 after:left-0 after:w-0 after:h-0.5 after:bg-brand-gold after:transition-all hover:after:w-full">Gallery</a>
                <a href="contact.php" class="px-6 py-2 bg-brand-gold hover:bg-brand-gold/90 text-white rounded-full font-semibold transition-all shadow-lg hover:shadow-brand-gold/50 transform hover:-translate-y-0.5 backdrop-blur-sm shadow-brand-gold/50">Contact Us</a>
            </div>

            <!-- Mobile Button -->
            <button class="md:hidden text-brand-dark text-3xl focus:outline-none">
                <ion-icon name="menu-outline"></ion-icon>
            </button>
        </div>
    </nav>

    <!-- Header -->
    <header class="relative h-[50vh] min-h-[400px] flex items-center justify-center overflow-hidden parallax-section" style="background-image: url('IMG_1022.jpg');">
        <div class="absolute inset-0 bg-brand-dark/80 z-10"></div>
        <div class="relative z-20 container mx-auto px-6 text-center text-white mt-12">
             <span class="inline-block py-1 px-4 border border-white/30 rounded-full bg-white/10 backdrop-blur-md text-xs font-bold tracking-[0.2em] uppercase mb-4 animate-fade-up">Get in Touch</span>
            <h1 class="text-4xl md:text-6xl font-serif font-bold mb-4 animate-fade-up delay-100">Contact Us</h1>
            <p class="text-lg md:text-xl text-gray-200 max-w-2xl mx-auto font-light leading-relaxed animate-fade-up delay-200">
                We'd love to hear from you. Whether you have a question, a prayer request, or just want to say hello.
            </p>
        </div>
    </header>

    <!-- Contact Content -->
    <section class="py-20 bg-white relative">
        <div class="container mx-auto px-6">
            <div class="flex flex-col lg:flex-row gap-16">
                
                <!-- Info Side -->
                <div class="lg:w-1/3 space-y-12">
                    <!-- Intro -->
                    <div>
                        <span class="text-brand-gold font-bold tracking-widest uppercase text-xs block mb-2">Reach Out</span>
                        <h2 class="text-3xl font-serif font-bold text-gray-900 mb-6">We're here for you.</h2>
                        <p class="text-gray-600 leading-relaxed">
                            Have questions about our services, ministries, or beliefs? Feel free to reach out to us using the form or the contact details below. We look forward to connecting with you.
                        </p>
                    </div>

                    <!-- Contact Details -->
                    <div class="space-y-6">
                        <div class="flex items-start gap-4 p-4 rounded-xl bg-brand-cream border border-brand-cream hover:border-brand-gold/30 transition-colors">
                            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-brand-gold shadow-sm shrink-0">
                                <ion-icon name="location" class="text-xl"></ion-icon>
                            </div>
                            <div>
                                <h4 class="font-serif font-bold text-gray-900 mb-1">Visit Us</h4>
                                <p class="text-gray-600 text-sm">Takofiano, Techiman, Ghana</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 p-4 rounded-xl bg-brand-cream border border-brand-cream hover:border-brand-gold/30 transition-colors">
                            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-brand-gold shadow-sm shrink-0">
                                <ion-icon name="call" class="text-xl"></ion-icon>
                            </div>
                            <div>
                                <h4 class="font-serif font-bold text-gray-900 mb-1">Call Us</h4>
                                <p class="text-gray-600 text-sm">+233 20 123 4567</p>
                                <p class="text-gray-500 text-xs mt-1">Mon-Fri, 9am - 5pm</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 p-4 rounded-xl bg-brand-cream border border-brand-cream hover:border-brand-gold/30 transition-colors">
                            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-brand-gold shadow-sm shrink-0">
                                <ion-icon name="mail" class="text-xl"></ion-icon>
                            </div>
                            <div>
                                <h4 class="font-serif font-bold text-gray-900 mb-1">Email Us</h4>
                                <p class="text-gray-600 text-sm">info@missionhopesda.org</p>
                            </div>
                        </div>
                    </div>

                    <!-- Service Times -->
                    <div class="p-6 bg-brand-dark rounded-2xl text-white relative overflow-hidden">
                        <div class="relative z-10">
                            <h3 class="font-serif font-bold text-xl mb-4">Service Times</h3>
                            <ul class="space-y-3 text-sm opacity-90">
                                <li class="flex justify-between border-b border-white/10 pb-2">
                                    <span>Sabbath School</span>
                                    <span class="font-bold">Sat 8:30 AM</span>
                                </li>
                                <li class="flex justify-between border-b border-white/10 pb-2">
                                    <span>Divine Service</span>
                                    <span class="font-bold">Sat 10:30 AM</span>
                                </li>
                                <li class="flex justify-between border-b border-white/10 pb-2">
                                    <span>Bible Study</span>
                                    <span class="font-bold">Sat 3:00 PM</span>
                                </li>
                                <li class="flex justify-between">
                                    <span>Wednesday Prayer</span>
                                    <span class="font-bold">Wed 7:00 PM</span>
                                </li>
                            </ul>
                        </div>
                        <!-- Decorative circle -->
                        <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-brand-gold rounded-full opacity-20 blur-2xl"></div>
                    </div>
                </div>

                <!-- Form Side -->
                <div class="lg:w-2/3">
                    <div class="bg-white p-8 md:p-10 rounded-2xl shadow-xl border border-gray-100">
                        <h3 class="text-2xl font-serif font-bold text-gray-900 mb-6">Send a Message</h3>
                        
                        <?php if($success_msg): ?>
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <strong class="font-bold">Success!</strong>
                                <span class="block sm:inline"><?php echo $success_msg; ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if($error_msg): ?>
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <strong class="font-bold">Error!</strong>
                                <span class="block sm:inline"><?php echo $error_msg; ?></span>
                            </div>
                        <?php endif; ?>

                        <form class="space-y-6" method="POST" action="contact.php">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">First Name</label>
                                    <input type="text" name="first_name" class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-brand-gold focus:bg-white focus:ring-0 transition-all outline-none" placeholder="John" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Last Name</label>
                                    <input type="text" name="last_name" class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-brand-gold focus:bg-white focus:ring-0 transition-all outline-none" placeholder="Doe" required>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                                    <input type="email" name="email" class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-brand-gold focus:bg-white focus:ring-0 transition-all outline-none" placeholder="john@example.com" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Subject</label>
                                    <select name="subject" class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-brand-gold focus:bg-white focus:ring-0 transition-all outline-none">
                                        <option>General Inquiry</option>
                                        <option>Prayer Request</option>
                                        <option>Membership</option>
                                        <option>Pastoral Care</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Your Message</label>
                                <textarea name="message" rows="5" class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-brand-gold focus:bg-white focus:ring-0 transition-all outline-none" placeholder="How can we help you?" required></textarea>
                            </div>

                            <button type="submit" name="send_message" class="w-full md:w-auto px-8 py-4 bg-brand-gold hover:bg-brand-dark text-white font-bold rounded-lg transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="h-[400px] w-full bg-gray-200 relative">
        <iframe src="https://maps.google.com/maps?q=Tako+SDA+School,+Techiman,+Bono+East+Region,+Ghana&t=&z=14&ie=UTF8&iwloc=&output=embed" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
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
                        <li><a href="contact.php" class="text-brand-gold font-bold transition-colors">Contact</a></li>
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
        
        // Mobile Menu Toggle logic needing implementation if desired
    </script>
</body>
</html>
