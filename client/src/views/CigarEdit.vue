<script setup>
    import { onMounted, reactive, ref } from 'vue';
    import { useRoute } from 'vue-router';
    import { useCigarStore } from '@/stores/cigar';

    const route = useRoute();
    const cigar = useCigarStore();

    const cigarInitialized = ref(false);
    const cigarToEdit = ref({});

    const form = reactive({
        name: '',
        ring: '',
        length: '',
        notes: '',
        summary: '',
        rating: 0,
    });

    const initializeForm = () => {
        form.name = cigarToEdit.value.name;
        form.ring = Math.round(cigarToEdit.value.ring * 100) / 100;
        form.length = Math.round(cigarToEdit.value.length * 100) / 100;
        form.notes = cigarToEdit.value.notes;
        form.summary = cigarToEdit.value.summary;
        form.rating = cigarToEdit.value.rating;
    }

    const editCigar = async () => {
        console.log('testing');
    }

    onMounted(async () => {
        try {
            const data = await cigar.getCigar(route.params.cigarId);
            cigarToEdit.value = data;
            initializeForm();
            cigarInitialized.value = true;
        } catch (e) {
            console.log("Error: ", e);
        } 
    });
</script>

<template>
    <div v-if="cigar.error" class="bg-white rounded-md shadow-md p-4 w-[400px] mx-auto">
        <p class="text-red-700">{{ cigar.error }}</p>
    </div>

    <div v-else-if="!cigarInitialized" class="bg-white rounded-md shadow-md p-4 w-[400px] mx-auto">
        <p class="text-sm font-bold">Edit Cigar Log</p>
        <div role="status" class="mt-4 max-w-sm animate-pulse">
            <div class="h-2.5 bg-gray-200 rounded-full dark:bg-gray-700 w-48 mb-4"></div>
            <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 max-w-[360px] mb-2.5"></div>
            <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 mb-2.5"></div>
            <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 max-w-[330px] mb-2.5"></div>
            <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 max-w-[300px] mb-2.5"></div>
            <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 max-w-[360px] mb-2.5"></div>
            <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 max-w-[330px] mb-2.5"></div>
            <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 max-w-[300px] mb-2.5"></div>
            <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 max-w-[360px]"></div>
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <div v-else class="bg-white rounded-md shadow-md p-4 w-[400px] mx-auto">
        <p class="text-sm font-bold">Edit Cigar Log</p>
        <form @submit.prevent="editCigar" class="mt-4">
            <div>
                <input type="text" v-model="form.name" placeholder="Cigar Name" class="w-full p-2 border border-gray-300 rounded-md" />
            </div>
            <div class="mt-4 grid grid-cols-2 gap-4">
                <input type="text" v-model="form.ring" placeholder="Ring Gauge" class="w-full p-2 border border-gray-300 rounded-md" />
                <input type="text" v-model="form.length" placeholder="Length" class="w-full p-2 border border-gray-300 rounded-md" />
            </div>
            <div class="mt-4">
                <input type="text" v-model="form.notes" placeholder="Tasting Notes" class="w-full p-2 border border-gray-300 rounded-md" />
            </div>
            <div class="mt-4">
                <input type="text" v-model="form.summary" placeholder="Overall Summary" class="w-full p-2 border border-gray-300 rounded-md" />
            </div>
            <div class="mt-4">
                <input type="number" min="0" max="100" v-model="form.rating" placeholder="Rating 1-100" class="w-full p-2 border border-gray-300 rounded-md" />
            </div>
            <div class="mt-4">
                <button 
                    type="submit" 
                    :disabled="cigar.loading" 
                    class="py-2 px-4 bg-blue-500 hover:bg-blue-600 transition text-sm text-white font-bold rounded-md w-full"
                >
                    <span v-if="!cigar.loading">Update Cigar Log</span>
                    <span v-if="cigar.loading">Updating Cigar Log...</span>
                </button>
            </div>
        </form>
    </div>
</template>