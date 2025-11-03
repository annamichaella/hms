<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light only">
    <meta name="theme-color" content="#ffffff">
    <title>Login - Hospital Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Force light mode - prevent dark mode from affecting the system */
        html {
            color-scheme: light !important;
        }
        
        * {
            color-scheme: light !important;
        }
        
        @media (prefers-color-scheme: dark) {
            html, body, * {
                background-color: #f3f4f6 !important; /* bg-gray-100 */
                color: #1f2937 !important; /* text-gray-800 */
            }
        }
        
        body {
            background-color: #f3f4f6 !important; /* bg-gray-100 */
        }
        
        .bg-white {
            background-color: #ffffff !important;
        }
        
        .shadow-professional {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.08), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center py-4">
    <div class="w-full max-w-sm bg-white rounded-lg shadow-professional p-6">
        <!-- Back Button -->
        <div class="mb-3">
            <a href="{{ route('landing') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-800 transition-colors">
                <i class="fas fa-arrow-left mr-1.5 text-xs"></i>
                Back to Home
            </a>
        </div>
        
        <div class="text-center mb-5">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm">
                <i class="fas fa-heartbeat text-white text-lg"></i>
            </div>
            <h1 class="text-xl font-bold text-gray-700">Welcome Back</h1>
            <p class="text-sm text-gray-600 mt-1">Sign in to your account</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}"
                       required 
                       class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email') border-red-500 @enderror"
                       placeholder="Enter your email">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       required
                       class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('password') border-red-500 @enderror"
                       placeholder="Enter your password">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="remember" 
                           name="remember" 
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-700">
                        Remember me
                    </label>
                </div>
                <a href="#" class="text-sm text-blue-600 hover:text-blue-500">
                    Forgot password?
                </a>
            </div>

            <button type="submit" 
                    class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-2.5 px-4 rounded-lg font-medium hover:from-blue-700 hover:to-blue-800 shadow-md hover:shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-sm">
                <i class="fas fa-sign-in-alt mr-2"></i>
                Sign In
            </button>
        </form>

        <!-- Divider -->
        <div class="mt-4">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-xs">
                    <span class="px-2 bg-white text-gray-500">Or</span>
                </div>
            </div>
        </div>

        <!-- Social Login Buttons -->
        <div class="mt-3 grid grid-cols-2 gap-2">
            <a href="{{ route('google.login') }}" 
               class="inline-flex items-center justify-center px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-white text-xs font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="fab fa-google text-red-600 mr-1.5"></i>
                Google
            </a>
            
            <a href="{{ route('facebook.login') }}" 
               class="inline-flex items-center justify-center px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-white text-xs font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="fab fa-facebook text-blue-600 mr-1.5"></i>
                Facebook
            </a>
        </div>

        <div class="mt-4 text-center">
            <p class="text-gray-600">
                Don't have an account? 
                <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-500 font-medium">
                    Create one here
                </a>
            </p>
        </div>
    </div>
</body>
</html>

