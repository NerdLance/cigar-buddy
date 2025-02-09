import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import axios from 'axios';

export const useHumidorCigarStore = defineStore('humidorCigar', () => {
    const isLoading = ref(false);
    const isError = ref(null);

    const humidorCigars = ref({});

    const myHumidorCigars = computed(() => humidorCigars.value);

    const getHumidorCigars = async (humidorId) => {
        isLoading.value = true;
        isError.value = null;

        try {
            const { data } = await axios.get(`/api/humidors/${humidorId}/cigars`);
            humidorCigars.value[humidorId] = data;
            console.log(data);
        } catch (e) {
            isError.value = e.response?.data?.message || "Error fetching Humidor contents.";
            console.log('Fetch error: ', e);
        } finally {
            isLoading.value = false;
        }
    }

    const addHumidorCigar = async (humidorId, cigarData) => {
        isLoading.value = true;
        isError.value = null;

        try {
            await axios.post(`/api/humidors/${humidorId}/cigars`, cigarData);
            await getHumidorCigars(humidorId);
        } catch (e) {
            isError.value = e.response?.data?.message || "Error updating Humidor. Please try again later.";
            console.log('Add Error: ', e);
        } finally {
            isLoading.value = false;
        }
    }

    return {
        isLoading,
        isError,
        myHumidorCigars,
        getHumidorCigars,
        addHumidorCigar,
    }
});