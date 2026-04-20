<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Doctor Dashboard</h2>
        <p class="text-gray-500 mt-1">Welcome, {{ authStore.user?.full_name }}</p>
      </div>
      <div class="flex gap-3">
        <button v-if="!doctorProfile" @click="showProfileForm = true" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition">
          Setup Doctor Profile
        </button>
        <button v-else @click="showAppointmentForm = true" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition">
          + Add Appointment
        </button>
      </div>
    </div>

    <!-- Doctor Profile Setup Form -->
    <div v-if="showProfileForm" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
      <h3 class="text-lg font-bold text-gray-900 mb-4">Setup Your Doctor Profile</h3>
      <form @submit.prevent="createProfile" class="space-y-4 max-w-md">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Specialization</label>
          <select v-model="profileForm.specialized_area" class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <option value="">Select specialization</option>
            <option v-for="spec in specializations" :key="spec.id" :value="spec.id">{{ spec.name }}</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Years of Experience</label>
          <input type="number" min="0" v-model="profileForm.years_of_experience" class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g. 5" />
        </div>
        <div class="flex gap-3">
          <button type="submit" :disabled="savingProfile" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-60 transition">
            {{ savingProfile ? 'Saving...' : 'Save Profile' }}
          </button>
          <button type="button" @click="showProfileForm = false" class="px-5 py-2.5 rounded-xl text-sm font-medium text-gray-700 border border-gray-300 hover:bg-gray-50 transition">Cancel</button>
        </div>
      </form>
    </div>

    <!-- Add Appointment Form -->
    <div v-if="showAppointmentForm" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
      <h3 class="text-lg font-bold text-gray-900 mb-4">Add New Appointment (Chamber)</h3>
      <form @submit.prevent="createAppointment" class="space-y-4 max-w-lg">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Hospital Name</label>
            <input type="text" v-model="apptForm.hospital_name" class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="City Hospital" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Hospital Location</label>
            <input type="text" v-model="apptForm.hospital_location" class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Dhaka" />
          </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Chamber Location</label>
            <input type="text" v-model="apptForm.chamber_location" class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Room 301" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Visiting Fee (BDT)</label>
            <input type="number" min="0" v-model="apptForm.visiting_fee" class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="500" />
          </div>
        </div>
        <div class="flex gap-3">
          <button type="submit" :disabled="savingAppt" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-60 transition">
            {{ savingAppt ? 'Saving...' : 'Create Appointment' }}
          </button>
          <button type="button" @click="showAppointmentForm = false" class="px-5 py-2.5 rounded-xl text-sm font-medium text-gray-700 border border-gray-300 hover:bg-gray-50 transition">Cancel</button>
        </div>
      </form>
    </div>

    <!-- Add Schedule Form -->
    <div v-if="showScheduleForm" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
      <h3 class="text-lg font-bold text-gray-900 mb-4">Add Schedule to: {{ scheduleForAppt?.hospital_name }}</h3>
      <form @submit.prevent="createSchedule" class="space-y-4 max-w-lg">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Day</label>
            <select v-model="schedForm.appointment_day" required class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              <option v-for="d in days" :key="d" :value="d">{{ d }}</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
            <input type="time" v-model="schedForm.available_start_time" required class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Duration (min)</label>
            <input type="number" min="15" v-model="schedForm.appointment_duration_max" required class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="30" />
          </div>
        </div>
        <div class="flex gap-3">
          <button type="submit" :disabled="savingSched" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-60 transition">
            {{ savingSched ? 'Saving...' : 'Add Schedule' }}
          </button>
          <button type="button" @click="showScheduleForm = false" class="px-5 py-2.5 rounded-xl text-sm font-medium text-gray-700 border border-gray-300 hover:bg-gray-50 transition">Cancel</button>
        </div>
      </form>
    </div>

    <!-- My Appointments -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
      <div class="px-6 py-5 border-b border-gray-100">
        <h3 class="text-lg font-bold text-gray-900">My Appointments (Chambers)</h3>
      </div>

      <div v-if="loadingAppts" class="p-12 flex justify-center">
        <div class="animate-spin rounded-full h-10 w-10 border-4 border-blue-200 border-t-blue-600"></div>
      </div>

      <div v-else-if="myAppointments.length === 0" class="px-6 py-12 text-center text-gray-500">
        <div class="text-3xl mb-2">🏥</div>
        <p>No appointments created yet. Add one above.</p>
      </div>

      <div v-else class="divide-y divide-gray-100">
        <div v-for="appt in myAppointments" :key="appt.id" class="p-6">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <div>
              <h4 class="font-bold text-gray-900">{{ appt.hospital_name }}</h4>
              <p class="text-sm text-gray-500">📍 {{ appt.chamber_location || appt.hospital_location }} · 💰 ৳{{ appt.visiting_fee }}</p>
            </div>
            <div class="flex gap-2">
              <button @click="openScheduleForm(appt)" class="px-3 py-1.5 rounded-lg text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 transition">
                + Add Schedule
              </button>
              <button @click="deleteAppointment(appt.id)" class="px-3 py-1.5 rounded-lg text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 transition">
                Delete
              </button>
            </div>
          </div>
          
          <!-- Schedules within this appointment -->
          <div v-if="appt.schedules && appt.schedules.length > 0" class="ml-4 border-l-2 border-blue-100 pl-4 space-y-2">
            <div v-for="sched in appt.schedules" :key="sched.id" class="flex items-center justify-between bg-gray-50 rounded-lg px-4 py-2 text-sm">
              <span class="font-medium text-gray-700">{{ sched.appointment_day }} · {{ sched.available_start_time }} · {{ sched.appointment_duration_max }}min</span>
              <button @click="deleteSchedule(sched.id)" class="text-red-400 hover:text-red-600 text-xs">✕ Remove</button>
            </div>
          </div>
          <p v-else class="text-sm text-gray-400 ml-4 italic">No schedules yet</p>
        </div>
      </div>
    </div>

    <!-- Patient Bookings for my appointments -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="px-6 py-5 border-b border-gray-100">
        <h3 class="text-lg font-bold text-gray-900">Patient Bookings</h3>
      </div>
      
      <div v-if="loadingBookings" class="p-12 flex justify-center">
        <div class="animate-spin rounded-full h-10 w-10 border-4 border-blue-200 border-t-blue-600"></div>
      </div>
      
      <div v-else-if="doctorBookings.length === 0" class="px-6 py-12 text-center text-gray-500">
        <div class="text-3xl mb-2">📋</div>
        <p>No bookings from patients yet.</p>
      </div>
      
      <ul v-else class="divide-y divide-gray-100">
        <li v-for="booking in doctorBookings" :key="booking.id" class="px-6 py-4">
          <div class="flex items-center justify-between gap-4">
            <div>
              <p class="font-semibold text-gray-900">{{ booking.patient?.full_name }}</p>
              <p class="text-sm text-gray-500">{{ booking.appointment?.hospital_name }} · {{ booking.appointment_date }} · {{ booking.schedule?.available_start_time }}</p>
            </div>
            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Confirmed</span>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import api from '../../api';
import { useAuthStore } from '../../stores/auth';
import { useNotificationStore } from '../../stores/notification';

const authStore = useAuthStore();
const notifStore = useNotificationStore();

const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

const doctorProfile = ref(null);
const specializations = ref([]);
const myAppointments = ref([]);
const doctorBookings = ref([]);

const loadingAppts = ref(true);
const loadingBookings = ref(true);

const showProfileForm = ref(false);
const savingProfile = ref(false);
const profileForm = reactive({ specialized_area: '', years_of_experience: 0 });

const showAppointmentForm = ref(false);
const savingAppt = ref(false);
const apptForm = reactive({ hospital_name: '', hospital_location: '', chamber_location: '', visiting_fee: 0 });

const showScheduleForm = ref(false);
const savingSched = ref(false);
const scheduleForAppt = ref(null);
const schedForm = reactive({ appointment_id: null, appointment_day: 'Monday', available_start_time: '09:00', appointment_duration_max: 30 });

onMounted(async () => {
  // Fetch specializations
  try {
    const specRes = await api.get('/specialists');
    specializations.value = specRes.data.data || specRes.data;
  } catch (e) { /* ignore */ }

  // Fetch doctor profile
  try {
    const profRes = await api.get('/doctor/profile');
    doctorProfile.value = profRes.data;
  } catch (e) {
    // No profile yet
    showProfileForm.value = true;
  }

  // Fetch my appointments
  await fetchMyAppointments();
  
  // Fetch bookings from patients
  await fetchDoctorBookings();
});

const fetchMyAppointments = async () => {
  loadingAppts.value = true;
  try {
    const res = await api.get('/my-appointments');
    myAppointments.value = res.data.data || res.data;
  } catch (e) { /* ignore */ }
  loadingAppts.value = false;
};

const fetchDoctorBookings = async () => {
  loadingBookings.value = true;
  try {
    const res = await api.get('/bookings-doctor');
    doctorBookings.value = res.data.data || res.data;
  } catch (e) { /* ignore */ }
  loadingBookings.value = false;
};

const createProfile = async () => {
  savingProfile.value = true;
  try {
    const res = await api.post('/doctor/profile', profileForm);
    doctorProfile.value = res.data.doctor;
    showProfileForm.value = false;
    notifStore.success('Doctor profile created!');
  } catch (err) {
    notifStore.error(err.response?.data?.message || 'Failed to create profile');
  }
  savingProfile.value = false;
};

const createAppointment = async () => {
  savingAppt.value = true;
  try {
    await api.post('/appointments', apptForm);
    showAppointmentForm.value = false;
    notifStore.success('Appointment created!');
    Object.assign(apptForm, { hospital_name: '', hospital_location: '', chamber_location: '', visiting_fee: 0 });
    await fetchMyAppointments();
  } catch (err) {
    notifStore.error(err.response?.data?.message || 'Failed to create appointment');
  }
  savingAppt.value = false;
};

const openScheduleForm = (appt) => {
  scheduleForAppt.value = appt;
  schedForm.appointment_id = appt.id;
  showScheduleForm.value = true;
};

const createSchedule = async () => {
  savingSched.value = true;
  try {
    // The API expects H:i format but the time input gives H:i:ss or H:i
    const payload = { ...schedForm };
    if (payload.available_start_time && payload.available_start_time.length === 5) {
      // already H:i format
    }
    await api.post('/appointment-schedules', payload);
    showScheduleForm.value = false;
    notifStore.success('Schedule added!');
    await fetchMyAppointments();
  } catch (err) {
    notifStore.error(err.response?.data?.message || 'Failed to add schedule');
  }
  savingSched.value = false;
};

const deleteAppointment = async (id) => {
  if (!confirm('Delete this appointment and all its schedules?')) return;
  try {
    await api.delete(`/appointments/${id}`);
    notifStore.success('Appointment deleted');
    await fetchMyAppointments();
  } catch (err) {
    notifStore.error('Failed to delete');
  }
};

const deleteSchedule = async (id) => {
  if (!confirm('Remove this schedule?')) return;
  try {
    await api.delete(`/appointment-schedules/${id}`);
    notifStore.success('Schedule removed');
    await fetchMyAppointments();
  } catch (err) {
    notifStore.error('Failed to remove');
  }
};
</script>
