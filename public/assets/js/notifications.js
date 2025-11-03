// Global notification system for success and error messages
function showNotification(message, type = 'success', duration = 5000) {
    // Remove any existing notifications
    const existing = document.getElementById('global-notification');
    if (existing) {
        existing.remove();
    }

    // Create notification element
    const notification = document.createElement('div');
    notification.id = 'global-notification';
    
    const bgColor = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 
                   type === 'error' ? 'bg-red-100 border-red-400 text-red-700' :
                   type === 'warning' ? 'bg-yellow-100 border-yellow-400 text-yellow-700' :
                   'bg-blue-100 border-blue-400 text-blue-700';
    
    notification.className = `fixed top-4 right-4 ${bgColor} border px-6 py-4 rounded-lg shadow-lg z-50 flex items-center space-x-3 min-w-80 max-w-md`;
    
    const icon = type === 'success' ? '<i class="fas fa-check-circle"></i>' :
                type === 'error' ? '<i class="fas fa-exclamation-circle"></i>' :
                type === 'warning' ? '<i class="fas fa-exclamation-triangle"></i>' :
                '<i class="fas fa-info-circle"></i>';
    
    notification.innerHTML = `
        <div class="text-xl">${icon}</div>
        <div class="flex-1">
            <p class="font-medium">${message}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after duration
    setTimeout(() => {
        if (notification.parentElement) {
            notification.style.opacity = '0';
            notification.style.transition = 'opacity 0.3s';
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        }
    }, duration);
}

// Enhanced confirmation dialog
function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

