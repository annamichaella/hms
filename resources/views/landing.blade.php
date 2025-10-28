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
    <style>
        /* Force light mode - prevent dark mode from affecting the system */
        html {
            color-scheme: light !important;
        }
        
        /* Ensure all elements stay in light mode */
        * {
            color-scheme: light !important;
        }
        
        /* Override any dark mode styles */
        @media (prefers-color-scheme: dark) {
            html, body, * {
                background-color: #f9fafb !important; /* bg-gray-50 */
                color: #1f2937 !important; /* text-gray-800 */
            }
        }
        
        /* Force specific background colors */
        body {
            background-color: #f9fafb !important; /* bg-gray-50 */
        }
        
        /* Ensure white backgrounds stay white */
        .bg-white {
            background-color: #ffffff !important;
        }
        
        /* Ensure text stays dark */
        .text-gray-800, .text-gray-900 {
            color: #1f2937 !important;
        }
        
        .text-gray-600 {
            color: #4b5563 !important;
        }
        
        .text-gray-500 {
            color: #6b7280 !important;
        }
        
        /* Force specific colors for landing page sections */
        .bg-gray-800 {
            background-color: #1f2937 !important;
        }
        
        .bg-gray-900 {
            background-color: #111827 !important;
        }
        
        .text-white {
            color: #ffffff !important;
        }
    </style>
</head>
<body class="font-inter bg-gray-50 text-gray-800">
    <!-- Hero Section -->
    <section id="home" class="hero-section pt-24 pb-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Hero Content -->
                <div class="loading">
                    <h1 class="hero-title text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight mb-6">
                        Streamline Your
                        <span class="text-blue-600">
                            Hospital Operations
                        </span>
                    </h1>
                    <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                        Comprehensive hospital management solution that integrates patient care, 
                        administrative tasks, and medical records in one powerful platform.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('login') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-200 text-center">
                            <i class="fas fa-calendar-check mr-2"></i>
                            Book an Appointment
                        </a>
                        <a href="{{ route('login') }}" class="border-2 border-blue-600 text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-600 hover:text-white transition duration-200 text-center">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Login
                        </a>
                    </div>
                    
                    <!-- Trust Indicators -->
                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <p class="text-sm text-gray-500 mb-4">Trusted by leading hospitals worldwide</p>
                        <div class="flex items-center space-x-6">
                            <div class="flex items-center">
                                <i class="fas fa-star text-yellow-400 mr-1"></i>
                                <span class="text-sm font-medium">4.9/5</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-users text-blue-500 mr-1"></i>
                                <span class="text-sm font-medium">10K+ Users</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-shield-alt text-green-500 mr-1"></i>
                                <span class="text-sm font-medium">HIPAA Compliant</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hero Image -->
                <div class="loading">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1559757148-5c350d0d3c56?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" 
                             alt="Hospital Management System" 
                             class="rounded-2xl shadow-lg w-full h-auto">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 px-4 sm:px-6 lg:px-8 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16 loading">
                <h2 class="text-3xl sm:text-4xl font-bold mb-4">
                    Powerful Features for Modern Healthcare
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Everything you need to manage your hospital efficiently, from patient records 
                    to financial reporting, all in one integrated platform.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-user-md text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Patient Management</h3>
                    <p class="text-gray-600">
                        Comprehensive patient records, appointment scheduling, and medical history 
                        tracking with secure, HIPAA-compliant storage.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Appointment Scheduling</h3>
                    <p class="text-gray-600">
                        Intelligent scheduling system with automated reminders, conflict detection, 
                        and easy rescheduling capabilities.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Analytics & Reporting</h3>
                    <p class="text-gray-600">
                        Real-time dashboards, performance metrics, and comprehensive reports 
                        to drive data-informed decisions.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-pills text-red-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Pharmacy Management</h3>
                    <p class="text-gray-600">
                        Complete inventory tracking, prescription management, and automated 
                        reordering for optimal pharmacy operations.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-credit-card text-yellow-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Billing & Insurance</h3>
                    <p class="text-gray-600">
                        Automated billing, insurance verification, and payment processing 
                        to streamline financial operations.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-mobile-alt text-indigo-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Mobile Access</h3>
                    <p class="text-gray-600">
                        Access your hospital management system from anywhere with our 
                        responsive mobile application.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-gray-800">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-4 gap-8 text-center">
                <div class="text-white">
                    <div class="text-4xl font-bold mb-2">500+</div>
                    <p class="text-white/90 font-medium">Hospitals Trust Us</p>
                </div>
                <div class="text-white">
                    <div class="text-4xl font-bold mb-2">10,000+</div>
                    <p class="text-white/90 font-medium">Healthcare Professionals</p>
                </div>
                <div class="text-white">
                    <div class="text-4xl font-bold mb-2">1,000,000+</div>
                    <p class="text-white/90 font-medium">Patients Served</p>
                </div>
                <div class="text-white">
                    <div class="text-4xl font-bold mb-2">99%</div>
                    <p class="text-white/90 font-medium">Uptime Guarantee</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-gray-800">
        <div class="max-w-4xl mx-auto text-center">
            <div class="loading">
                <h2 class="text-3xl sm:text-4xl font-bold text-white mb-6">
                    Ready to Transform Your Hospital Operations?
                </h2>
                <p class="text-xl text-white/90 mb-8">
                    Join thousands of healthcare professionals who have already upgraded 
                    their hospital management systems.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" class="bg-white text-gray-800 px-6 py-3 rounded-lg font-medium hover:bg-gray-100 transition-colors inline-block">
                        Get Started
                    </a>
                    <a href="{{ route('login') }}" class="border-2 border-white text-white px-6 py-3 rounded-lg font-medium hover:bg-white hover:text-gray-800 transition-colors inline-block">
                        Login
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-4 gap-8 mb-12">
                <div>
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-heartbeat text-white text-xl"></i>
                        </div>
                        <span class="text-2xl font-bold">MediCare Pro</span>
                    </div>
                    <p class="text-gray-400 leading-relaxed">
                        Transforming healthcare delivery through innovative technology solutions 
                        that empower medical professionals and improve patient outcomes.
                    </p>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Product</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#features" class="hover:text-white transition-colors">Features</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Pricing</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Security</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">API</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Company</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#about" class="hover:text-white transition-colors">About</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Blog</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Careers</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Support</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Help Center</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Documentation</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Training</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Status</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm">
                    &copy; 2025 MediCare Pro. All rights reserved.
                </p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fab fa-twitter text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fab fa-linkedin text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fab fa-facebook text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fab fa-youtube text-xl"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>

