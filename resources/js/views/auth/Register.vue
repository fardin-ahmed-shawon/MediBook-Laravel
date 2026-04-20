<template>
  <div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-50">
    <div class="max-w-md w-full">
      <div class="bg-white p-8 sm:p-10 rounded-2xl shadow-xl border border-gray-100">
        <div class="text-center mb-8">
          <div class="w-14 h-14 bg-blue-600 rounded-xl flex items-center justify-center mx-auto mb-4 text-white font-bold text-xl">+</div>
          <h2 class="text-2xl font-bold text-gray-900">Create an Account</h2>
          <p class="mt-2 text-sm text-gray-500">Join MediBook today</p>
        </div>
        
        <form @submit.prevent="handleRegister" class="space-y-5">
          <div v-if="authStore.error" class="bg-red-50 text-red-600 p-3 rounded-lg text-sm text-center border border-red-100">
            {{ authStore.error }}
          </div>
          
          <!-- Account Type -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Account Type</label>
            <div class="grid grid-cols-2 gap-3">
              <button type="button" @click="form.user_type = 'patient'" 
                :class="['rounded-xl p-3 text-sm font-medium border-2 transition', form.user_type === 'patient' ? 'bg-blue-50 border-blue-500 text-blue-700' : 'bg-white border-gray-200 text-gray-500 hover:bg-gray-50']">
                🩺 Patient
              </button>
              <button type="button" @click="form.user_type = 'doctor'" 
                :class="['rounded-xl p-3 text-sm font-medium border-2 transition', form.user_type === 'doctor' ? 'bg-blue-50 border-blue-500 text-blue-700' : 'bg-white border-gray-200 text-gray-500 hover:bg-gray-50']">
                👨‍⚕️ Doctor
              </button>
            </div>
          </div>
        
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
            <input type="text" required v-model="form.full_name" class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="John Doe" />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
            <input type="text" required v-model="form.phone" class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="01712345678" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email (Optional)</label>
            <input type="email" v-model="form.email" class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="john@example.com" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" required v-model="form.password" class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="Min 6 characters" />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
            <input type="password" required v-model="form.password_confirmation" class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="Re-enter password" />
          </div>

          <button type="submit" :disabled="authStore.loading" 
            class="w-full py-3 px-4 text-sm font-semibold rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-60 shadow-lg shadow-blue-600/20 transition">
            {{ authStore.loading ? 'Creating Account...' : 'Register' }}
          </button>
          
          <p class="text-center text-sm text-gray-500 mt-6">
            Already have an account?
            <router-link to="/login" class="font-semibold text-blue-600 hover:text-blue-500">Sign in</router-link>
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
  full_name: '',
  phone: '',
  email: '',
  password: '',
  password_confirmation: '',
  user_type: 'patient'
});

const handleRegister = async () => {
  const success = await authStore.register(form);
  if (success) {
    notifStore.success('Registration successful!');
    if (authStore.isDoctor) {
      router.push('/doctor/dashboard');
    } else {
      router.push('/dashboard');
    }
  }
};
</script>
