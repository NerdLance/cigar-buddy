import { defineStore } from "pinia";
import { ref, computed } from "vue";
import axios from "axios";

export const useCigarStore = defineStore('cigar', () => {
    const cigars = ref([]);
    const loading = ref(false);
    const error = ref(null);

    const myCigars = computed(() => cigars.value);

    const setCigars = (value) => {
        cigars.value = value;
    }

    const getCigars = async () => {
        loading.value = true;
        error.value = null;

        try {
            const newCigars = await axios.get('/api/cigars');
            setCigars(newCigars.data);
            console.log(myCigars);
        } catch (e) {
            error.value = e.response?.data?.message || "Error fetching your cigars. Please try again.";
        } finally {
            loading.value = false;
        }
    }

    const getCigar = async (cigarId) => {
        loading.value = true;
        error.value = null;

        try {
            const { data } = await axios.get(`/api/cigars/${cigarId}`);
            return data;
        } catch (e) {
            error.value = e.response?.data?.message || "Error fetching cigar. Please try again.";
        } finally {
            loading.value = false;
        }
    }

    const addCigar = async (cigarData) => {
        loading.value = true;
        error.value = null;

        try {
            const { data } = await axios.post('/api/cigars', cigarData);
            cigars.value.unshift(data);
        } catch (e) {
            error.value = e.response?.data?.message || "Error adding your cigar. Please try again.";
        } finally {
            loading.value = false;
        }
    }

    const deleteCigar = async (cigarId) => {
        loading.value = true;
        error.value = null;

        try {
            await axios.delete(`/api/cigars/${cigarId}`);
            cigars.value = cigars.value.filter(stogie => stogie.id !== cigarId);
        } catch (e) {
            error.value = e.response?.data?.message || "Error deleting cigar log entry. Please try again.";
        } finally {
            loading.value = false;
        }
    }

    const resetStore = () => {
        cigars.value = [];
    }

    return {
        loading,
        error,
        myCigars,
        getCigars,
        getCigar,
        addCigar,
        deleteCigar,
        resetStore,
    };
});