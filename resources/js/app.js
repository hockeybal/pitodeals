import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import { loadContent } from './stores/content';

const app = createApp(App);
app.use(router);

loadContent().finally(() => app.mount('#app'));
