import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/Home.vue';
import { useAuthStore } from '../stores/auth';

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home
    },
    {
        path: '/login',
        name: 'Login',
        component: () => import('../views/auth/Login.vue'),
        meta: { guest: true }
    },
    {
        path: '/register',
        name: 'Register',
        component: () => import('../views/auth/Register.vue'),
        meta: { guest: true }
    },
    {
        path: '/doctors',
        name: 'DoctorList',
        component: () => import('../views/patient/DoctorList.vue')
    },
    {
        path: '/doctors/:id',
        name: 'DoctorDetail',
        component: () => import('../views/patient/DoctorDetail.vue')
    },
    {
        path: '/dashboard',
        name: 'PatientDashboard',
        component: () => import('../views/patient/Dashboard.vue'),
        meta: { requiresAuth: true, role: 'patient' }
    },
    {
        path: '/doctor/dashboard',
        name: 'DoctorDashboard',
        component: () => import('../views/doctor/Dashboard.vue'),
        meta: { requiresAuth: true, role: 'doctor' }
    },
    {
        path: '/admin',
        name: 'AdminDashboard',
        component: () => import('../views/admin/Dashboard.vue'),
        meta: { requiresAuth: true, role: 'admin' }
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior() {
        return { top: 0 };
    }
});

router.beforeEach((to, from, next) => {
    const authStore = useAuthStore();
    const isAuthenticated = authStore.isAuthenticated;

    if (to.meta.requiresAuth && !isAuthenticated) {
        next('/login');
    } else if (to.meta.guest && isAuthenticated) {
        // Redirect logged-in users to their role dashboard
        const userType = authStore.userType;
        if (userType === 'admin') next('/admin');
        else if (userType === 'doctor') next('/doctor/dashboard');
        else next('/dashboard');
    } else {
        next();
    }
});

export default router;
