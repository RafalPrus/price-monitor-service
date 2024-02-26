import { computed } from 'vue'
import { defineStore } from 'pinia'
import { useLocalStorage } from "@vueuse/core"

export const useAuthStore = defineStore('counter', () => {
  const user = useLocalStorage('auth/logged', null)
  const isAuthenticated = computed(() => user.value != null)

  function login(data) {
    user.value = data
  }

  function logout() {
    user.value = null
  }

  return { user, isAuthenticated, login, logout }
})
