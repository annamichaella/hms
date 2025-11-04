@extends('layouts.patient')

@section('title', 'Book Appointment')

@section('content')
<div class="fade-in">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center space-x-4 mb-4">
            <a href="{{ route('patient.appointments') }}" 
               class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-md hover:shadow-lg transition-shadow text-gray-600 hover:text-blue-600">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Book an Appointment</h1>
                <p class="text-gray-600">Schedule a visit with one of our healthcare providers</p>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="patient-card p-6 mb-6 bg-red-50 border-l-4 border-red-400">
            <div class="flex items-start">
                <i class="fas fa-exclamation-circle text-red-600 mr-3 mt-1"></i>
                <div>
                    <h3 class="font-semibold text-red-800 mb-2">Please fix the following errors:</h3>
                    <ul class="list-disc list-inside space-y-1 text-red-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Booking Form -->
    <div class="patient-card p-8">
        <form action="{{ route('patient.appointments.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Doctor Selection -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-2xl border border-blue-100">
                <label class="block text-base font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-user-md text-blue-600 mr-3 text-xl"></i>
                    Select Your Doctor *
                </label>
                <select name="doctor_id" required 
                        class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-white text-gray-900 text-base @error('doctor_id') border-red-400 @enderror">
                    <option value="">Choose a doctor...</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                            Dr. {{ $doctor->full_name }}
                            @if($doctor->specialization) - {{ $doctor->specialization }} @endif
                        </option>
                    @endforeach
                </select>
                @error('doctor_id')
                    <p class="text-red-600 text-sm mt-2 flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                    </p>
                @enderror
                <p class="text-sm text-gray-600 mt-3 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                    Select the healthcare provider you'd like to see
                </p>
            </div>

            <!-- Date and Time -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-2xl border border-green-100">
                    <label class="block text-base font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="far fa-calendar-alt text-green-600 mr-3 text-xl"></i>
                        Appointment Date *
                    </label>
                    <input type="date" name="appointment_date" value="{{ old('appointment_date') }}" required 
                           min="{{ date('Y-m-d') }}" 
                           class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all bg-white text-gray-900 text-base @error('appointment_date') border-red-400 @enderror">
                    @error('appointment_date')
                        <p class="text-red-600 text-sm mt-2 flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                        </p>
                    @enderror
                    <p class="text-sm text-gray-600 mt-3 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-green-500"></i>
                        Select a date from today onwards
                    </p>
                </div>

                <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-6 rounded-2xl border border-purple-100">
                    <label class="block text-base font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="far fa-clock text-purple-600 mr-3 text-xl"></i>
                        Preferred Time *
                    </label>
                    <input type="time" name="appointment_time" value="{{ old('appointment_time') }}" required 
                           class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all bg-white text-gray-900 text-base @error('appointment_time') border-red-400 @enderror">
                    @error('appointment_time')
                        <p class="text-red-600 text-sm mt-2 flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                        </p>
                    @enderror
                    <p class="text-sm text-gray-600 mt-3 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-purple-500"></i>
                        Choose your preferred time slot
                    </p>
                </div>
            </div>

            <!-- Reason for Visit -->
            <div class="bg-gradient-to-r from-amber-50 to-orange-50 p-6 rounded-2xl border border-amber-100">
                <label class="block text-base font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-sticky-note text-amber-600 mr-3 text-xl"></i>
                    Reason for Visit
                </label>
                <textarea name="reason" rows="5" 
                          class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all bg-white text-gray-900 resize-none text-base @error('reason') border-red-400 @enderror"
                          placeholder="Please describe your reason for the appointment (optional, but helpful for your doctor)...">{{ old('reason') }}</textarea>
                @error('reason')
                    <p class="text-red-600 text-sm mt-2 flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                    </p>
                @enderror
                <p class="text-sm text-gray-600 mt-3 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-amber-500"></i>
                    Providing details helps your doctor prepare for your visit
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('patient.appointments') }}" 
                   class="px-6 py-4 text-gray-700 border-2 border-gray-300 rounded-xl font-semibold hover:bg-gray-50 transition-all duration-200 text-center">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit" 
                        class="px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl font-semibold hover:from-blue-700 hover:to-blue-800 shadow-lg hover-soft transition-all duration-200">
                    <i class="fas fa-calendar-check mr-2"></i>Schedule Appointment
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
