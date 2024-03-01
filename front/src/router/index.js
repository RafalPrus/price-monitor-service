import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import { AuthMiddleware } from '@/Middlewares/AuthMiddleware'
import { useAuthStore } from "@/stores/useAuth";
import middlewarePipeline from './middlewarePipeline'

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
    {
      path: '/offers',
      name: 'offers',
      component: () => import('../views/OffersView.vue'),
      meta: {
        middleware: [AuthMiddleware]
      }
    },
    {
      path: '/logout',
      name: 'logout',
      redirect: to => {
        return { path: '/' }
      },
    },
    {
      path: '/register/confirm',
      name: 'confirm-email',
      component: () => import('../views/EmailConfirmed.vue'),
    }
  ]
})

router.beforeEach((to, from, next) => {
  if (!to.meta.middleware) {
      return next()
  }
  const middleware = to.meta.middleware

  const context = {
      to,
      from,
      next,
      useAuthStore
  }

  return middleware[0]({
      ...context,
      next: middlewarePipeline(context, middleware, 1)
  })
})

export default router
