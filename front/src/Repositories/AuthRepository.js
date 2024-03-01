import axios from 'axios'
import { useAuthStore } from '@/stores/useAuth'



export const login = async (data) => {
    const store = useAuthStore()
    await axios.post('api/login', data)
    const response = await axios.get('api/users')
    const { loginAuthStore } = store
    loginAuthStore(response.data)
}

export const logout = async () => {
    const store = useAuthStore()
    await axios.post('api/logout')
    const { logoutAuthStore } = store
    logoutAuthStore()
    window.location.reload()
}

export const register = async (data) => {
    await axios.post('api/register', data);
    await axios.post('api/login', {
      email: data.email,
      password: data.password
    })

    const response = await axios.get('api/users')
    const store = useAuthStore()
    const { loginAuthStore } = store
    loginAuthStore(response.data)
}

export default { login, logout, register }