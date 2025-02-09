<script setup>
    import { onMounted } from 'vue';
    import { RouterLink } from 'vue-router';
    import { useAuthStore } from '@/stores/auth';
    import { useCigarStore } from '@/stores/cigar';
    import { useHumidorStore } from '@/stores/humidor';

    const auth = useAuthStore();
    const cigar = useCigarStore();
    const humidorStore = useHumidorStore();

    const deleteCigarEntry = async (cigarId, cigarName) => {
        const confirmDeletion = window.confirm("Are you sure you want to delete your " + cigarName + " entry?");

        if (confirmDeletion) {
            await cigar.deleteCigar(cigarId);
        }
    }

    onMounted(async () => {
        await Promise.all([
            cigar.getCigars(),
            humidorStore.getHumidors()
        ]);
    });
</script>

<template>
    <div class="bg-white rounded-md shadow-md p-4">
        <div class="md:flex md:justify-between md:items-center">
            <p class="mb-4 md:mb-0">Welcome to the dashboard, {{ auth.currentUser.name }}!</p>
            <RouterLink to="/cigar/add">
                <button type="button" class="w-full md:w-auto py-2 px-4 bg-blue-500 hover:bg-blue-600 transition text-white text-sm font-bold rounded-md">Add a Cigar</button>
            </RouterLink>
        </div>
    </div>
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-4">
        <div class="">
            <h3 class="text-lg font-bold">My Humidors</h3>
            <div v-if="humidorStore.isLoading && !humidorStore.myHumidors.length" class="mt-4">
                <div class="grid grid-cols-1 gap-4">
                    <div class="bg-white rounded-md shadow-md p-4">
                        <div role="status" class="max-w-sm animate-pulse">
                            <div class="h-2.5 bg-gray-200 rounded-full dark:bg-gray-700 w-48 mb-4"></div>
                            <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 max-w-[360px] mb-2.5"></div>
                            <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700"></div>
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="humidorStore.myHumidors.length" class="mt-4">
                <div class="grid grid-cols-1 gap-4">
                    <RouterLink :to="{ name: 'humidor-view', params: { humidorId: humidor.id } }" v-for="humidor in humidorStore.myHumidors">
                        <div class="bg-white rounded-md shadow-md p-4">
                            <div class="grid grid-cols-4 items-start">
                                <div class="col-span-3">
                                    <h3>{{ humidor.name }}</h3>
                                    <p class="mt-2 text-sm text-gray-700 italic">{{ humidor.description }}</p>
                                </div>
                                <div class="col-span-1">
                                    <div>
                                        <p class="text-right text-xs uppercase text-gray-700">Cigars</p>
                                        <h4 class="text-right text-2xl font-bold">{{ humidor.humidor_cigars_count }}</h4>
                                    </div>
                                    <div class="mt-2">
                                        <p class="text-right text-xs uppercase text-gray-700">Value</p>
                                        <h4 class="text-right text-2xl font-bold">${{ humidor.total_value }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </RouterLink>
                </div>
            </div>
            <div v-if="!humidorStore.myHumidors.length && !humidorStore.isLoading" class="mt-4">
                <div class="bg-white rounded-md shadow-md p-4">
                    <div class="md:flex md:justify-between md:items-center">
                        <p class="text-sm md:text-left text-center mb-4 md:mb-0">You have no humidors.</p>
                        <RouterLink to="/humidor/add">
                            <button type="button" class="cursor-pointer w-full md:w-auto py-2 px-4 bg-blue-500 hover:bg-blue-600 transition text-sm text-white font-bold rounded-md">Add a Humidor</button>
                        </RouterLink>
                    </div>
                </div>
            </div>
        </div>
        <div class="">
            <h3 class="text-lg font-bold">My Cigar Logs</h3>
            <div v-if="cigar.loading && !cigar.myCigars.length" class="mt-4">
                <div class="grid grid-cols-1 gap-4">
                    <div v-for="n in 2" class="bg-white rounded-md shadow-md p-4">
                        <div role="status" class="max-w-sm animate-pulse">
                            <div class="h-2.5 bg-gray-200 rounded-full dark:bg-gray-700 w-48 mb-4"></div>
                            <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 max-w-[360px] mb-2.5"></div>
                            <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 mb-2.5"></div>
                            <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 max-w-[330px] mb-2.5"></div>
                            <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 max-w-[300px] mb-2.5"></div>
                            <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 max-w-[360px]"></div>
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="cigar.myCigars.length" class="mt-4">
                <div class="grid grid-cols-1 gap-4">
                    <div v-for="stogie in cigar.myCigars" class="bg-white rounded-md shadow-md p-4">
                        <p class="text-sm font-medium">{{ stogie.name }}</p>
                        <p class="text-sm italic mt-2">Notes of {{ stogie.notes }}</p>
                        <p class="text-sm mt-2">{{ stogie.summary }}</p>
                        <div class="mt-4 flex justify-between">
                            <p class="text-xs">Size: {{ Math.round(stogie.length * 100) / 100 }} x {{ Math.round(stogie.ring * 100) / 100 }}</p>
                            <p class="text-xs">Rating: {{ stogie.rating }}/100</p>
                        </div>
                        <div class="mt-4 flex justify-end gap-2">
                            <p class="text-xs underline font-medium text-gray-700"><RouterLink :to="{ name: 'cigar-edit', params: { cigarId: stogie.id } }">Edit</RouterLink></p>
                            <button class="text-xs underline font-medium text-red-700" type="button" @click="deleteCigarEntry(stogie.id, stogie.name)">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>