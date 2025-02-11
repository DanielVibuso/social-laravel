import { createApp } from 'vue'
import App from './App.vue'
import router from './router' // Importe o router
import { createPinia } from 'pinia'

const app = createApp(App)
const pinia = createPinia()

app.use(pinia)
app.use(router)
app.use(pinia)
app.mount('#app')
