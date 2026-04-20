<template>
  <div>
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800">
      <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-72 h-72 bg-white rounded-full -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full translate-x-1/3 translate-y-1/3"></div>
      </div>
      
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28 lg:py-32">
        <div class="lg:grid lg:grid-cols-12 lg:gap-12">
          <div class="lg:col-span-7 text-center lg:text-left">
            <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white/20 text-white tracking-wide uppercase mb-6 backdrop-blur-sm">
              ✦ Trusted by thousands
            </div>
            <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">
              Find the right doctor,
              <span class="block text-blue-200 mt-2">book instantly.</span>
            </h1>
            <p class="mt-6 text-lg text-blue-100 max-w-xl mx-auto lg:mx-0 leading-relaxed">
              Your health journey made simple. Browse top specialists, view real-time availability, and secure your appointment in seconds.
            </p>
            <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
              <router-link to="/doctors" class="inline-flex items-center justify-center px-8 py-3.5 text-base font-semibold rounded-xl text-blue-700 bg-white hover:bg-blue-50 shadow-lg shadow-blue-900/20 transition-all hover:-translate-y-0.5">
                Find a Doctor
              </router-link>
              <router-link to="/register" class="inline-flex items-center justify-center px-8 py-3.5 text-base font-semibold rounded-xl text-white border-2 border-white/30 hover:bg-white/10 transition-all">
                Register as Doctor
              </router-link>
            </div>
          </div>
          
          <!-- Stats Cards -->
          <div class="hidden lg:flex lg:col-span-5 items-center">
            <div class="grid grid-cols-2 gap-4 w-full">
              <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                <div class="text-3xl font-bold text-white">50+</div>
                <div class="text-blue-200 text-sm mt-1">Verified Doctors</div>
              </div>
              <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                <div class="text-3xl font-bold text-white">8</div>
                <div class="text-blue-200 text-sm mt-1">Specializations</div>
              </div>
              <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                <div class="text-3xl font-bold text-white">24/7</div>
                <div class="text-blue-200 text-sm mt-1">Online Booking</div>
              </div>
              <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                <div class="text-3xl font-bold text-white">1K+</div>
                <div class="text-blue-200 text-sm mt-1">Happy Patients</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Specializations Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-gray-900">Browse by Specialization</h2>
        <p class="mt-3 text-gray-500">Find the right specialist for your needs</p>
      </div>
      
      <div v-if="specializations.length > 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
        <router-link 
          v-for="spec in specializations" :key="spec.id"
          :to="`/doctors?specialization=${spec.id}`"
          class="bg-white border border-gray-200 rounded-xl p-5 text-center hover:shadow-md hover:border-blue-200 hover:-translate-y-1 transition-all duration-300 group"
        >
          <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-blue-100 transition-colors">
            <span class="text-blue-600 text-xl">🏥</span>
          </div>
          <h3 class="font-semibold text-gray-900 text-sm">{{ spec.name }}</h3>
        </router-link>
      </div>
    </div>

    <!-- How It Works -->
    <div class="bg-gray-50 py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-bold text-gray-900">How It Works</h2>
          <p class="mt-3 text-gray-500">Get your appointment in 3 simple steps</p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8">
          <div class="text-center">
            <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-5 text-white text-2xl font-bold shadow-lg shadow-blue-600/30">1</div>
            <h3 class="font-bold text-gray-900 text-lg mb-2">Search Doctor</h3>
            <p class="text-gray-500 text-sm leading-relaxed">Browse our extensive list of verified doctors by specialization, location, or name.</p>
          </div>
          <div class="text-center">
            <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-5 text-white text-2xl font-bold shadow-lg shadow-blue-600/30">2</div>
            <h3 class="font-bold text-gray-900 text-lg mb-2">Pick a Schedule</h3>
            <p class="text-gray-500 text-sm leading-relaxed">Choose from available time slots that work for you. View chamber locations and fees upfront.</p>
          </div>
          <div class="text-center">
            <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-5 text-white text-2xl font-bold shadow-lg shadow-blue-600/30">3</div>
            <h3 class="font-bold text-gray-900 text-lg mb-2">Confirm Booking</h3>
            <p class="text-gray-500 text-sm leading-relaxed">Book instantly online. Get confirmation and manage your appointments from your dashboard.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../api';

const specializations = ref([]);

onMounted(async () => {
  try {
    const response = await api.get('/specialists');
    specializations.value = response.data.data || response.data;
  } catch (e) {
    // silently fail on home page
  }
});
</script>
