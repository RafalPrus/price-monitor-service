import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

// Vuetify
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

import axios from 'axios'



const vuetify = createVuetify({
  components,
  directives,
  theme: {
    defaultTheme: 'dark',
  },
})


const getCookie = async () => {
  console.log('setted 2')
  axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
  axios.defaults.headers.common['Content-Type'] = 'apllication/json'
  axios.defaults.headers.common['Accept'] = 'apllication/json'
  axios.defaults.withCredentials = true
  axios.defaults.withXSRFToken = true;
  
  await axios.get('http://localhost/sanctum/csrf-cookie')
}
getCookie()

const pinia = createPinia()

const app = createApp(App)

app.use(pinia)
app.use(router)
app.use(vuetify)

app.mount('#app')
