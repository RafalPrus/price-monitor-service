import { computed } from 'vue'
import {defineStore, skipHydrate} from 'pinia'
import { useLocalStorage } from "@vueuse/core"

export const useAuthStore = defineStore('auth', () => {
  const user= useLocalStorage('auth.logged', 'unauthorized')
  console.log('store')
  console.log(user.value)

  const isAuthenticated = computed(() => user.value != 'unauthorized')

  function loginAuthStore(data) {
    user.value = data
  }

  function logoutAuthStore() {
    user.value = 'unauthorized'
  }

  async function updateAuthStore(data) {
    user.value = data
  }

  return { user: skipHydrate(user), isAuthenticated, loginAuthStore, logoutAuthStore, updateAuthStore }
})
