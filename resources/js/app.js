import './bootstrap';
import { createApp } from 'vue/dist/vue.esm-bundler';
import Cart from '../vue/pages/cart.vue';

const app = createApp({ components: { Cart } });
app.mount('#app');
