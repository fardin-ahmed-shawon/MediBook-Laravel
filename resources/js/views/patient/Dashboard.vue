<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Patient Dashboard</h2>
        <p class="text-gray-500 mt-1">Welcome, {{ authStore.user?.full_name || 'User' }}</p>
      </div>
      <router-link to="/doctors" class="inline-flex items-center px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition self-start">
        + Book New Appointment
      </router-link>
    </div>
    
    <!-- Bookings Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="px-6 py-5 border-b border-gray-100">
        <h3 class="text-lg font-bold text-gray-900">Your Appointments</h3>
      </div>
      
      <!-- Loading -->
      <div v-if="loading" class="p-12 flex justify-center">
        <div class="animate-spin rounded-full h-10 w-10 border-4 border-blue-200 border-t-blue-600"></div>
      </div>
      
      <!-- Empty -->
      <div v-else-if="bookings.length === 0" class="px-6 py-16 text-center">
        <div class="text-4xl mb-3">📅</div>
        <h3 class="font-semibold text-gray-900">No appointments yet</h3>
        <p class="mt-1 text-sm text-gray-500">
          <router-link to="/doctors" class="text-blue-600 hover:text-blue-800 font-medium">Find a doctor</router-link> to book one.
        </p>
      </div>
      
      <!-- Booking List -->
      <ul v-else class="divide-y divide-gray-100">
        <li v-for="booking in bookings" :key="booking.id" class="px-6 py-4 hover:bg-gray-50 transition">
          <div class="flex items-center justify-between gap-4">
            <div class="min-w-0">
              <p class="font-semibold text-gray-900 truncate">
                {{ booking.appointment?.hospital_name || 'Appointment' }}
              </p>
              <p class="text-sm text-gray-500 mt-0.5">
                📍 {{ booking.appointment?.chamber_location || 'Location N/A' }}
              </p>
              <p class="text-sm text-gray-500 mt-0.5">
                📅 {{ booking.appointment_date }} · 🕐 {{ booking.schedule?.available_start_time || 'N/A' }}
              </p>
            </div>
            <div class="flex items-center gap-3 flex-shrink-0">
              <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">
                Confirmed
              </span>
              <button @click="cancelBooking(booking.id)" class="text-red-400 hover:text-red-600 text-sm transition" title="Cancel">
                ✕
              </button>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import api from '../../api';
import { useAuthStore } from '../../stores/auth';
import { useNotificationStore } from '../../stores/notification';

const authStore = useAuthStore();
const notifStore = useNotificationStore();
const router = useRouter();

const bookings = ref([]);
const loading = ref(true);

onMounted(async () => {
  if (!authStore.user) {
    await authStore.fetchUser();
  }
  await fetchBookings();
});

const fetchBookings = async () => {
  loading.value = true;
  try {
    const response = await api.get('/my-bookings');
    bookings.value = response.data.data || response.data;
  } catch (err) {
    notifStore.error('Could not fetch your appointments.');
  } finally {
    loading.value = false;
  }
};

const cancelBooking = async (id) => {
  if (!confirm('Are you sure you want to cancel this booking?')) return;
  try {
    await api.delete(`/bookings/${id}`);
    notifStore.success('Booking cancelled.');
    await fetchBookings();
  } catch (err) {
    notifStore.error('Failed to cancel booking.');
  }
};
</script>
