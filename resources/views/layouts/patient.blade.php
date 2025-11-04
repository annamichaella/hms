<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light only">
    <meta name="theme-color" content="#ffffff">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Patient Portal') - MediCare Pro</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/js/laravel-ajax.js') }}"></script>
    <script src="{{ asset('assets/js/notifications.js') }}"></script>
    <style>
        /* Force light mode */
        html {
            color-scheme: light !important;
        }
        
        * {
            color-scheme: light !important;
        }
        
        body {
            background: linear-gradient(to bottom, #f0f9ff 0%, #f8fafc 100%);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            min-height: 100vh;
        }
        
        /* Soft hospital-themed colors */
        .bg-soft-blue {
            background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
        }
        
        .bg-soft-green {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        }
        
        .bg-soft-purple {
            background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%);
        }
        
        .text-soft-blue {
            color: #0369a1;
        }
        
        .hover-soft {
            transition: all 0.3s ease;
        }
        
        .hover-soft:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        /* Standardized patient-friendly card styles */
        .patient-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .patient-card:hover {
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }
        
        /* Standardized button styles */
        .btn-primary {
            background: linear-gradient(to right, #2563eb, #1d4ed8);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(to right, #1d4ed8, #1e40af);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            transform: translateY(-1px);
        }
        
        .btn-secondary {
            border: 2px solid #d1d5db;
            color: #374151;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        
        .btn-secondary:hover {
            background-color: #f9fafb;
        }
        
        .btn-danger {
            background-color: #fef2f2;
            color: #dc2626;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .btn-danger:hover {
            background-color: #fee2e2;
        }
        
        .btn-outline {
            border: 2px solid #2563eb;
            color: #2563eb;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        
        .btn-outline:hover {
            background-color: #2563eb;
            color: white;
        }
        
        /* Smooth animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        
        /* Standardized page header */
        .page-header {
            margin-bottom: 2rem;
        }
        
        .page-header h1 {
            font-size: 1.875rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }
        
        .page-header p {
            color: #4b5563;
        }
        
        /* Standardized empty state */
        .empty-state {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 3rem;
            text-align: center;
        }
        
        .empty-state-icon {
            width: 6rem;
            height: 6rem;
            background-color: #f3f4f6;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        
        .empty-state h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }
        
        .empty-state p {
            color: #4b5563;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation Header -->
    <nav class="bg-white shadow-sm border-b border-blue-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo and Brand -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('patient.dashboard') }}" class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-md">
                            <i class="fas fa-heartbeat text-white text-lg"></i>
                        </div>
                        <div>
                            <span class="text-xl font-bold text-gray-800">MediCare Pro</span>
                            <p class="text-xs text-gray-500">Patient Portal</p>
                        </div>
                    </a>
                </div>
                
                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('patient.dashboard') }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('patient.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-home mr-2"></i>Home
                    </a>
                    <a href="{{ route('patient.appointments') }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('patient.appointments*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-calendar-check mr-2"></i>Appointments
                    </a>
                    <a href="{{ route('patient.records') }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('patient.records*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-file-medical mr-2"></i>Records
                    </a>
                    <a href="{{ route('patient.billing') }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('patient.billing*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-credit-card mr-2"></i>Billing
                    </a>
                </div>
                
                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    <div class="relative" id="user-menu-container">
                        <button id="user-menu-button" class="flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-md">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-medium text-gray-800">{{ Auth::user()->full_name }}</p>
                                <p class="text-xs text-gray-500">Patient</p>
                            </div>
                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div id="user-menu-dropdown" class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 z-50 hidden overflow-hidden">
                            <div class="py-2">
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-sm font-medium text-gray-800">{{ Auth::user()->full_name }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                </div>
                                <a href="#" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                    <i class="fas fa-user mr-3 w-4"></i>My Profile
                                </a>
                                <a href="#" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                    <i class="fas fa-cog mr-3 w-4"></i>Settings
                                </a>
                                <hr class="my-2 border-gray-100">
                                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                    @csrf
                                    <button type="button" id="logout-button" class="block w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        <i class="fas fa-sign-out-alt mr-3 w-4"></i>Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden border-t border-gray-100 hidden">
            <div class="px-4 py-3 space-y-1">
                <a href="{{ route('patient.dashboard') }}" 
                   class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('patient.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="fas fa-home mr-2"></i>Home
                </a>
                <a href="{{ route('patient.appointments') }}" 
                   class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('patient.appointments*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="fas fa-calendar-check mr-2"></i>Appointments
                </a>
                <a href="{{ route('patient.records') }}" 
                   class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('patient.records*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="fas fa-file-medical mr-2"></i>Records
                </a>
                <a href="{{ route('patient.billing') }}" 
                   class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('patient.billing*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="fas fa-credit-card mr-2"></i>Billing
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-400 text-green-800 px-4 py-3 rounded-lg shadow-sm fade-in flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2 text-green-600"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-400 text-red-800 px-4 py-3 rounded-lg shadow-sm fade-in flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2 text-red-600"></i>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif
        
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center text-gray-600 text-sm">
                <p>&copy; {{ date('Y') }} MediCare Pro. All rights reserved.</p>
                <p class="mt-2 text-gray-500">For medical emergencies, please call 911</p>
            </div>
        </div>
    </footer>

    <script>
        // User menu dropdown toggle
        document.addEventListener('DOMContentLoaded', function() {
            const userMenuButton = document.getElementById('user-menu-button');
            const dropdown = document.getElementById('user-menu-dropdown');
            const userMenuContainer = document.getElementById('user-menu-container');
            
            if (userMenuButton && dropdown) {
                userMenuButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdown.classList.toggle('hidden');
                });
                
                document.addEventListener('click', function(event) {
                    if (userMenuContainer && !userMenuContainer.contains(event.target)) {
                        dropdown.classList.add('hidden');
                    }
                });
                
                dropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
            
            // Logout confirmation
            const logoutButton = document.getElementById('logout-button');
            const logoutForm = document.getElementById('logout-form');
            
            if (logoutButton && logoutForm) {
                logoutButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    if (confirm('Are you sure you want to sign out?')) {
                        logoutForm.submit();
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
