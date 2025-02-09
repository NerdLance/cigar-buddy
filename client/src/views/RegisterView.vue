<script setup>
    import { useRouter, RouterLink } from 'vue-router';
    import { useAuthStore } from '@/stores/auth';
    import { reactive, ref } from 'vue';

    const router = useRouter();
    const auth = useAuthStore();

    if (auth.isAuthenticated) {
        router.push({ name: 'dashboard' });
    }

    const form = reactive({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });
    
    const loading = ref(false);
    const registerError = ref(null);

    const submitRegister = async () => {
        loading.value = true;
        registerError.value = null;

        const error = await auth.register(form);

        if (error) {
            loading.value = false;
            registerError.value = error;
        } else {
            router.push({ name: 'dashboard' });
        }
    };
</script>

<template>
    <div v-if="auth.isAuthenticated">
        Hello {{ auth.currentUser.name }}!
    </div>

    <div class="bg-white rounded-md p-4 shadow-md w-[400px] mx-auto">
        <p class="text-sm font-bold">Create a CigarBuddy Account</p>
        <div v-if="registerError" class="mt-4">
            <p class="text-red-700 text-sm">{{ registerError }}</p>
        </div>
        <form v-on:submit.prevent="submitRegister" class="mt-4">
            <div>
                <input type="text" v-model="form.name" placeholder="Enter your name" class="border rounded-md p-2 border-gray-300 w-full" />
            </div>
            <div class="mt-4">
                <input type="email" v-model="form.email" placeholder="Enter your email" class="border rounded-md p-2 border-gray-300 w-full" />
            </div>
            <div class="mt-4">
                <input type="password" v-model="form.password" placeholder="Enter your password" class="border rounded-md p-2 border-gray-300 w-full" />
            </div>
            <div class="mt-4">
                <input type="password" v-model="form.password_confirmation" placeholder="Enter your password" class="border rounded-md p-2 border-gray-300 w-full" />
            </div>
            <div class="mt-4">
                <button class="w-full bg-blue-500 hover:bg-blue-600 transition py-2 px-4 text-white text-sm font-bold rounded-md" type="submit">
                    <span v-if="loading">Creating Account...</span>
                    <span v-if="!loading">Create Account</span>
                </button>
            </div>
        </form>
        <div class="mt-2">
            <p class="text-sm underline text-gray-700 text-center"><RouterLink to="/login">Login to My Account</RouterLink></p>
        </div>
    </div>
</template>