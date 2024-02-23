import { ref, computed } from 'vue'
import { defineStore } from 'pinia'

export const useAuthStore = defineStore('counter', () => {
  const user = ref(null)
  const isAuthenticated = computed(() => user.value != null)

  function login(data) {
    console.log(data)
    user.value = data
    console.log(user.value)
  }

  function logout() {
    user.value = null
  }

  return { user, isAuthenticated, login, logout }
})
