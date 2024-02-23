import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import { useAuthStore } from '@/stores/useAuth'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/about',
      name: 'about',
      component: () => import('../views/AboutView.vue')
    },
    {
      path: '/register',
      name: 'register',
      component: () => import('../views/RegisterView.vue')
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/LoginView.vue')
    },
    {
      path: '/me',
      name: 'me',
      component: () => import('../views/MeView.vue')
    },
    ,
    {
      path: '/offers',
      name: 'offers',
      component: () => import('../views/OffersView.vue')
    },
    {
      path: '/logout',
      name: 'logout',
      redirect: to => {
        return { path: '/' }
      },
    }
  ]
})


router.beforeEach((to, from, next) => {
  const store = useAuthStore()
  const { isAuthenticated } = store
  if ((to.name !=='login' && to.name !=='home' && to.name !=='register')  && !isAuthenticated) next({ name: 'login' })
    
  else next()
})

export default router
