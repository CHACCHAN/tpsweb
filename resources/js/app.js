import './bootstrap';
import { createApp } from 'vue';
import App from '../js/views/App.vue';
import router from "./router";
import 'vuetify/dist/vuetify.min.css';

createApp(App).use(router).mount('#app');