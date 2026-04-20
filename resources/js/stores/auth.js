import { defineStore } from 'pinia';
import api from '../api';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: JSON.parse(localStorage.getItem('user')) || null,
        token: localStorage.getItem('token') || null,
        loading: false,
        error: null,
    }),
    getters: {
        isAuthenticated: (state) => !!state.token,
        isDoctor: (state) => state.user?.user_type === 'doctor',
        isPatient: (state) => state.user?.user_type === 'patient',
        isAdmin: (state) => state.user?.user_type === 'admin',
        userType: (state) => state.user?.user_type || null,
    },
    actions: {
        async login(credentials) {
            this.loading = true;
            this.error = null;
            try {
                const response = await api.post('/auth/login', credentials);
                this.setUserData(response.data.user, response.data.token);
                return true;
            } catch (err) {
                this.error = err.response?.data?.errors?.phone?.[0] || err.response?.data?.message || 'Login failed';
                return false;
            } finally {
                this.loading = false;
            }
        },
        async register(data) {
            this.loading = true;
            this.error = null;
            try {
                const response = await api.post('/auth/register', data);
                this.setUserData(response.data.user, response.data.token);
                return true;
            } catch (err) {
                const errors = err.response?.data?.errors;
                if (errors) {
                    this.error = Object.values(errors).flat().join(', ');
                } else {
                    this.error = err.response?.data?.message || 'Registration failed';
                }
                return false;
            } finally {
                this.loading = false;
            }
        },
        async logout() {
            try {
                await api.post('/auth/logout');
            } catch (e) {
                // Ignore API failures on logout
            }
            this.clearUserData();
        },
        async fetchUser() {
            try {
                const response = await api.get('/auth/me');
                this.user = response.data;
                localStorage.setItem('user', JSON.stringify(this.user));
            } catch (err) {
                this.clearUserData();
            }
        },
        setUserData(user, token) {
            this.user = user;
            this.token = token;
            localStorage.setItem('user', JSON.stringify(user));
            if (token) {
                localStorage.setItem('token', token);
            }
        },
        clearUserData() {
            this.user = null;
            this.token = null;
            localStorage.removeItem('user');
            localStorage.removeItem('token');
        }
    }
});
