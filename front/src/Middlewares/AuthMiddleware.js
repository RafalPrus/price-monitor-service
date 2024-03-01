import { useAuthStore } from "@/stores/useAuth";

export const AuthMiddleware = async ({ to, next }) => {
	const authStore = useAuthStore();

	if (!authStore.isAuthenticated) return next({ name: 'login' });

	return next()
};

export default AuthMiddleware;