import { defineStore } from "pinia";
import { computed, ref } from "vue";
import axios from "axios";

export const useHumidorStore = defineStore('humidor', () => {
    const isLoading = ref(false);
    const isError = ref(null);

    const humidor = ref(null);
    const humidors = ref([]);

    const myHumidors = computed(() => humidors.value);
    const currentHumidor = computed(() => humidor.value);

    const setHumidors = (value) => {
        humidors.value = value;
    }

    const setHumidor = (value) => {
        humidor.value = value;
    }

    const getHumidor = async (humidorId) => {
        isLoading.value = true;
        isError.value = null;

        try {
            const { data } = await axios.get(`/api/humidors/${humidorId}`);
            return data;
        } catch (e) {
            isError.value = e.response?.data?.message || "Error fetching humidor.";
            return null;
        } finally {
            isLoading.value = false;
        }
    }

    const getHumidors = async () => {
        isLoading.value = true;
        isError.value = null;

        try {
            const { data } = await axios.get('/api/humidors');
            setHumidors(data);
        } catch (e) {
            isError.value = e.response?.data?.message || "Error fetching humidors.";
        } finally {
            isLoading.value = false;
        }
    }

    const addHumidor = async (humidorData) => {
        isLoading.value = true;
        isError.value = null;

        try {
            const { data } = await axios.post('/api/humidors', humidorData);
            humidors.value.push(data);
        } catch (e) {
            isError.value = e.response?.data?.message || "Error creating humidor.";
        } finally {
            isLoading.value = false;
        }
    }

    const deleteHumidor = async (humidorId) => {
        isLoading.value = true;
        isError.value = null;

        try {
            const { data } = await axios.delete(`/api/humidors/${humidorId}`);
            humidors.value = humidors.value.filter(h => h.id !== humidorId);
        } catch (e) {
            isError.value = e.response?.data?.message || "Error deleting humidor.";
        } finally {
            isLoading.value = false;
        }
    }

    return {
        isLoading,
        isError,
        myHumidors,
        getHumidor,
        getHumidors,
        addHumidor,
        deleteHumidor,
    };
});