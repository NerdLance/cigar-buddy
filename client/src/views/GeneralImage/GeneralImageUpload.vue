<script setup>
    import { ref } from 'vue';
    import axios from 'axios';

    const files = ref([]);
    const uploadProgress = ref(0);
    const isUploading = ref(false);

    const handleFileChange = (event) => {
        const selectedFiles = event.target.files || event.dataTransfer.files;
        for (const file of selectedFiles) {
            files.value.push({ file, preview: URL.createObjectURL(file) });
        }
    }

    const removeFile = (index) => {
        URL.revokeObjectURL(files.value[index].preview);
        files.value.splice(index, 1);
    }

    const uploadFiles = async () => {
        if (!files.value.length) return;

        isUploading.value = true;

        const formData = new FormData();
        files.value.forEach(({ file }) => {
            formData.append("images[]", file);
        });

        try {
            const response = await axios.post("/api/image/upload/general", formData, {
                headers: { "Content-Type": "multipart/form-data" },
                onUploadProgress: (progressEvent) => {
                    const percentCompleted = Math.round(
                        (progressEvent.loaded * 100) / progressEvent.total
                    );
                    uploadProgress.value = percentCompleted;
                },
            });

            console.log("Upload successful:", response.data?.message || "Woot!");
        } catch (error) {
            console.error("Upload failed:", error.response?.data?.message || "Unknown Error Occurred.");
        } finally {
            isUploading.value = false;
            files.value = [];
        }
    }
</script>

<template>
    <div id="uploader" class="p-5 text-center">
        <div
            id="drop-zone"
            class="py-16 px-8 cursor-pointer border-2 border-dashed border-gray-400 rounded-md"
            @dragover.prevent
            @drop.prevent="handleFileChange"
            @click="$refs.fileInput.click()"
        >
            <p class="text-sm text-gray-700">Drag & Drop or Click Here to Upload</p>
            <input 
                type="file"
                ref="fileInput"
                multiple
                accept="image/*"
                @change="handleFileChange"
                hidden
            />
        </div>

        <div id="preview-container" class="grid grid-cols-4 lg:grid-cols-12 gap-4 mt-4">
            <div v-for="(file, index) in files" :key="index" id="preview">
                <img :src="file.preview" alt="Preview" class="w-full h-auto object-cover rounded-md" />
                <button @click="removeFile(index)" class="text-sm underline text-gray-700">Remove</button>
            </div>
        </div>

        <div class="mt-4">
            <button 
                @click="uploadFiles" 
                :disabled="isUploading || !files.length" 
                class="py-2 px-4 bg-blue-500 hover:bg-blue-600 transition text-white text-sm font-bold rounded-md cursor-pointer"
            >
                {{ isUploading ? `Uploading ${uploadProgress}` : "Upload Selected Images" }}
            </button>
        </div>
    </div>
</template>