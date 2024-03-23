import { computed } from 'vue'
import {defineStore, skipHydrate} from 'pinia'
import { useLocalStorage } from "@vueuse/core"

export const useAuthStore = defineStore('auth', () => {
  const user= useLocalStorage('auth.logged', {})
  console.log('store')
  console.log(user)

  const isAuthenticated = computed(() => Object.keys(user.value).length > 0)

  console.log(isAuthenticated.value)

  function loginAuthStore(data) {
    user.value = data
  }

  function logoutAuthStore() {
    user.value = {}
  }

  async function updateAuthStore(data) {
    user.value = data
  }

  return { user: skipHydrate(user), isAuthenticated, loginAuthStore, logoutAuthStore, updateAuthStore }
})
