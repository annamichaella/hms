$(document).ready(function() {
    // Load users on page load
    loadUsers();
    loadUserStats();

    // Search functionality
    $('#search-users').on('input', function() {
        const searchTerm = $(this).val();
        const roleFilter = $('#role-filter').val();
        searchUsers(searchTerm, roleFilter);
    });

    // Role filter change
    $('#role-filter').on('change', function() {
        const searchTerm = $('#search-users').val();
        const roleFilter = $(this).val();
        searchUsers(searchTerm, roleFilter);
    });

    // Add new user button
    $('#add-user-btn').on('click', function() {
        showAddUserModal();
    });

    // Handle form submissions
    $('#add-user-form').on('submit', function(e) {
        e.preventDefault();
        addUser();
    });

    $('#edit-user-form').on('submit', function(e) {
        e.preventDefault();
        updateUser();
    });
});

// Load all users
function loadUsers() {
    $.ajax({
        url: '../../actions/admin_users.php',
        type: 'POST',
        data: { get_users: true },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                displayUsers(response.data);
            } else {
                showAlert('error', 'Failed to load users: ' + response.error);
            }
        },
        error: function(xhr, status, error) {
            showAlert('error', 'Error loading users: ' + error);
        }
    });
}

// Load user statistics
function loadUserStats() {
    $.ajax({
        url: '../../actions/admin_users.php',
        type: 'POST',
        data: { get_user_stats: true },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                updateUserStats(response.data);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading user stats:', error);
        }
    });
}

// Search users
function searchUsers(searchTerm, roleFilter) {
    $.ajax({
        url: '../../actions/admin_users.php',
        type: 'POST',
        data: { 
            search_users: true,
            search_term: searchTerm,
            role_filter: roleFilter
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                displayUsers(response.data);
            } else {
                showAlert('error', 'Search failed: ' + response.error);
            }
        },
        error: function(xhr, status, error) {
            showAlert('error', 'Search error: ' + error);
        }
    });
}

// Display users in table
function displayUsers(users) {
    const tbody = $('#users-table tbody');
    tbody.empty();

    if (users.length === 0) {
        tbody.append('<tr><td colspan="4" class="text-center py-4 text-gray-500">No users found</td></tr>');
        return;
    }

    users.forEach(user => {
        const row = `
            <tr class="hover:bg-gray-50" data-user-id="${user.id}">
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                            <span class="text-white text-xs font-medium">${getInitials(user.fname, user.lname)}</span>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">${user.fname} ${user.lname}</div>
                            <div class="text-sm text-gray-500">${user.email}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="inline-flex px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">${user.role}</span>
                </td>
                <td class="px-6 py-4">
                    <span class="inline-flex px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Active</span>
                </td>
                <td class="px-6 py-4">
                    <button class="text-blue-600 hover:text-blue-900 mr-3" onclick="editUser(${user.id})">Edit</button>
                    <button class="text-red-600 hover:text-red-900" onclick="deleteUser(${user.id})">Delete</button>
                </td>
            </tr>
        `;
        tbody.append(row);
    });
}

// Update user statistics
function updateUserStats(stats) {
    if (stats.total_users !== undefined) {
        $('#total-users').text(stats.total_users);
    }
    
    if (stats.users_by_role) {
        Object.keys(stats.users_by_role).forEach(role => {
            const count = stats.users_by_role[role];
            // Update specific role counts if elements exist
            $(`#${role.toLowerCase()}-count`).text(count);
        });
    }
}

// Show add user modal
function showAddUserModal() {
    $('#add-user-modal').removeClass('hidden');
    $('#add-user-form')[0].reset();
}

// Hide add user modal
function hideAddUserModal() {
    $('#add-user-modal').addClass('hidden');
}

// Show edit user modal
function showEditUserModal() {
    $('#edit-user-modal').removeClass('hidden');
}

// Hide edit user modal
function hideEditUserModal() {
    $('#edit-user-modal').addClass('hidden');
}

// Add new user
function addUser() {
    const formData = new FormData($('#add-user-form')[0]);
    formData.append('add_user', true);

    $.ajax({
        url: '../../actions/admin_users.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showAlert('success', response.message);
                hideAddUserModal();
                loadUsers();
                loadUserStats();
            } else {
                showAlert('error', response.error);
            }
        },
        error: function(xhr, status, error) {
            showAlert('error', 'Error adding user: ' + error);
        }
    });
}

// Edit user
function editUser(userId) {
    $.ajax({
        url: '../../actions/admin_users.php',
        type: 'POST',
        data: { 
            get_user: true,
            user_id: userId
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                populateEditForm(response.data);
                showEditUserModal();
            } else {
                showAlert('error', 'Failed to load user: ' + response.error);
            }
        },
        error: function(xhr, status, error) {
            showAlert('error', 'Error loading user: ' + error);
        }
    });
}

// Populate edit form
function populateEditForm(user) {
    $('#edit-user-id').val(user.id);
    $('#edit-fname').val(user.fname);
    $('#edit-mname').val(user.mname || '');
    $('#edit-lname').val(user.lname);
    $('#edit-email').val(user.email);
    $('#edit-role').val(user.role);
    $('#edit-phone').val(user.phone || '');
    $('#edit-address').val(user.address || '');
    $('#edit-specialization').val(user.specialization || '');
    $('#edit-department').val(user.department || '');
}

// Update user
function updateUser() {
    const formData = new FormData($('#edit-user-form')[0]);
    formData.append('update_user', true);

    $.ajax({
        url: '../../actions/admin_users.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showAlert('success', response.message);
                hideEditUserModal();
                loadUsers();
                loadUserStats();
            } else {
                showAlert('error', response.error);
            }
        },
        error: function(xhr, status, error) {
            showAlert('error', 'Error updating user: ' + error);
        }
    });
}

// Delete user
function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        $.ajax({
            url: '../../actions/admin_users.php',
            type: 'POST',
            data: { 
                delete_user: true,
                id: userId
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    loadUsers();
                    loadUserStats();
                } else {
                    showAlert('error', response.error);
                }
            },
            error: function(xhr, status, error) {
                showAlert('error', 'Error deleting user: ' + error);
            }
        });
    }
}

// Utility functions
function getInitials(fname, lname) {
    return (fname.charAt(0) + lname.charAt(0)).toUpperCase();
}

function showAlert(type, message) {
    const alertClass = type === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
    const alertHtml = `
        <div class="fixed top-4 right-4 z-50 p-4 rounded-lg ${alertClass} shadow-lg">
            <div class="flex items-center">
                <span class="mr-2">${type === 'success' ? '✓' : '✗'}</span>
                <span>${message}</span>
                <button class="ml-4 text-gray-500 hover:text-gray-700" onclick="this.parentElement.parentElement.remove()">
                    ×
                </button>
            </div>
        </div>
    `;
    
    $('body').append(alertHtml);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        $('.fixed.top-4.right-4').remove();
    }, 5000);
}

// Close modals when clicking outside
$(document).on('click', function(e) {
    if ($(e.target).hasClass('modal-overlay')) {
        $('.modal').addClass('hidden');
    }
});

// Close modals with escape key
$(document).on('keydown', function(e) {
    if (e.key === 'Escape') {
        $('.modal').addClass('hidden');
    }
});
