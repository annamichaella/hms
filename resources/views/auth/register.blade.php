<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light only">
    <meta name="theme-color" content="#ffffff">
    <title>Register - Hospital Management System</title>
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
    <div class="w-full max-w-xl bg-white rounded-lg shadow-professional p-6">
        <!-- Back Button -->
        <div class="mb-3">
            <a href="{{ route('landing') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-800 transition-colors">
                <i class="fas fa-arrow-left mr-1.5 text-xs"></i>
                Back to Home
            </a>
        </div>
        
        <div class="text-center mb-5">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm">
                <i class="fas fa-user-plus text-white text-lg"></i>
            </div>
            <h1 class="text-xl font-bold text-gray-700">Create Account</h1>
            <p class="text-sm text-gray-600 mt-1">Join our hospital management system</p>
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

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            
            <!-- Name Fields -->
            <div class="grid md:grid-cols-3 gap-3">
                <div>
                    <label for="fname" class="block text-sm font-medium text-gray-700 mb-1.5">First Name *</label>
                    <input type="text" 
                           id="fname" 
                           name="fname" 
                           value="{{ old('fname') }}"
                           required 
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('fname') border-red-500 @enderror"
                           placeholder="First name">
                    @error('fname')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="mname" class="block text-sm font-medium text-gray-700 mb-1.5">Middle Name</label>
                    <input type="text" 
                           id="mname" 
                           name="mname" 
                           value="{{ old('mname') }}"
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('mname') border-red-500 @enderror"
                           placeholder="Middle name">
                    @error('mname')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="lname" class="block text-sm font-medium text-gray-700 mb-1.5">Last Name *</label>
                    <input type="text" 
                           id="lname" 
                           name="lname" 
                           value="{{ old('lname') }}"
                           required 
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('lname') border-red-500 @enderror"
                           placeholder="Last name">
                    @error('lname')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Email and Role -->
            <div class="grid md:grid-cols-2 gap-3">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email Address *</label>
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
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1.5">Role *</label>
                    <select id="role" 
                            name="role" 
                            required 
                            class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('role') border-red-500 @enderror">
                        <option value="">Select your role</option>
                        <option value="patient" {{ old('role') == 'patient' ? 'selected' : '' }}>Patient</option>
                        <option value="doctor" {{ old('role') == 'doctor' ? 'selected' : '' }}>Doctor</option>
                        <option value="nurse" {{ old('role') == 'nurse' ? 'selected' : '' }}>Nurse</option>
                        <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                    </select>
                    @error('role')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Password Fields -->
            <div class="grid md:grid-cols-2 gap-3">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password *</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('password') border-red-500 @enderror"
                           placeholder="Create a password">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Confirm Password *</label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           required
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                           placeholder="Confirm your password">
                </div>
            </div>

            <!-- Contact Information -->
            <div class="grid md:grid-cols-2 gap-3">
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">Phone Number</label>
                    <input type="tel" 
                           id="phone" 
                           name="phone" 
                           value="{{ old('phone') }}"
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('phone') border-red-500 @enderror"
                           placeholder="Phone number">
                    @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1.5">Address</label>
                    <input type="text" 
                           id="address" 
                           name="address" 
                           value="{{ old('address') }}"
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('address') border-red-500 @enderror"
                           placeholder="Your address">
                    @error('address')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Terms and Conditions -->
            <div class="flex items-start">
                <input type="checkbox" 
                       id="terms" 
                       name="terms" 
                       required
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mt-1">
                <label for="terms" class="ml-2 block text-sm text-gray-700">
                    I agree to the <a href="#" class="text-blue-600 hover:text-blue-500">Terms of Service</a> 
                    and <a href="#" class="text-blue-600 hover:text-blue-500">Privacy Policy</a>
                </label>
            </div>

            <button type="submit" 
                    class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-2.5 px-4 rounded-lg font-medium hover:from-blue-700 hover:to-blue-800 shadow-md hover:shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-sm">
                <i class="fas fa-user-plus mr-2"></i>
                Create Account
            </button>
        </form>

        <div class="mt-5 text-center">
            <p class="text-gray-600">
                Already have an account? 
                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-500 font-medium">
                    Sign in here
                </a>
            </p>
        </div>
    </div>
</body>
</html>

