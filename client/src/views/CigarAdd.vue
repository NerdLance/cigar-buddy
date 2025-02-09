<script setup>
    import { reactive, ref } from 'vue';
    import { useCigarStore } from "@/stores/cigar";

    const cigar = useCigarStore();

    cigar.getCigars();

    const form = reactive({
        name: '',
        ring: '',
        length: '',
        notes: '',
        summary: '',
        rating: 0,
    });

    const cigarAddSuccess = ref(false);

    const addCigar = async () => {
        await cigar.addCigar(form);

        if (!cigar.error) {
            cigarAddSuccess.value = true;
            form.name = '';
            form.ring = '';
            form.length = '';
            form.notes = '';
            form.summary = '';
            form.rating = 0;
        }
    }
</script>

<template>
    <div v-if="cigarAddSuccess" class="mb-4 w-[400px] mx-auto bg-blue-500 text-white font-bold text-center text-sm p-4 rounded-md">
        <p>Cigar Log Successfully Added!</p>
    </div>
    <div class="bg-white rounded-md shadow-md p-4 w-[400px] mx-auto">
        <p class="text-sm font-bold">Add a Cigar Log</p>
        <form @submit.prevent="addCigar" class="mt-4">
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
                    <span v-if="!cigar.loading">Add Cigar Log</span>
                    <span v-if="cigar.loading">Adding Cigar Log...</span>
                </button>
            </div>
        </form>
    </div>
    <div class="mt-4 w-[400px] mx-auto">
        <p class="text-sm text-center font-bold">Recent Cigar Logs</p>
        <div class="mt-4 grid grid-cols-1 gap-4">
            <div v-for="stogie in cigar.myCigars" class="bg-white rounded-md p-4 shadow-md">
                <p class="text-sm font-bold">{{ stogie.name }}</p>
                <p class="mt-2 text-sm">{{ stogie.summary }}</p>
                <p class="mt-4 text-xs text-right">My Rating: {{ stogie.rating }}/100</p>
            </div>
        </div>
    </div>
</template>