import axios from 'axios'
import { useAuthStore } from '@/stores/useAuth'

export const getAuth = async () => {
    return await axios.get('api/users')
}

export const login = async (data) => {
    const store = useAuthStore()
    await axios.post('api/login', data)
    const response = await getAuth()
    const { loginAuthStore } = store
    loginAuthStore(response.data)
}

export const logout = async () => {
    const store = useAuthStore()
    const { logoutAuthStore } = store
    try {
        await axios.post('api/logout')
        logoutAuthStore()
    } catch (error) {
        logoutAuthStore()
    }

}

export const register = async (data) => {
    await axios.post('api/register', data);
    await axios.post('api/login', {
      email: data.email,
      password: data.password
    })

    const response = await getAuth()
    const store = useAuthStore()
    const { loginAuthStore } = store
    loginAuthStore(response.data)
}

export default { login, logout, register, getAuth }