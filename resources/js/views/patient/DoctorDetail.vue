<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <button @click="$router.back()" class="text-sm font-medium text-blue-600 hover:text-blue-500 flex items-center mb-6 transition">
      <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
      Back to Doctors
    </button>
    
    <!-- Loading -->
    <div v-if="loading" class="flex justify-center py-20">
      <div class="animate-spin rounded-full h-12 w-12 border-4 border-blue-200 border-t-blue-600"></div>
    </div>
    
    <!-- Error -->
    <div v-else-if="error" class="bg-red-50 text-red-600 p-4 rounded-xl">{{ error }}</div>

    <template v-else-if="doctor">
      <!-- Doctor Profile Card -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-10">
          <div class="sm:flex sm:items-center sm:gap-6">
            <div class="w-20 h-20 rounded-2xl bg-white/20 text-white flex items-center justify-center font-bold text-3xl flex-shrink-0 backdrop-blur-sm mx-auto sm:mx-0">
              {{ doctor.user?.full_name?.charAt(0) || 'D' }}
            </div>
            <div class="mt-4 sm:mt-0 text-center sm:text-left">
              <h1 class="text-2xl sm:text-3xl font-bold text-white">{{ doctor.user?.full_name }}</h1>
              <p class="text-blue-200 font-medium mt-1">{{ doctor.specialized_category?.name || 'General Practitioner' }}</p>
              <div class="flex items-center justify-center sm:justify-start gap-3 mt-3">
                <span class="inline-flex items-center gap-1 bg-white/20 text-white text-sm rounded-full px-3 py-1 backdrop-blur-sm">
                  🎓 {{ doctor.years_of_experience || 0 }} Years Experience
                </span>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Appointments Section -->
        <div class="p-6 sm:p-8">
          <h2 class="text-xl font-bold text-gray-900 mb-6">Available Chambers & Schedules</h2>
          
          <div v-if="!doctor.appointments || doctor.appointments.length === 0" class="text-center py-12 text-gray-500">
            <div class="text-3xl mb-2">📋</div>
            <p>This doctor has not set up any appointments yet.</p>
          </div>
          
          <div v-else class="space-y-6">
            <div v-for="appointment in doctor.appointments" :key="appointment.id" class="border border-gray-200 rounded-xl overflow-hidden">
              <!-- Location Header -->
              <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 border-b border-gray-200">
                <div>
                  <h3 class="font-bold text-gray-900">{{ appointment.hospital_name || 'Hospital' }}</h3>
                  <p class="text-sm text-gray-500 flex items-center mt-0.5">
                    📍 {{ appointment.chamber_location || appointment.hospital_location || 'Location N/A' }}
                  </p>
                </div>
                <div class="text-left sm:text-right">
                  <span class="text-lg font-bold text-blue-600">৳{{ appointment.visiting_fee }}</span>
                  <p class="text-xs text-gray-500">Consultation fee</p>
                </div>
              </div>
              
              <!-- Schedules -->
              <ul class="divide-y divide-gray-100">
                <li v-for="schedule in appointment.schedules" :key="schedule.id" 
                  class="px-6 py-4 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 hover:bg-blue-50/50 transition">
                  <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                      <span class="text-xs font-bold">{{ schedule.appointment_day.substring(0, 3).toUpperCase() }}</span>
                    </div>
                    <div>
                      <p class="font-medium text-gray-900">{{ schedule.appointment_day }}</p>
                      <p class="text-xs text-gray-500">Starts {{ formatTime(schedule.available_start_time) }} · {{ schedule.appointment_duration_max }} min session</p>
                    </div>
                  </div>
                  <button @click="openBookingModal(appointment, schedule)" 
                    class="px-5 py-2 text-sm font-semibold rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition shadow-sm self-start sm:self-auto">
                    Book Now
                  </button>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </template>
    
    <BookingModal 
      :show="showModal" 
      :appointment="selectedAppointment" 
      :schedule="selectedSchedule" 
      @close="showModal = false"
      @booked="handleBooked"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../../api';
import BookingModal from '../../components/BookingModal.vue';
import { useNotificationStore } from '../../stores/notification';
import { useAuthStore } from '../../stores/auth';

const route = useRoute();
const router = useRouter();
const notifStore = useNotificationStore();
const authStore = useAuthStore();

const doctor = ref(null);
const loading = ref(true);
const error = ref(null);

const showModal = ref(false);
const selectedAppointment = ref(null);
const selectedSchedule = ref(null);

onMounted(async () => {
  try {
    // getDoctorById already eager loads appointments.schedules
    const res = await api.get(`/doctors/${route.params.id}`);
    doctor.value = res.data;
  } catch (err) {
    error.value = 'Failed to load doctor details.';
    notifStore.error(error.value);
  } finally {
    loading.value = false;
  }
});

const formatTime = (time) => {
  if (!time) return '';
  const [h, m] = time.split(':');
  const hours = parseInt(h, 10);
  const ampm = hours >= 12 ? 'PM' : 'AM';
  return `${hours % 12 || 12}:${m} ${ampm}`;
};

const openBookingModal = (appointment, schedule) => {
  if (!authStore.isAuthenticated) {
    notifStore.info('Please log in to book an appointment.');
    router.push('/login');
    return;
  }
  selectedAppointment.value = appointment;
  selectedSchedule.value = schedule;
  showModal.value = true;
};

const handleBooked = () => {
  router.push('/dashboard');
};
</script>
