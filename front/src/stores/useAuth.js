import { computed } from 'vue'
import { defineStore } from 'pinia'
import { useLocalStorage } from "@vueuse/core"

export const useAuthStore = defineStore('counter', () => {
  const user = useLocalStorage('auth/logged', null)

  const isAuthenticated = computed(() => user.value != null)

  function loginAuthStore(data) {
    user.value = data
  }

  function logoutAuthStore() {
    user.value = null
  }

  async function updateAuthStore(data) {
    user.value = data
  }

  return { user, isAuthenticated, loginAuthStore, logoutAuthStore, updateAuthStore }
})
