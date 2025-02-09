import { createRouter, createWebHistory } from 'vue-router';
import HomeView from '@/views/HomeView.vue';
import { useAuthStore } from '@/stores/auth';

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView,
    meta:{
        auth: true
    }
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('../views/LoginView.vue')
  },
  {
    path: '/feed',
    name: 'feed',
    component: () => import('../views/FeedView.vue'),
    meta:{
        auth: true
    }
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});


router.beforeEach((to, from, next) => {
    if(to.meta?.auth){
        const auth = useAuthStore();
        if (auth.token && auth.user)
            next()
        else
            next({name: 'login'})

        console.log('precisa validar')
        console.log(from)
    }else {
        next()
    }
})

export default router;
