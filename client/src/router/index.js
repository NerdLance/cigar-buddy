import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import { useAuthStore } from '@/stores/auth';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue'),
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: () => import('../views/DashboardView.vue'),
      meta: { requiresAuth: true },
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/LoginView.vue'),
      meta: { guest: true },
    },
    {
      path: '/register',
      name: 'register',
      component: () => import('../views/RegisterView.vue'),
      meta: { guest: true },
    },
    {
      path: '/cigar/add',
      name: 'cigar-add',
      component: () => import('../views/CigarAdd.vue'),
      meta: { requiresAuth: true }
    }, 
    {
      path: '/cigar/:cigarId/edit',
      name: 'cigar-edit',
      component: () => import('../views/CigarEdit.vue'),
      meta: { requiresAuth: true },
    },
    {
      path: '/humidor/add',
      name: 'humidor-add',
      component: () => import('../views/HumidorAdd.vue'),
      meta: { requiresAuth: true },
    },
    {
      path: '/humidor/view/:humidorId',
      name: 'humidor-view',
      component: () => import('../views/Humidor/HumidorView.vue'),
      meta: { requiresAuth: true },
    },
    {
      path: '/image/general/upload',
      name: 'image-general-upload',
      component: () => import('../views/GeneralImage/GeneralImageUpload.vue'),
      meta: { requiresAuth: true },
    }
  ],
});

router.beforeEach(async (to, from, next) => {
  const auth = useAuthStore();

  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    localStorage.setItem('intendedRoute', to.fullPath);
    next('/login');
  } else if (to.meta.guest && auth.isAuthenticated) {
    next('/dashboard');
  } else {
    next();
  }
});

export default router
