<script setup>
    import { ref, onMounted, reactive, watch } from 'vue';
    import { useRoute } from 'vue-router';
    import { useHumidorStore } from '@/stores/humidor';
    import { useHumidorCigarStore } from '@/stores/humidorCigar';

    const route = useRoute();
    const humidorStore = useHumidorStore();
    const humidorCigarStore = useHumidorCigarStore();

    const showAddCigarsForm = ref(false);
    const loadedInitialCigars = ref(false);

    const humidorContents = ref(null);

    const addCigarsForm = reactive({
        name: '',
        ring: '',
        length: '',
        quanity: '',
        price: '',
    });

    const resetForm = () => {
        addCigarsForm.name = '';
        addCigarsForm.ring = '';
        addCigarsForm.length = '';
        addCigarsForm.quantity = '';
        addCigarsForm.price = '';
    }

    const toggleShowAddCigarsForm = () => {
        showAddCigarsForm.value = !showAddCigarsForm.value;
    }

    const addCigars = async () => {
        await humidorCigarStore.addHumidorCigar(route.params.humidorId, addCigarsForm);

        if (!humidorCigarStore.isError) {
            resetForm();
        }
    }

    onMounted(async () => {
        humidorContents.value = await humidorStore.getHumidor(route.params.humidorId);
        await humidorCigarStore.getHumidorCigars(route.params.humidorId);
        loadedInitialCigars.value = true;
    });
</script>

<template>
    <div v-if="humidorStore.isLoading" class="bg-white rounded-md shadow-md p-4">
        <div role="status" class="max-w-sm animate-pulse">
            <div class="h-2.5 bg-gray-200 rounded-full dark:bg-gray-700 w-48 mb-4"></div>
            <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 max-w-[360px] mb-2.5"></div>
            <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700"></div>
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <div v-if="humidorContents">
        <div class="bg-white rounded-md shadow-md p-4">
            <div class="grid grid-cols-4 gap-4">
                <div class="col-span-3">
                    <p>{{ humidorContents.name }}</p>
                    <p class="mt-2 text-gray-700 text-sm">{{ humidorContents.description }}</p>
                    <div class="mt-2">
                        <button @click="toggleShowAddCigarsForm" class="underline text-sm text-gray-700">
                            <span v-if="showAddCigarsForm">Hide Add Cigars Form</span>
                            <span v-else>Add Cigars</span>
                        </button>
                    </div>
                </div>
                <div class="col-span-1">
                    <div>
                        <p class="text-right text-xs uppercase text-gray-700">Cigars</p>
                        <h4 class="text-right text-2xl font-bold">{{ humidorContents.humidor_cigars_count }}</h4>
                    </div>
                    <div class="mt-2">
                        <p class="text-right text-xs uppercase text-gray-700">Value</p>
                        <h4 class="text-right text-2xl font-bold">${{ humidorContents.total_value }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="showAddCigarsForm" class="mt-4 bg-white rounded-md shadow-md p-4">
            <p class="font-bold text-sm">Add Cigar(s) to Humidor</p>
            <div class="mt-4">
                <form @submit.prevent="addCigars">
                    <div class="">
                        <input type="text" v-model="addCigarsForm.name" placeholder="Cigar Name" class="w-full p-2 border border-gray-300 rounded-md" />
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-4">
                        <input type="text" v-model="addCigarsForm.length" placeholder="Length" class="w-full p-2 border border-gray-300 rounded-md" />
                        <input type="text" v-model="addCigarsForm.ring" placeholder="Ring Gauge" class="w-full p-2 border border-gray-300 rounded-md" />
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-4">
                        <input type="text" v-model="addCigarsForm.price" placeholder="Price Per Cigar" class="w-full p-2 border border-gray-300 rounded-md" />
                        <input type="text" v-model="addCigarsForm.quantity" placeholder="Quantity" class="w-full p-2 border border-gray-300 rounded-md" />
                    </div>
                    <div class="mt-4 md:flex md:justify-end">
                        <button 
                            type="submit" 
                            class="py-2 px-4 w-full md:w-auto text-white text-sm font-bold bg-blue-500 hover:bg-blue-600 transition rounded-md"
                        >
                            <span v-if="loadedInitialCigars && humidorCigarStore.isLoading">Adding Cigars...</span>
                            <span v-else>Add Cigar</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div v-if="!loadedInitialCigars" class="mt-4 bg-white rounded-md shadow-md p-4">
        <div role="status" class="max-w-sm animate-pulse">
            <div class="h-2.5 bg-gray-200 rounded-full dark:bg-gray-700 w-48 mb-4"></div>
            <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 max-w-[360px] mb-2.5"></div>
            <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700"></div>
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <div v-if="loadedInitialCigars && (!humidorCigarStore.myHumidorCigars[route.params.humidorId] || !humidorCigarStore.myHumidorCigars[route.params.humidorId].length)" class="mt-4 bg-white rounded-md shadow-md p-4">
        <p class="text-sm text-gray-700">You have no cigars in your humidor.</p>
    </div>

    <div v-if="loadedInitialCigars && humidorCigarStore.myHumidorCigars[route.params.humidorId]?.length" class="mt-4">
        <div class="grid grid-cols-1 gap-4">
            <div v-for="cigar in humidorCigarStore.myHumidorCigars[route.params.humidorId]" class="bg-white rounded-md shadow-md p-4">
                <p>{{ cigar.name }} x {{ cigar.quantity }}</p>
            </div>
        </div>
    </div>
</template>