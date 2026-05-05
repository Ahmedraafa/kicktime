// frontend/js/api.js
// All API calls use text() then JSON.parse() to prevent "Unexpected end of JSON input" errors

const getBaseUrl = () => {
    if (window.apiRoot && window.apiRoot !== 'null' && window.apiRoot !== 'undefined') return window.apiRoot;
    
    // Use window.root as the base for finding backend
    const base = (window.root && window.root !== 'null' && window.root !== 'undefined') ? window.root : './';
    
    // If we are in a subdirectory like owner/, admin/, or user/
    const path = window.location.pathname;
    if (path.includes('/owner/') || path.includes('/admin/') || path.includes('/user/')) {
        return '../backend/';
    }
    
    // If window.root is available, backend should be relative to it
    if (window.root && window.root.startsWith('/')) {
        return window.root + '../backend/';
    }
    
    return base + 'backend/';
};

const api = {
    auth: {
        async login(email, password) {
            const baseUrl = getBaseUrl();
            const response = await fetch(baseUrl + 'api/auth/login.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, password }),
                credentials: 'include'
            });
            const text = await response.text();
            let data;
            try {
                data = JSON.parse(text);
            } catch (e) {
                console.error('Invalid JSON response from login API:', text);
                throw new Error('Invalid server response. Please try again.');
            }
            if (!response.ok) throw new Error(data.message || 'Login failed');
            return data;
        },
        async register(name, email, password, role, phone) {
            const baseUrl = getBaseUrl();
            const response = await fetch(baseUrl + 'api/auth/register.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ name, email, password, role, phone }),
                credentials: 'include'
            });
            const text = await response.text();
            let data;
            try {
                data = JSON.parse(text);
            } catch (e) {
                console.error('Invalid JSON response from register API:', text);
                throw new Error('Invalid server response. Please try again.');
            }
            if (!response.ok) throw new Error(data.message || 'Registration failed');
            return data;
        }
    },

    stadiums: {
        async getAll() {
            const baseUrl = getBaseUrl();
            const response = await fetch(baseUrl + 'api/stadiums/index.php', { credentials: 'include' });
            const text = await response.text();
            let data;
            try {
                data = JSON.parse(text);
            } catch (e) {
                console.error('Invalid JSON response from stadiums API:', text);
                throw new Error('Failed to fetch stadiums');
            }
            if (!response.ok) throw new Error(data.message || 'Failed to fetch stadiums');
            return data;
        },
        async getById(id) {
            const baseUrl = getBaseUrl();
            const response = await fetch(`${baseUrl}api/stadiums/index.php?id=${id}`, { credentials: 'include' });
            const text = await response.text();
            let data;
            try {
                data = JSON.parse(text);
            } catch (e) {
                console.error('Invalid JSON response from stadiums API:', text);
                throw new Error('Failed to fetch stadium');
            }
            if (!response.ok) throw new Error(data.message || 'Failed to fetch stadium');
            return data;
        },
        async getByOwner(ownerId) {
            const baseUrl = getBaseUrl();
            const response = await fetch(`${baseUrl}api/stadiums/owner.php?owner_id=${ownerId}`, { credentials: 'include' });
            const text = await response.text();
            let data;
            try {
                data = JSON.parse(text);
            } catch (e) {
                console.error('Invalid JSON response from stadiums API:', text);
                throw new Error('Failed to fetch owner stadiums');
            }
            if (!response.ok) throw new Error(data.message || 'Failed to fetch owner stadiums');
            return data;
        }
    },

    bookings: {
        async create(data) {
            const baseUrl = getBaseUrl();
            const user = JSON.parse(localStorage.getItem('user'));
            if (user) data.userId = user.id;
            
            const response = await fetch(baseUrl + 'api/bookings/index.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data),
                credentials: 'include'
            });
            const text = await response.text();
            let result;
            try {
                result = JSON.parse(text);
            } catch (e) {
                console.error('Invalid JSON response from bookings API:', text);
                throw new Error('Invalid server response');
            }
            if (!response.ok) throw new Error(result.message || 'Booking failed');
            return result;
        },
        async getAvailability(stadiumId, date) {
            const baseUrl = getBaseUrl();
            const response = await fetch(`${baseUrl}api/bookings/availability.php?stadium_id=${stadiumId}&date=${date}`, { credentials: 'include' });
            const text = await response.text();
            let data;
            try {
                data = JSON.parse(text);
            } catch (e) {
                console.error('Invalid JSON response from availability API:', text);
                throw new Error('Failed to fetch availability');
            }
            if (!response.ok) throw new Error(data.message || 'Failed to fetch availability');
            return data;
        },
        async getUserBookings(userId) {
            if (!userId) {
                const user = JSON.parse(localStorage.getItem('user'));
                if (!user) return { records: [] };
                userId = user.id;
            }
            const baseUrl = getBaseUrl();
            const response = await fetch(`${baseUrl}api/bookings/index.php?user_id=${userId}`, { credentials: 'include' });
            const text = await response.text();
            let data;
            try {
                data = JSON.parse(text);
            } catch (e) {
                console.error('Invalid JSON response from bookings API:', text);
                throw new Error('Failed to fetch bookings');
            }
            if (!response.ok) throw new Error(data.message || 'Failed to fetch bookings');
            return data;
        }
    },

    // Generic helper methods
    async get(endpoint) {
        const baseUrl = getBaseUrl();
        const response = await fetch(`${baseUrl}api/${endpoint}`);
        const text = await response.text();
        let data;
        try {
            data = JSON.parse(text);
        } catch (e) {
            console.error('Invalid JSON response:', text);
            throw new Error('Invalid server response');
        }
        if (!response.ok) throw new Error(data.message || 'Request failed');
        return data;
    },
    async post(endpoint, body) {
        const baseUrl = getBaseUrl();
        const response = await fetch(`${baseUrl}api/${endpoint}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(body)
        });
        const text = await response.text();
        let data;
        try {
            data = JSON.parse(text);
        } catch (e) {
            console.error('Invalid JSON response:', text);
            throw new Error('Invalid server response');
        }
        if (!response.ok) throw new Error(data.message || 'Request failed');
        return data;
    }
};


