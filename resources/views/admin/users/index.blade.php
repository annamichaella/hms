@extends('layouts.app')

@section('title', 'Users Management')
@section('page-title', 'Users Management')

@section('sidebar')
    @include('partials.admin-sidebar')
@endsection

@section('content')
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold text-gray-800">Users Management</h1>
                <p class="text-gray-600">Manage all system users and their permissions</p>
            </div>
            <button id="add-user-btn" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>Add New User
            </button>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <div class="flex items-center space-x-4">
            <div class="flex items-center flex-1">
                <i class="fas fa-search text-gray-400 mr-3"></i>
                <input type="text" id="search-users" placeholder="Search users by name or email..." 
                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <select id="role-filter" class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Roles</option>
                <option value="admin">Admin</option>
                <option value="doctor">Doctor</option>
                <option value="nurse">Nurse</option>
                <option value="staff">Staff</option>
                <option value="patient">Patient</option>
            </select>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto table-container">
        <table class="w-full min-w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody id="users-table-body" class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $user->full_name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($user->role == 'admin') bg-red-100 text-red-800
                                @elseif($user->role == 'doctor') bg-blue-100 text-blue-800
                                @elseif($user->role == 'nurse') bg-green-100 text-green-800
                                @elseif($user->role == 'staff') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->phone ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="editUser({{ $user->id }})" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                            <button onclick="deleteUser({{ $user->id }})" class="text-red-600 hover:text-red-900">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No users found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <!-- Add/Edit User Modal -->
    <div id="user-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h3 id="modal-title" class="text-lg font-semibold">Add New User</h3>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="user-form" method="POST">
                @csrf
                <input type="hidden" id="user-id" name="id">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                            <input type="text" id="fname" name="fname" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                            <input type="text" id="lname" name="lname" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" id="email" name="email" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password <span id="pwd-required">*</span></label>
                        <input type="password" id="password" name="password" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                        <select id="role" name="role" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="doctor">Doctor</option>
                            <option value="nurse">Nurse</option>
                            <option value="staff">Staff</option>
                            <option value="patient">Patient</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="tel" id="phone" name="phone" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <textarea id="address" name="address" rows="2" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('assets/js/auto-search.js') }}"></script>
    <script>
        // Auto search functionality
        const roleFilter = document.getElementById('role-filter');
        let currentRoleFilter = '';
        
        roleFilter.addEventListener('change', function() {
            currentRoleFilter = this.value;
            performUsersSearch();
        });
        
        function performUsersSearch() {
            const searchTerm = document.getElementById('search-users').value;
            const roleFilter = currentRoleFilter;
            
            let url = '/admin/users/search?';
            if (searchTerm) url += `search_term=${encodeURIComponent(searchTerm)}&`;
            if (roleFilter) url += `role_filter=${encodeURIComponent(roleFilter)}`;
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const tbody = document.getElementById('users-table-body');
                    if (data.data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No users found</td></tr>';
                        return;
                    }
                    
                    tbody.innerHTML = data.data.map(user => {
                        const roleClass = user.role === 'admin' ? 'bg-red-100 text-red-800' :
                                        user.role === 'doctor' ? 'bg-blue-100 text-blue-800' :
                                        user.role === 'nurse' ? 'bg-green-100 text-green-800' :
                                        user.role === 'staff' ? 'bg-yellow-100 text-yellow-800' :
                                        'bg-gray-100 text-gray-800';
                        
                        return `
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">${user.fname} ${user.lname}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">${user.email}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${roleClass}">
                                        ${user.role.charAt(0).toUpperCase() + user.role.slice(1)}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    ${user.phone || 'N/A'}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="editUser(${user.id})" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                                    <button onclick="deleteUser(${user.id})" class="text-red-600 hover:text-red-900" title="Delete User">Delete</button>
                                </td>
                            </tr>
                        `;
                    }).join('');
                }
            });
        }
        
        new AutoSearch({
            inputId: 'search-users',
            searchUrl: '/admin/users/search',
            onSearch: function(data) {
                performUsersSearch();
            }
        });
        
        // Initialize by setting search to POST
        document.getElementById('search-users').addEventListener('input', function() {
            performUsersSearch();
        });
        
        const addBtn = document.getElementById('add-user-btn');
        const modal = document.getElementById('user-modal');
        const form = document.getElementById('user-form');
        const modalTitle = document.getElementById('modal-title');

        addBtn.addEventListener('click', () => {
            modalTitle.textContent = 'Add New User';
            form.reset();
            document.getElementById('user-id').value = '';
            document.getElementById('pwd-required').style.display = 'inline';
            document.getElementById('password').required = true;
            modal.classList.remove('hidden');
        });

        function closeModal() {
            modal.classList.add('hidden');
        }

        function editUser(id) {
            fetch(`/admin/users/${id}`, {
                headers: {'Accept': 'application/json'}
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const user = data.data;
                    modalTitle.textContent = 'Edit User';
                    document.getElementById('user-id').value = user.id;
                    document.getElementById('fname').value = user.fname;
                    document.getElementById('lname').value = user.lname;
                    document.getElementById('email').value = user.email;
                    document.getElementById('role').value = user.role;
                    document.getElementById('phone').value = user.phone || '';
                    document.getElementById('address').value = user.address || '';
                    document.getElementById('pwd-required').style.display = 'none';
                    document.getElementById('password').required = false;
                    modal.classList.remove('hidden');
                }
            });
        }

        function deleteUser(id) {
            confirmAction('Are you sure you want to delete this user? This action cannot be undone.', function() {
                fetch(`/admin/users/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showNotification('User deleted successfully', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showNotification(data.error || 'Failed to delete user', 'error');
                    }
                })
                .catch(error => {
                    showNotification('An error occurred while deleting the user', 'error');
                });
            });
        }

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            const id = document.getElementById('user-id').value;
            const url = id ? `/admin/users/${id}` : '/admin/users';
            const method = id ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showNotification(id ? 'User updated successfully' : 'User created successfully', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification(data.error || 'Operation failed', 'error');
                }
            })
            .catch(error => {
                showNotification('An error occurred. Please try again.', 'error');
            });
        });
    </script>
    @endpush
@endsection
