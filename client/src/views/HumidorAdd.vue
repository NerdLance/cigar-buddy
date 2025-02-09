<script setup>
    import { useHumidorStore } from '@/stores/humidor';
    import { ref, reactive } from 'vue';

    const humidorStore = useHumidorStore();

    const humidorAddSuccess = ref(false);

    const form = reactive({
        name: '',
        description: '',
        humidity: '',
    });

    const resetForm = () => {
        form.name = '';
        form.description = '';
        form.humidity = '';
    }

    const submitAddHumidor = async () => {
        await humidorStore.addHumidor(form);

        if (!humidorStore.isError) {
            resetForm();
            humidorAddSuccess.value = true;
        }
    }
</script>

<template>
    <div class="bg-white w-[400px] mx-auto rounded-md shadow-md p-4">
        <h3 class="text-sm font-bold">Add a Humidor</h3>
        <div class="mt-4">
            <form @submit.prevent="submitAddHumidor">
                <div>
                    <input type="text" v-model="form.name" placeholder="Humidor Name" class="w-full p-2 border border-gray-300 rounded-md" />
                </div>
                <div class="mt-4">
                    <input type="text" v-model="form.description" placeholder="Humidor Description" class="w-full p-2 border border-gray-300 rounded-md" />
                </div>
                <div class="mt-4">
                    <input type="number" min="0" max="100" v-model="form.humidity" placeholder="Resting Humidity (%)" class="w-full p-2 border border-gray-300 rounded-md" />
                </div>
                <div class="mt-4">
                    <button type="submit" class="w-full py-2 px-4 bg-blue-500 hover:bg-blue-600 transition text-white text-sm font-bold rounded-md">
                        <span v-if="humidorStore.isLoading">Adding Humidor...</span>
                        <span v-if="!humidorStore.isLoading">Add Humidor</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>