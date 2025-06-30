import './bootstrap';
import { createApp } from 'vue';
import BillingIndex from './components/BillingIndex.vue';

const app = createApp({});
app.component('billing-index', BillingIndex);

if (document.getElementById('billing-app')) {
    app.mount('#billing-app');
}
