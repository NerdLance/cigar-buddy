import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import { useAuthStore } from './stores/auth'

import axios from 'axios'

import App from './App.vue'
import router from './router'

axios.defaults.baseURL = 'http://localhost:8000';
axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;

(async () => {
    const app = createApp(App);
    
    app.use(createPinia());
    app.use(router);
    
    const auth = useAuthStore();
    
    await auth.attempt();

    app.mount('#app');
})();
