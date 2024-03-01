import axios from 'axios'
import { useAuthStore } from '@/stores/useAuth'
import { getAuth } from '@/Repositories/AuthRepository'

export const verifyEmail = async (queryParams, params) => {
    await axios.get('api/email/verify/' + queryParams.id + '/' + queryParams.token, { params })
    const authData = await getAuth()
    const { updateAuthStore } = useAuthStore()
    await updateAuthStore(authData.data)
}

export default { verifyEmail }