<script setup>
    import { RouterLink, useRouter } from 'vue-router'
    import { useAuthStore } from '@/stores/auth'

    const router = useRouter();
    const auth = useAuthStore();

    const logout = async () => {
        const logoutError = await auth.logout();

        if (!logoutError) {
            router.push({ name: 'home' });
        }
    };
</script>

<template>
    <header>
        <div class="max-w-7xl mx-auto p-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <img src="@/assets/cigar.svg" class="w-8 h-8 mr-2" />
                    <h2 class="font-medium">CigarBuddy</h2>
                </div>
                <nav class="flex gap-2 items-center text-sm">
                    <RouterLink v-if="auth.isAuthenticated" to="/dashboard">Dashboard</RouterLink>
                    <p v-if="auth.isAuthenticated">|</p>
                    <RouterLink to="/image/general/upload">Upload</RouterLink>
                    <p>|</p>
                    <RouterLink v-if="!auth.isAuthenticated" to="/login">Login</RouterLink>
                    <button type="button" v-if="auth.isAuthenticated" @click="logout">Logout</button>
                    <p v-if="!auth.isAuthenticated">|</p>
                    <RouterLink v-if="!auth.isAuthenticated" to="/register">Register</RouterLink>
                </nav>
            </div>
        </div>
    </header>
</template>