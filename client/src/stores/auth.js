import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import { useCigarStore } from './cigar';

import axios from 'axios';

export const useAuthStore = defineStore('auth', () => {
    const user = ref({});
    const authenticated = ref(false);
    const isAuthResolved = ref(false);

    const isAuthenticated = computed(() => authenticated.value);
    const currentUser = computed(() => user.value);

    const setAuthenticated = (value) => {
        authenticated.value = value;
    }

    const setUser = (userData) => {
        user.value = userData;
    }

    const login = async (credentials) => {
        await axios.get('/sanctum/csrf-cookie');

        try {
            await axios.post('/login', credentials);
            await attempt();

            return null;
        } catch (e) {
            console.error('Login failed', e);

            if (e.response && e.response.data) {
                return e.response.data.message || "Login failed. Please try again.";
            } else {
                return "An unexpected error occurred. Please try again.";
            }
        }
    }

    const register = async (credentials) => {
        await axios.get('/sanctum/csrf-cookie');

        try {
            await axios.post('/register', credentials);
            await attempt();

            return null;
        } catch (e) {
            console.error('Registration failed', e);

            if (e.response && e.response.data) {
                return e.response.data.message || "Registration failed. Please try again.";
            } else {
                return "An unexpected error occurred. Please try again.";
            }
        }
    }

    const logout = async () => {
        try {
            await axios.post('/logout');

            const cigarStore = useCigarStore();
            cigarStore.resetStore();
            
            setUser({});
            setAuthenticated(false);
            return null;
        } catch (e) {
            console.error("Logout failed", e);
            return "Logout failed";
        }
    }

    const attempt = async () => {
        try {
            let response = await axios.get('/api/user');
            console.log(response);
            setUser(response.data);
            setAuthenticated(true);

            return response;
        } catch (e) {
            setUser({});
            setAuthenticated(false);
        } finally {
            isAuthResolved.value = true;
        }
    }

    return {
        currentUser,
        isAuthenticated,
        isAuthResolved,
        login,
        register,
        logout,
        attempt,
    };
});