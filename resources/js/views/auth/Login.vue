<template>
  <div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-50">
    <div class="max-w-md w-full">
      <div class="bg-white p-8 sm:p-10 rounded-2xl shadow-xl border border-gray-100">
        <div class="text-center mb-8">
          <div class="w-14 h-14 bg-blue-600 rounded-xl flex items-center justify-center mx-auto mb-4 text-white font-bold text-xl">+</div>
          <h2 class="text-2xl font-bold text-gray-900">Welcome back</h2>
          <p class="mt-2 text-sm text-gray-500">Sign in to your MediBook account</p>
        </div>
        
        <form @submit.prevent="handleLogin" class="space-y-5">
          <div v-if="authStore.error" class="bg-red-50 text-red-600 p-3 rounded-lg text-sm text-center border border-red-100">
            {{ authStore.error }}
          </div>
          
          <div>
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
            <input id="phone" type="text" required v-model="form.phone"
              class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition"
              placeholder="e.g. 01712345678" />
          </div>
          
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input id="password" type="password" required v-model="form.password"
              class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition"
              placeholder="••••••••" />
          </div>

          <button type="submit" :disabled="authStore.loading"
            class="w-full py-3 px-4 border border-transparent text-sm font-semibold rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition disabled:opacity-60 shadow-lg shadow-blue-600/20">
            {{ authStore.loading ? 'Signing in...' : 'Sign in' }}
          </button>
          
          <p class="text-center text-sm text-gray-500 mt-6">
            Don't have an account?
            <router-link to="/register" class="font-semibold text-blue-600 hover:text-blue-500">Register here</router-link>
          </p>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../stores/auth';
import { useNotificationStore } from '../../stores/notification';

const router = useRouter();
const authStore = useAuthStore();
const notifStore = useNotificationStore();

const form = reactive({
  phone: '',
  password: ''
});

const handleLogin = async () => {
  const success = await authStore.login(form);
  if (success) {
    notifStore.success('Logged in successfully!');
    // Route based on role
    if (authStore.isAdmin) {
      router.push('/admin');
    } else if (authStore.isDoctor) {
      router.push('/doctor/dashboard');
    } else {
      router.push('/dashboard');
    }
  }
};
</script>
