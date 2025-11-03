// Laravel AJAX Helper
// Automatically includes CSRF token in all AJAX requests

(function() {
    // Get CSRF token from meta tag
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    // Set up jQuery AJAX defaults
    if (typeof jQuery !== 'undefined') {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': token
            }
        });
    }
    
    // Helper function for AJAX requests
    window.laravelAjax = {
        post: function(url, data, callback) {
            if (typeof jQuery !== 'undefined') {
                return $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    dataType: 'json',
                    success: callback
                });
            } else {
                return fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                }).then(response => response.json()).then(callback);
            }
        },
        
        get: function(url, callback) {
            if (typeof jQuery !== 'undefined') {
                return $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: callback
                });
            } else {
                return fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                }).then(response => response.json()).then(callback);
            }
        }
    };
})();
