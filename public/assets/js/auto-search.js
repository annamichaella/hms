/**
 * Auto Search Component
 * Provides automatic (debounced) search functionality for tables
 */
class AutoSearch {
    constructor(config) {
        this.inputId = config.inputId;
        this.searchUrl = config.searchUrl;
        this.tableBodyId = config.tableBodyId;
        this.debounceDelay = config.debounceDelay || 300;
        this.onSearch = config.onSearch || null;
        this.onError = config.onError || null;
        
        this.debounceTimer = null;
        this.init();
    }
    
    init() {
        const input = document.getElementById(this.inputId);
        if (!input) return;
        
        input.addEventListener('input', (e) => {
            clearTimeout(this.debounceTimer);
            const keyword = e.target.value.trim();
            
            this.debounceTimer = setTimeout(() => {
                this.performSearch(keyword);
            }, this.debounceDelay);
        });
    }
    
    performSearch(keyword) {
        if (!this.searchUrl) return;
        
        const url = this.searchUrl.includes('?') 
            ? `${this.searchUrl}&keyword=${encodeURIComponent(keyword)}`
            : `${this.searchUrl}?keyword=${encodeURIComponent(keyword)}`;
        
        fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && this.onSearch) {
                this.onSearch(data.data);
            }
        })
        .catch(error => {
            console.error('Search error:', error);
            if (this.onError) {
                this.onError(error);
            }
        });
    }
}

// Helper function to render table rows
function renderTableRows(data, renderFunction) {
    const tbody = document.querySelector('tbody');
    if (!tbody || !renderFunction) return;
    
    if (data.length === 0) {
        const colspan = tbody.querySelector('tr')?.querySelectorAll('td').length || 5;
        tbody.innerHTML = `<tr><td colspan="${colspan}" class="px-6 py-4 text-center text-gray-500">No results found</td></tr>`;
        return;
    }
    
    tbody.innerHTML = data.map(renderFunction).join('');
}

