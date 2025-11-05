<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light only">
    <meta name="theme-color" content="#ffffff">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Hospital Management System')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/js/laravel-ajax.js') }}"></script>
    <script src="{{ asset('assets/js/notifications.js') }}"></script>
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
            background-color: #f8fafc !important; /* bg-slate-50 */
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        
        /* Ensure white backgrounds stay white */
        .bg-white {
            background-color: #ffffff !important;
        }
        
        /* Ensure text stays dark */
        .text-gray-800, .text-gray-900 {
            color: #1e293b !important;
        }
        
        .text-gray-600 {
            color: #475569 !important;
        }
        
        .text-gray-500 {
            color: #64748b !important;
        }
        
        /* Ensure sidebar stays fixed and doesn't break layout */
        body {
            overflow-x: hidden;
        }
        
        /* Table container styling */
        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .table-container table {
            width: 100%;
            min-width: 100%;
        }
        
        /* Enhanced shadows and transitions */
        .shadow-professional {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.08), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
        
        .shadow-elevated {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        /* Smooth transitions */
        * {
            transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease;
        }
        
        /* Subtle focus states aligned with patient design */
        button:focus, a:focus, input:focus, select:focus, textarea:focus {
            outline: 2px solid rgba(59, 130, 246, 0.5);
            outline-offset: 2px;
        }
        
        /* Professional button styles */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.625rem 1rem; /* 10px 16px */
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            line-height: 1.25rem;
            transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease, transform 0.15s ease, box-shadow 0.2s ease;
        }
        .btn-sm { padding: 0.375rem 0.75rem; border-radius: 0.375rem; font-size: 0.8125rem; }
        .btn-lg { padding: 0.875rem 1.25rem; font-size: 1rem; }
        .btn-primary {
            background: linear-gradient(to right, #2563eb, #1d4ed8);
            box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
            color: #ffffff;
        }
        
        .btn-primary:hover {
            background: linear-gradient(to right, #1d4ed8, #1e40af);
            box-shadow: 0 4px 6px rgba(37, 99, 235, 0.3);
            transform: translateY(-1px);
        }
        
        /* Patient-style secondary and outline buttons */
        .btn-secondary {
            border: 2px solid #d1d5db;
            color: #374151;
            border-radius: 0.5rem;
            font-weight: 600;
            background-color: #ffffff;
        }
        .btn-secondary:hover { background-color: #f3f4f6; }
        
        .btn-outline {
            border: 2px solid #2563eb;
            color: #2563eb;
            border-radius: 0.5rem;
            font-weight: 600;
            background-color: #ffffff;
        }
        .btn-outline:hover { background-color: #2563eb; color: #ffffff; }
        
        .btn-danger {
            background-color: #ef4444;
            color: #ffffff;
        }
        .btn-danger:hover { background-color: #dc2626; }
        
        /* Better table row hover */
        tbody tr {
            transition: background-color 0.15s ease;
        }
        
        tbody tr:hover {
            background-color: #f8fafc;
        }
        
        /* Improved link styles */
        a.text-blue-600 {
            color: #2563eb;
            transition: color 0.2s ease;
        }
        
        a.text-blue-600:hover {
            color: #1d4ed8;
        }
        
        /* Patient-style subtle card utility for consistency */
        .ui-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        /* Patient-style fade-in */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in { animation: fadeIn 0.4s ease-out; }
        
        /* Modal animations */
        @keyframes overlayFade {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes panelIn {
            from { opacity: 0; transform: translateY(8px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .modal-overlay { animation: overlayFade 0.18s ease-out; }
        .modal-panel { animation: panelIn 0.22s ease-out; }
        
        /* Subtle micro-interactions for a less static feel */
        button, .btn, a.button-like {
            transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease, transform 0.15s ease, box-shadow 0.2s ease;
        }
        button:hover, .btn:hover, a.button-like:hover { transform: translateY(-1px); }
        button:active, .btn:active, a.button-like:active { transform: translateY(0); }
        
        /* Elevate cards and tables on hover */
        .shadow-professional:hover, .shadow-elevated:hover, .ui-card:hover {
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }
        
        /* Unified search bar */
        .search-bar {
            display: flex;
            align-items: center;
            border: 1px solid #e5e7eb; /* gray-200 */
            border-radius: 0.5rem; /* rounded-lg */
            background-color: #ffffff;
            padding: 0.5rem 0.75rem; /* py-2 px-3 */
        }
        .search-bar i { color: #9ca3af; /* gray-400 */ margin-right: 0.5rem; font-size: 0.875rem; }
        .search-input {
            flex: 1 1 auto;
            border: none;
            outline: none;
            padding: 0.375rem 0.25rem 0.375rem 0.5rem; /* compact */
            font-size: 0.875rem; /* text-sm */
            color: #374151; /* gray-700 */
            background: transparent;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-elevated flex-shrink-0 flex flex-col fixed h-screen left-0 top-0 z-40 border-r border-gray-100">
            <div class="px-4 py-4 h-[72px] flex-shrink-0 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-white flex items-center">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg flex items-center justify-center mr-2.5 shadow-sm">
                        <i class="fas fa-heartbeat text-white text-sm"></i>
                    </div>
                    <span class="text-lg font-semibold text-gray-800 tracking-tight leading-tight">MediCare Pro</span>
                </div>
            </div>
            
            <nav class="mt-4 flex-1 overflow-y-auto px-2 pb-4">
                @yield('sidebar')
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col ml-64 min-w-0">
            <!-- Header -->
            <header class="bg-white shadow-professional border-b border-gray-100 flex-shrink-0">
                <div class="px-4 py-4 h-[72px] flex items-center justify-between">
                    <h1 class="text-lg font-semibold text-gray-800 tracking-tight leading-tight">@yield('page-title', 'Dashboard')</h1>
                    
                    <div class="flex items-center space-x-2.5">
                        <!-- User Menu (patient-style) -->
                        <div class="relative" id="user-menu-container">
                            <button id="user-menu-button" class="flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-md">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                <div class="hidden md:block text-left">
                                    <p class="text-sm font-medium text-gray-800">{{ Auth::user()->full_name }}</p>
                                    <p class="text-xs text-gray-500">{{ ucfirst(Auth::user()->role) }}</p>
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
                                            <i class="fas fa-sign-out-alt mr-3 w-4"></i>Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6 overflow-y-auto fade-in">
                @if(session('success'))
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-800 px-4 py-3 rounded-lg mb-4 flex items-center justify-between shadow-professional">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2.5 text-green-600"></i>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800 rounded p-1 hover:bg-green-100 transition-colors">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>
                    <script>
                        setTimeout(() => showNotification('{{ session('success') }}', 'success'), 100);
                    </script>
                @endif
                @if(session('error'))
                    <div class="bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 text-red-800 px-4 py-3 rounded-lg mb-4 flex items-center justify-between shadow-professional">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-2.5 text-red-600"></i>
                            <span class="font-medium">{{ session('error') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800 rounded p-1 hover:bg-red-100 transition-colors">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>
                    <script>
                        setTimeout(() => showNotification('{{ session('error') }}', 'error'), 100);
                    </script>
                @endif
                <div class="max-w-full">
                @yield('content')
                </div>
            </main>
        </div>
    </div>

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
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (userMenuContainer && !userMenuContainer.contains(event.target)) {
                        dropdown.classList.add('hidden');
                    }
                });
                
                // Prevent dropdown from closing when clicking inside it
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
                    
                    if (confirm('Are you sure you want to logout?')) {
                        logoutForm.submit();
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
