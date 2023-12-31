import './bootstrap';
import { createApp } from 'vue';
import App from '../js/views/App.vue';
import router from "./router";
import 'vuetify/dist/vuetify.min.css';

const app = createApp(App);
app.use(router).mount('#app');