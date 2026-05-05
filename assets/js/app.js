/**
 * KickTime - Core Application Logic
 * Refactored for maximum stability and predictable initialization.
 */

// Global State
const getStoredUser = () => {
    try {
        const val = localStorage.getItem('user');
        if (!val || val === 'undefined' || val === 'null') {
            localStorage.removeItem('user');
            return null;
        }
        return JSON.parse(val);
    } catch (e) {
        localStorage.removeItem('user');
        return null;
    }
};

const state = {
    user: getStoredUser(),
    lang: window.lang || localStorage.getItem('lang') || 'ar',
    darkMode: localStorage.getItem('darkMode') !== null ? localStorage.getItem('darkMode') === 'true' : window.matchMedia('(prefers-color-scheme: dark)').matches,
    isLoading: false,
    translations: { ar: {}, en: {} }
};

// Application Object
const app = {
    /**
     * Initialization - Runs on DOMContentLoaded
     */
    init: async () => {
        console.log('KickTime: Initializing...');
        
        // 1. Apply initial theme and language classes
        document.documentElement.classList.toggle('dark-mode', state.darkMode);
        if (document.body) {
            document.body.classList.toggle('dark-mode', state.darkMode);
            document.body.classList.toggle('lang-en', state.lang === 'en');
        }
        
        // 2. Load translations
        await app.loadTranslations();
        
        // 3. Setup core containers
        app.setupContainers();
        
        // 4. Attach global listeners
        app.attachGlobalListeners();
        
        // 5. Initial render and broadcast
        app.translatePage();
        app.broadcastAuthState();
        
        console.log('KickTime: Initialization complete.');
    },

    loadTranslations: async () => {
        const root = (window.root && window.root !== 'null' && window.root !== 'undefined') ? window.root : './';
        try {
            const [arRes, enRes] = await Promise.all([
                fetch(root + 'assets/locales/ar.json'),
                fetch(root + 'assets/locales/en.json')
            ]);
            if (!arRes.ok || !enRes.ok) throw new Error('Translation files not found');
            state.translations.ar = await arRes.json();
            state.translations.en = await enRes.json();
            return true;
        } catch (e) {
            console.warn('KickTime: Failed to load external translations:', e);
            return false;
        }
    },

    setupContainers: () => {
        // Toast Container
        if (!document.getElementById('toast-box')) {
            const toastBox = document.createElement('div');
            toastBox.id = 'toast-box';
            toastBox.className = 'toast-container';
            document.body.appendChild(toastBox);
        }

        // Modals Container
        const modalsContainer = document.getElementById('modals-container');
        if (modalsContainer && window.Components) {
            modalsContainer.innerHTML = Components.LoginModal() + Components.RegisterModal();
        }

        // Chatbot removed - system stabilization
    },

    attachGlobalListeners: () => {
        // Dropdown Click-Outside
        document.addEventListener('click', (e) => {
            const dropdown = document.getElementById('userDropdown');
            const trigger = document.querySelector('.user-profile-trigger');
            if (dropdown && trigger && !trigger.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.remove('active');
            }
        });

        // Header Scroll Effect
        window.addEventListener('scroll', () => {
            const header = document.querySelector('.header');
            if (header) {
                header.classList.toggle('scrolled', window.scrollY > 50);
            }
        }, { passive: true });

        // Mobile Menu Backdrop Close
        const backdrop = document.getElementById('menuBackdrop');
        if (backdrop) {
            backdrop.addEventListener('click', () => app.toggleMobileMenu(false));
        }

        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
            if (localStorage.getItem('darkMode') === null) {
                app.toggleDarkMode(e.matches, true);
            }
        });
    },

    broadcastAuthState: () => {
        document.dispatchEvent(new CustomEvent('authStateChanged', { detail: { user: state.user } }));
        app.translatePage();
    },

    toggleLanguage: () => {
        const newLang = state.lang === 'en' ? 'ar' : 'en';
        state.lang = newLang;
        localStorage.setItem('lang', newLang);
        
        // Sync with backend session and reload
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('lang', newLang);
        window.location.href = currentUrl.toString();
    },

    toggleDarkMode: (force, silent = false) => {
        state.darkMode = force !== undefined ? force : !state.darkMode;
        localStorage.setItem('darkMode', state.darkMode);
        document.documentElement.classList.toggle('dark-mode', state.darkMode);
        if (document.body) document.body.classList.toggle('dark-mode', state.darkMode);
        
        // Refresh UI elements that might need re-rendering
        const navActions = document.getElementById('navActions');
        if (navActions && typeof renderNavbar === 'function') {
            renderNavbar();
        }
        
        if (!silent) {
            const msg = state.darkMode ? 
                (state.lang === 'en' ? 'Dark mode enabled' : 'تم تفعيل الوضع الليلي') : 
                (state.lang === 'en' ? 'Dark mode disabled' : 'تم تعطيل الوضع الليلي');
            app.toast(msg);
        }
    },

    translatePage: () => {
        const lang = state.lang;
        document.documentElement.dir = lang === 'en' ? 'ltr' : 'rtl';
        document.documentElement.lang = lang;
        
        document.querySelectorAll('[data-i18n]').forEach(el => {
            const key = el.getAttribute('data-i18n');
            const translation = state.translations[lang]?.[key];

            if (translation) {
                if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
                    if (el.hasAttribute('placeholder')) el.placeholder = translation;
                    else el.value = translation;
                } else {
                    el.innerHTML = translation;
                }
            }
        });
    },

    openLoginModal: () => {
        const modal = document.getElementById('loginModal');
        if (modal) modal.classList.add('active');
        else console.warn('KickTime: Login modal not found');
    },

    openRegisterModal: () => {
        const modal = document.getElementById('registerModal');
        if (modal) modal.classList.add('active');
        else console.warn('KickTime: Register modal not found');
    },

    closeModals: () => {
        document.querySelectorAll('.modal-overlay').forEach(m => m.classList.remove('active'));
    },

    toggleMobileMenu: (show) => {
        const menu = document.getElementById('mobileMenu');
        const backdrop = document.getElementById('menuBackdrop');
        if (!menu || !backdrop) return;

        menu.classList.toggle('active', show);
        backdrop.classList.toggle('active', show);
        document.body.style.overflow = show ? 'hidden' : '';
    },

    toast: (msg, type = 'success') => {
        const box = document.getElementById('toast-box');
        if (!box) return;
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `<span>${msg}</span>`;
        box.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 500);
        }, 3000);
    },

    showLoading: (show) => {
        state.isLoading = show;
        let overlay = document.getElementById('global-spinner');
        if (show && !overlay) {
            overlay = document.createElement('div');
            overlay.id = 'global-spinner';
            overlay.className = 'spinner-overlay';
            overlay.innerHTML = '<div class="spinner"></div>';
            document.body.appendChild(overlay);
        }
        if (overlay) overlay.style.display = show ? 'flex' : 'none';
    },

    handleLogin: async (e) => {
        e.preventDefault();
        app.showLoading(true);
        const email = document.getElementById('loginEmail')?.value;
        const password = document.getElementById('loginPassword')?.value;
        
        try {
            const result = await api.auth.login(email, password);

            if (result.data && result.data.user) {
                state.user = result.data.user;
                localStorage.setItem('user', JSON.stringify(result.data.user));
                app.closeModals();
                app.broadcastAuthState();
                app.toast(state.lang === 'en' ? 'Welcome back!' : 'مرحباً بك مجدداً!');
                setTimeout(() => location.reload(), 500);
            } else {
                app.toast(result.message || 'Login failed', 'error');
            }
        } catch (error) {
            app.toast(error.message || 'Server connection failed', 'error');
        } finally {
            app.showLoading(false);
        }
    },

    handleRegister: async (e) => {
        e.preventDefault();
        app.showLoading(true);
        const name = document.getElementById('regName')?.value;
        const email = document.getElementById('regEmail')?.value;
        const password = document.getElementById('regPassword')?.value;
        const role = document.getElementById('regRole')?.value || 'user';

        try {
            const result = await api.auth.register(name, email, password, role);

            if (result.success || result.data) {
                app.closeModals();
                app.toast(state.lang === 'en' ? 'Account created! Please log in.' : 'تم إنشاء الحساب! سجل الدخول.');
                setTimeout(() => app.openLoginModal(), 1000);
            } else {
                app.toast(result.message || 'Registration failed', 'error');
            }
        } catch (error) {
            app.toast(error.message || 'Server connection failed', 'error');
        } finally {
            app.showLoading(false);
        }
    },

    logout: () => {
        localStorage.removeItem('user');
        state.user = null;
        app.broadcastAuthState();
        location.href = (window.root || './') + 'auth/logout.php';
    },

    handleBookingClick: (stadiumId) => {
        if (!state.user) {
            app.toast(state.lang === 'en' ? 'Please login first' : 'يرجى تسجيل الدخول أولاً', 'error');
            app.openLoginModal();
            return;
        }
        location.href = (window.root || './') + 'user/book.php?id=' + stadiumId;
    },

    openVisaModal: () => {
        const modal = document.getElementById('visaModal');
        if (modal) modal.classList.add('active');
        else app.toast(state.lang === 'en' ? 'Visa modal not found' : 'لم يتم العثور على نافذة الفيزا', 'error');
    }
};

// Immediate Global Exposure
window.app = app;
window.state = state;

// Initialize on DOM Ready
document.addEventListener('DOMContentLoaded', app.init);
