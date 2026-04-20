<template>
  <div class="min-h-screen bg-gray-50 font-sans text-gray-900 flex flex-col">
    <GlobalToast />
    
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <!-- Logo -->
          <div class="flex items-center">
            <router-link to="/" class="flex items-center gap-2 group">
              <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white font-bold shadow-sm group-hover:shadow-md transition-shadow">
                +
              </div>
              <span class="font-bold text-xl tracking-tight text-gray-900">Medi<span class="text-blue-600">Book</span></span>
            </router-link>
          </div>
          
          <!-- Nav Links -->
          <div class="flex items-center gap-1">
            <router-link to="/" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-lg text-sm font-medium transition hover:bg-blue-50">Home</router-link>
            <router-link to="/doctors" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-lg text-sm font-medium transition hover:bg-blue-50">Doctors</router-link>
            
            <template v-if="!authStore.isAuthenticated">
              <div class="w-px h-6 bg-gray-200 mx-2"></div>
              <router-link to="/login" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-lg text-sm font-medium transition hover:bg-blue-50">Login</router-link>
              <router-link to="/register" class="ml-1 bg-blue-600 text-white hover:bg-blue-700 px-4 py-2 rounded-xl text-sm font-semibold shadow-sm transition hover:shadow-md">Register</router-link>
            </template>
            
            <template v-else>
              <div class="w-px h-6 bg-gray-200 mx-2"></div>
              
              <!-- Role-based dashboard link -->
              <router-link v-if="authStore.isAdmin" to="/admin" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-lg text-sm font-medium transition hover:bg-blue-50">Admin Panel</router-link>
              <router-link v-else-if="authStore.isDoctor" to="/doctor/dashboard" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-lg text-sm font-medium transition hover:bg-blue-50">Dashboard</router-link>
              <router-link v-else to="/dashboard" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-lg text-sm font-medium transition hover:bg-blue-50">Dashboard</router-link>
              
              <!-- User info + logout -->
              <div class="flex items-center gap-2 ml-2">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center text-white text-xs font-bold">
                  {{ authStore.user?.full_name?.charAt(0) || '?' }}
                </div>
                <button @click="handleLogout" class="text-gray-500 hover:text-red-500 px-2 py-2 rounded-lg text-sm font-medium transition hover:bg-red-50 cursor-pointer">
                  Logout
                </button>
              </div>
            </template>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">
      <router-view v-slot="{ Component }">
        <transition name="fade" mode="out-in">
          <component :is="Component" />
        </transition>
      </router-view>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-8 mt-auto">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-gray-400 text-sm">
        &copy; {{ new Date().getFullYear() }} MediBook. All rights reserved.
      </div>
    </footer>
  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useRouter } from 'vue-router';
import GlobalToast from './components/GlobalToast.vue';
import { useAuthStore } from './stores/auth';
import { useNotificationStore } from './stores/notification';

const authStore = useAuthStore();
const notifStore = useNotificationStore();
const router = useRouter();

onMounted(() => {
  if (authStore.isAuthenticated && !authStore.user) {
    authStore.fetchUser();
  }
});

const handleLogout = async () => {
  await authStore.logout();
  notifStore.success('Logged out successfully');
  router.push('/');
};
</script>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.15s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
