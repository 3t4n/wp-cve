import { createApp } from 'vue'
import App from './App.vue'
import menuFix from './utils/admin-menu-fix'

const app = createApp(App)

app.mount('#pargo-admin-app')



// fix the admin menu for the slug "vue-app"
menuFix('vue-app');
