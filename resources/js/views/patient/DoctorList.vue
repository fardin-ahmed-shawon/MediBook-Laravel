<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="md:flex md:items-center md:justify-between mb-8">
      <div>
        <h2 class="text-3xl font-extrabold tracking-tight text-gray-900">Find a Doctor</h2>
        <p class="mt-2 text-gray-500">Book your next appointment from our trusted specialists.</p>
      </div>
      <div class="mt-4 md:mt-0 flex gap-3">
        <input v-model="searchQuery" type="text" placeholder="Search by name..." 
          class="px-4 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent w-56"
          @input="handleSearch" />
      </div>
    </div>

    <!-- Filter by specialization -->
    <div class="flex flex-wrap gap-2 mb-8" v-if="specializations.length > 0">
      <button @click="filterSpec = null" 
        :class="['px-4 py-2 rounded-full text-sm font-medium border transition', !filterSpec ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 border-gray-200 hover:border-blue-300']">
        All
      </button>
      <button v-for="spec in specializations" :key="spec.id" @click="filterSpec = spec.id"
        :class="['px-4 py-2 rounded-full text-sm font-medium border transition', filterSpec === spec.id ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 border-gray-200 hover:border-blue-300']">
        {{ spec.name }}
      </button>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="flex justify-center py-20">
      <div class="animate-spin rounded-full h-12 w-12 border-4 border-blue-200 border-t-blue-600"></div>
    </div>
    
    <!-- Error -->
    <div v-else-if="error" class="bg-red-50 text-red-600 p-4 rounded-xl border border-red-100 text-center">
      {{ error }}
    </div>

    <!-- Empty -->
    <div v-else-if="doctors.length === 0" class="text-center py-20 bg-white rounded-2xl border border-gray-100">
      <div class="text-4xl mb-3">🔍</div>
      <h3 class="text-lg font-semibold text-gray-900">No doctors found</h3>
      <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter criteria.</p>
    </div>

    <!-- Doctor Grid -->
    <div v-else class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
      <DoctorCard 
        v-for="doctor in doctors" 
        :key="doctor.id" 
        :doctor="doctor"
        @select="goToDoctor"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import api from '../../api';
import DoctorCard from '../../components/DoctorCard.vue';
import { useNotificationStore } from '../../stores/notification';

const router = useRouter();
const route = useRoute();
const notifStore = useNotificationStore();

const doctors = ref([]);
const specializations = ref([]);
const loading = ref(true);
const error = ref(null);
const searchQuery = ref('');
const filterSpec = ref(null);

onMounted(async () => {
  // Check for specialization query param from home page
  if (route.query.specialization) {
    filterSpec.value = parseInt(route.query.specialization);
  }

  try {
    const specRes = await api.get('/specialists');
    specializations.value = specRes.data.data || specRes.data;
  } catch (e) { /* ignore */ }

  await fetchDoctors();
});

watch(filterSpec, () => {
  fetchDoctors();
});

const fetchDoctors = async () => {
  loading.value = true;
  error.value = null;
  try {
    const params = {};
    if (filterSpec.value) params.specialized_area = filterSpec.value;
    if (searchQuery.value) params.search = searchQuery.value;
    
    const response = await api.get('/doctors', { params });
    doctors.value = response.data.data || response.data;
  } catch (err) {
    error.value = 'Failed to load doctors. Please try again.';
    notifStore.error(error.value);
  } finally {
    loading.value = false;
  }
};

let searchTimeout = null;
const handleSearch = () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    fetchDoctors();
  }, 400);
};

const goToDoctor = (doctor) => {
  router.push(`/doctors/${doctor.id}`);
};
</script>
