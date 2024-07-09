import './bootstrap';                                                      
import { createApp } from 'vue/dist/vue.esm-bundler.js';
import Welcome from './Pages/Welcome.vue';

createApp({
    components: {
        Welcome
    }
}).mount('#app');