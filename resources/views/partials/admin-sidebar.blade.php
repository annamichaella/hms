@php
    $currentRoute = request()->route()->getName();
    $isActive = function($route) use ($currentRoute) {
        return strpos($currentRoute, $route) === 0 
            ? 'text-blue-700 bg-gradient-to-r from-blue-50 to-blue-100 border-l-3 border-blue-600 font-medium shadow-sm' 
            : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900';
    };
@endphp

<div class="px-2 space-y-0.5">
    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm {{ $isActive('admin.dashboard') }} rounded-md transition-all duration-200 group">
        <i class="fas fa-home mr-2.5 text-sm w-4 text-center"></i> Dashboard
    </a>
    <a href="{{ route('admin.users.index') }}" class="flex items-center px-3 py-2.5 text-sm {{ $isActive('admin.users') }} rounded-md transition-all duration-200 group">
        <i class="fas fa-users mr-2.5 text-sm w-4 text-center"></i> Users
    </a>
    <a href="{{ route('admin.records.index') }}" class="flex items-center px-3 py-2.5 text-sm {{ $isActive('admin.records') }} rounded-md transition-all duration-200 group">
        <i class="fas fa-user-injured mr-2.5 text-sm w-4 text-center"></i> Patient Records
    </a>
    <a href="{{ route('admin.appointments.index') }}" class="flex items-center px-3 py-2.5 text-sm {{ $isActive('admin.appointments') }} rounded-md transition-all duration-200 group">
        <i class="fas fa-calendar-check mr-2.5 text-sm w-4 text-center"></i> Appointments
    </a>
    <a href="{{ route('admin.wards.index') }}" class="flex items-center px-3 py-2.5 text-sm {{ $isActive('admin.wards') }} rounded-md transition-all duration-200 group">
        <i class="fas fa-bed mr-2.5 text-sm w-4 text-center"></i> Wards
    </a>
    <a href="{{ route('admin.billings.index') }}" class="flex items-center px-3 py-2.5 text-sm {{ $isActive('admin.billings') }} rounded-md transition-all duration-200 group">
        <i class="fas fa-credit-card mr-2.5 text-sm w-4 text-center"></i> Billing
    </a>
</div>

<style>
.border-l-3 {
    border-left-width: 3px;
}
</style>

