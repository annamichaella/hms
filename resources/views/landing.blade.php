<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light only">
    <meta name="theme-color" content="#ffffff">
    <title>Hospital Management System</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            color-scheme: light !important;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f9fafb !important;
            color: #1f2937 !important;
            line-height: 1.6;
        }

        /* Force light mode */
        @media (prefers-color-scheme: dark) {
            html, body, * {
                background-color: #f9fafb !important;
                color: #1f2937 !important;
            }
        }

        /* Navbar */
        nav {
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.95) !important;
        }

        /* Professional shadows */
        .shadow-professional {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.08), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        .shadow-lg {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* Smooth animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .loading {
            animation: fadeInUp 0.8s ease-out;
        }

        /* Feature cards hover effect */
        .feature-card {
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Button improvements */
        .btn-primary {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3);
        }

        .btn-outline {
            transition: all 0.3s ease;
        }

        .btn-outline:hover {
            transform: translateY(-2px);
        }

        /* Hero section improvements */
        .hero-section {
            background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
        }

        /* Stats section */
        .stat-item {
            transition: transform 0.3s ease;
        }

        .stat-item:hover {
            transform: scale(1.05);
        }

        /* Smooth scroll */
        html {
            scroll-padding-top: 80px;
        }

        /* Mobile menu improvements */
        @media (max-width: 640px) {
            nav .flex.items-center.space-x-4 {
                flex-wrap: wrap;
                gap: 0.5rem;
            }
        }

        /* Image loading */
        img {
            transition: opacity 0.3s ease;
        }

        img[loading="lazy"] {
            opacity: 0;
        }

        img.loaded {
            opacity: 1;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">
    <!-- Navbar -->
    <nav class="bg-white shadow-professional border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg flex items-center justify-center mr-3 shadow-md">
                        <i class="fas fa-heartbeat text-white text-lg"></i>
                    </div>
                    <span class="text-xl font-bold text-gray-800 tracking-tight">MediCare Pro</span>
                </div>
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <a href="#home" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">Home</a>
                    <a href="#features" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">Features</a>
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">Login</a>
                    <a href="{{ route('register') }}" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2.5 rounded-lg text-sm font-semibold hover:from-blue-700 hover:to-blue-800 shadow-md hover:shadow-lg transition-all duration-200">
                        Sign Up
                    </a>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section id="home" class="hero-section pt-20 pb-20 sm:pt-24 sm:pb-24 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Hero Content -->
                <div class="loading">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight mb-6 text-gray-900">
                        Streamline Your
                        <span class="text-blue-600 block sm:inline">
                            Hospital Operations
                        </span>
                    </h1>
                    <p class="text-lg sm:text-xl text-gray-600 mb-8 leading-relaxed">
                        Comprehensive hospital management solution that integrates patient care, 
                        administrative tasks, and medical records in one powerful platform.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 mb-8">
                        <a href="{{ route('login') }}" class="btn-primary bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 text-center shadow-lg">
                            <i class="fas fa-calendar-check mr-2"></i>
                            Book an Appointment
                        </a>
                        <a href="{{ route('login') }}" class="btn-outline border-2 border-blue-600 text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-600 hover:text-white text-center">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Login
                        </a>
                    </div>
                    
                    <!-- Trust Indicators -->
                    <div class="pt-6 border-t border-gray-200">
                        <p class="text-sm text-gray-500 mb-4">Trusted by leading hospitals worldwide</p>
                        <div class="flex flex-wrap items-center gap-6">
                            <div class="flex items-center">
                                <i class="fas fa-star text-yellow-400 mr-2"></i>
                                <span class="text-sm font-semibold text-gray-700">4.9/5 Rating</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-users text-blue-500 mr-2"></i>
                                <span class="text-sm font-semibold text-gray-700">10K+ Users</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-shield-alt text-green-500 mr-2"></i>
                                <span class="text-sm font-semibold text-gray-700">HIPAA Compliant</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hero Image -->
                <div class="loading">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl transform rotate-3 opacity-20 blur-xl"></div>
                        <img src="https://images.unsplash.com/photo-1559757148-5c350d0d3c56?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" 
                             alt="Hospital Management System" 
                             class="relative rounded-2xl shadow-2xl w-full h-auto"
                             loading="lazy"
                             onload="this.classList.add('loaded')">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 px-4 sm:px-6 lg:px-8 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16 loading">
                <h2 class="text-3xl sm:text-4xl font-bold mb-4 text-gray-900">
                    Powerful Features for Modern Healthcare
                </h2>
                <p class="text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto">
                    Everything you need to manage your hospital efficiently, from patient records 
                    to financial reporting, all in one integrated platform.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Feature 1 -->
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-user-md text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900">Patient Management</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Comprehensive patient records, appointment scheduling, and medical history 
                        tracking with secure, HIPAA-compliant storage.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-calendar-check text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900">Appointment Scheduling</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Intelligent scheduling system with automated reminders, conflict detection, 
                        and easy rescheduling capabilities.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                    <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-chart-line text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900">Analytics & Reporting</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Real-time dashboards, performance metrics, and comprehensive reports 
                        to drive data-informed decisions.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                    <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-pills text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900">Pharmacy Management</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Complete inventory tracking, prescription management, and automated 
                        reordering for optimal pharmacy operations.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                    <div class="w-14 h-14 bg-yellow-100 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-credit-card text-yellow-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900">Billing & Insurance</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Automated billing, insurance verification, and payment processing 
                        to streamline financial operations.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                    <div class="w-14 h-14 bg-indigo-100 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-mobile-alt text-indigo-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900">Mobile Access</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Access your hospital management system from anywhere with our 
                        responsive mobile application.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-gray-800 to-gray-900">
        <div class="max-w-7xl mx-auto">
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                <div class="stat-item text-white">
                    <div class="text-4xl sm:text-5xl font-bold mb-2 text-blue-400">500+</div>
                    <p class="text-gray-300 font-medium">Hospitals Trust Us</p>
                </div>
                <div class="stat-item text-white">
                    <div class="text-4xl sm:text-5xl font-bold mb-2 text-green-400">10,000+</div>
                    <p class="text-gray-300 font-medium">Healthcare Professionals</p>
                </div>
                <div class="stat-item text-white">
                    <div class="text-4xl sm:text-5xl font-bold mb-2 text-yellow-400">1M+</div>
                    <p class="text-gray-300 font-medium">Patients Served</p>
                </div>
                <div class="stat-item text-white">
                    <div class="text-4xl sm:text-5xl font-bold mb-2 text-purple-400">99%</div>
                    <p class="text-gray-300 font-medium">Uptime Guarantee</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-blue-600 to-blue-700">
        <div class="max-w-4xl mx-auto text-center">
            <div class="loading">
                <h2 class="text-3xl sm:text-4xl font-bold text-white mb-6">
                    Ready to Transform Your Hospital Operations?
                </h2>
                <p class="text-lg sm:text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                    Join thousands of healthcare professionals who have already upgraded 
                    their hospital management systems.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-all duration-200 inline-block shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        Get Started Free
                    </a>
                    <a href="{{ route('login') }}" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-all duration-200 inline-block">
                        Login to Your Account
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12 mb-12">
                <div>
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mr-3 shadow-lg">
                            <i class="fas fa-heartbeat text-white text-xl"></i>
                        </div>
                        <span class="text-2xl font-bold">MediCare Pro</span>
                    </div>
                    <p class="text-gray-400 leading-relaxed text-sm">
                        Transforming healthcare delivery through innovative technology solutions 
                        that empower medical professionals and improve patient outcomes.
                    </p>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Product</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#features" class="hover:text-white transition-colors text-sm">Features</a></li>
                        <li><a href="#" class="hover:text-white transition-colors text-sm">Pricing</a></li>
                        <li><a href="#" class="hover:text-white transition-colors text-sm">Security</a></li>
                        <li><a href="#" class="hover:text-white transition-colors text-sm">API</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Company</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#about" class="hover:text-white transition-colors text-sm">About</a></li>
                        <li><a href="#" class="hover:text-white transition-colors text-sm">Blog</a></li>
                        <li><a href="#" class="hover:text-white transition-colors text-sm">Careers</a></li>
                        <li><a href="#" class="hover:text-white transition-colors text-sm">Contact</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Support</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors text-sm">Help Center</a></li>
                        <li><a href="#" class="hover:text-white transition-colors text-sm">Documentation</a></li>
                        <li><a href="#" class="hover:text-white transition-colors text-sm">Training</a></li>
                        <li><a href="#" class="hover:text-white transition-colors text-sm">Status</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm mb-4 md:mb-0">
                    &copy; {{ date('Y') }} MediCare Pro. All rights reserved.
                </p>
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors text-xl" aria-label="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors text-xl" aria-label="LinkedIn">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors text-xl" aria-label="Facebook">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors text-xl" aria-label="YouTube">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Lazy load images
        if ('loading' in HTMLImageElement.prototype) {
            const images = document.querySelectorAll('img[loading="lazy"]');
            images.forEach(img => {
                img.src = img.src;
            });
        }
    </script>
</body>
</html>
