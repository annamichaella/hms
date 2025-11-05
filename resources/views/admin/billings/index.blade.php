@extends('layouts.app')

@section('title', 'Billing')
@section('page-title', 'Billing Management')

@section('sidebar')
    @include('partials.admin-sidebar')
@endsection

@section('content')
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold text-gray-800">Billing Management</h1>
                <p class="text-gray-600">Manage patient billing and invoices</p>
            </div>
            <a href="{{ route('admin.billings.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i>New Bill
            </a>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <div class="search-bar">
            <i class="fas fa-search"></i>
            <input type="text" id="search-billings" placeholder="Search by patient name, service, or amount..." class="search-input">
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto table-container">
        <table class="w-full min-w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($bills as $bill)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $bill->patient_name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $bill->service }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            ₱{{ number_format($bill->amount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $bill->billing_date->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($bill->status == 'paid') bg-green-100 text-green-800
                                @elseif($bill->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($bill->status == 'partial') bg-blue-100 text-blue-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($bill->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.billings.show', $bill) }}" class="text-blue-600 hover:text-blue-900">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No bills found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('assets/js/auto-search.js') }}"></script>
    <script>
        new AutoSearch({
            inputId: 'search-billings',
            searchUrl: '/admin/billings/search',
            onSearch: function(data) {
                const tbody = document.querySelector('tbody');
                if (data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No bills found</td></tr>';
                    return;
                }
                
                tbody.innerHTML = data.map(bill => {
                    const statusClass = bill.status === 'paid' ? 'bg-green-100 text-green-800' :
                                       bill.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                       bill.status === 'partial' ? 'bg-blue-100 text-blue-800' :
                                       'bg-red-100 text-red-800';
                    const date = new Date(bill.billing_date);
                    const amount = parseFloat(bill.amount).toFixed(2);
                    
                    return `
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">${bill.patient_name || 'N/A'}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${bill.service || 'N/A'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">₱${amount}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                ${date.toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'})}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                                    ${bill.status.charAt(0).toUpperCase() + bill.status.slice(1)}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="/admin/billings/${bill.id}" class="text-blue-600 hover:text-blue-900">View</a>
                            </td>
                        </tr>
                    `;
                }).join('');
            }
        });
    </script>
    @endpush
@endsection
