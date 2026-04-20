<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Header -->
    <div class="mb-8">
      <h2 class="text-2xl font-bold text-gray-900">Admin Dashboard</h2>
      <p class="text-gray-500 mt-1">System overview and management</p>
    </div>

    <!-- Stats Grid -->
    <div v-if="stats" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-10">
      <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <p class="text-2xl font-bold text-gray-900">{{ stats.total_users }}</p>
        <p class="text-sm text-gray-500 mt-1">Total Users</p>
      </div>
      <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <p class="text-2xl font-bold text-blue-600">{{ stats.total_patients }}</p>
        <p class="text-sm text-gray-500 mt-1">Patients</p>
      </div>
      <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <p class="text-2xl font-bold text-indigo-600">{{ stats.total_doctors }}</p>
        <p class="text-sm text-gray-500 mt-1">Doctors</p>
      </div>
      <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <p class="text-2xl font-bold text-emerald-600">{{ stats.total_appointments }}</p>
        <p class="text-sm text-gray-500 mt-1">Appointments</p>
      </div>
      <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <p class="text-2xl font-bold text-amber-600">{{ stats.total_bookings }}</p>
        <p class="text-sm text-gray-500 mt-1">Bookings</p>
      </div>
      <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <p class="text-2xl font-bold text-pink-600">{{ stats.active_appointments }}</p>
        <p class="text-sm text-gray-500 mt-1">Active Appts</p>
      </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-8">
      <!-- Users Management -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
          <h3 class="text-lg font-bold text-gray-900">Users</h3>
          <span class="text-xs text-gray-400">{{ users.length }} loaded</span>
        </div>
        
        <div v-if="loadingUsers" class="p-12 flex justify-center">
          <div class="animate-spin rounded-full h-8 w-8 border-4 border-blue-200 border-t-blue-600"></div>
        </div>
        
        <ul v-else class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
          <li v-for="user in users" :key="user.id" class="px-6 py-3 flex items-center justify-between hover:bg-gray-50 transition">
            <div class="flex items-center gap-3 min-w-0">
              <div :class="['w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0',
                user.user_type === 'admin' ? 'bg-pink-500' : user.user_type === 'doctor' ? 'bg-indigo-500' : 'bg-blue-500']">
                {{ user.full_name?.charAt(0) }}
              </div>
              <div class="min-w-0">
                <p class="font-medium text-gray-900 text-sm truncate">{{ user.full_name }}</p>
                <p class="text-xs text-gray-500">{{ user.phone }} · {{ user.user_type }}</p>
              </div>
            </div>
            <button v-if="user.user_type !== 'admin'" @click="deleteUser(user.id)" class="text-red-400 hover:text-red-600 text-xs flex-shrink-0 ml-2">
              Delete
            </button>
          </li>
        </ul>
      </div>

      <!-- Specializations Management -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100">
          <h3 class="text-lg font-bold text-gray-900">Specializations</h3>
        </div>
        
        <!-- Add New -->
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
          <form @submit.prevent="addSpecialization" class="flex gap-3">
            <input v-model="newSpecName" type="text" required placeholder="New specialization name..." class="flex-1 px-4 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
            <button type="submit" class="px-4 py-2 rounded-xl text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 transition">Add</button>
          </form>
        </div>

        <ul class="divide-y divide-gray-100 max-h-80 overflow-y-auto">
          <li v-for="spec in specializations" :key="spec.id" class="px-6 py-3 flex items-center justify-between hover:bg-gray-50 transition">
            <span class="text-sm font-medium text-gray-900">{{ spec.name }}</span>
            <button @click="deleteSpecialization(spec.id)" class="text-red-400 hover:text-red-600 text-xs">Delete</button>
          </li>
        </ul>
      </div>
    </div>

    <!-- All Bookings -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mt-8">
      <div class="px-6 py-5 border-b border-gray-100">
        <h3 class="text-lg font-bold text-gray-900">All Bookings</h3>
      </div>
      
      <div v-if="loadingBookings" class="p-12 flex justify-center">
        <div class="animate-spin rounded-full h-8 w-8 border-4 border-blue-200 border-t-blue-600"></div>
      </div>
      
      <div v-else-if="allBookings.length === 0" class="px-6 py-12 text-center text-gray-500">
        No bookings in the system yet.
      </div>

      <div v-else class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hospital</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="b in allBookings" :key="b.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ b.patient?.full_name || 'N/A' }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ b.appointment?.hospital_name || 'N/A' }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ b.appointment_date }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ b.schedule?.available_start_time || 'N/A' }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Confirmed</span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../../api';
import { useNotificationStore } from '../../stores/notification';

const notifStore = useNotificationStore();

const stats = ref(null);
const users = ref([]);
const specializations = ref([]);
const allBookings = ref([]);

const loadingUsers = ref(true);
const loadingBookings = ref(true);
const newSpecName = ref('');

onMounted(async () => {
  // Fetch stats
  try {
    const res = await api.get('/admin/statistics');
    stats.value = res.data;
  } catch (e) { /* ignore */ }

  // Fetch users
  try {
    const res = await api.get('/admin/users');
    users.value = res.data.data || res.data;
  } catch (e) { /* ignore */ }
  loadingUsers.value = false;

  // Fetch specializations
  try {
    const res = await api.get('/specialists');
    specializations.value = res.data.data || res.data;
  } catch (e) { /* ignore */ }

  // Fetch all bookings
  try {
    const res = await api.get('/admin/bookings');
    allBookings.value = res.data.data || res.data;
  } catch (e) { /* ignore */ }
  loadingBookings.value = false;
});

const deleteUser = async (id) => {
  if (!confirm('Are you sure you want to delete this user?')) return;
  try {
    await api.delete(`/admin/users/${id}`);
    users.value = users.value.filter(u => u.id !== id);
    notifStore.success('User deleted');
  } catch (err) {
    notifStore.error(err.response?.data?.message || 'Failed to delete user');
  }
};

const addSpecialization = async () => {
  if (!newSpecName.value.trim()) return;
  try {
    const res = await api.post('/specialists', { name: newSpecName.value.trim() });
    specializations.value.push(res.data.category);
    newSpecName.value = '';
    notifStore.success('Specialization added');
  } catch (err) {
    notifStore.error(err.response?.data?.message || 'Failed to add');
  }
};

const deleteSpecialization = async (id) => {
  if (!confirm('Delete this specialization?')) return;
  try {
    await api.delete(`/specialists/${id}`);
    specializations.value = specializations.value.filter(s => s.id !== id);
    notifStore.success('Specialization deleted');
  } catch (err) {
    notifStore.error(err.response?.data?.message || 'Failed to delete');
  }
};
</script>
