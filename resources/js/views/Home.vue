<template>
  <div class="relative overflow-hidden bg-white">
    <!-- Decorative background elements -->
    <div class="hidden sm:block sm:absolute sm:inset-y-0 sm:h-full sm:w-full" aria-hidden="true">
      <div class="relative h-full max-w-7xl mx-auto">
        <svg class="absolute right-full transform translate-y-1/4 translate-x-1/4 lg:translate-x-1/2" width="404" height="784" fill="none" viewBox="0 0 404 784">
          <defs>
            <pattern id="f210dbf6-a58d-4871-961e-36d5016a0f49" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
              <rect x="0" y="0" width="4" height="4" class="text-gray-50" fill="currentColor" />
            </pattern>
          </defs>
          <rect width="404" height="784" fill="url(#f210dbf6-a58d-4871-961e-36d5016a0f49)" />
        </svg>
      </div>
    </div>

    <div class="relative pt-10 pb-16 sm:pt-16 sm:pb-24 lg:pb-32">
      <main class="mt-16 mx-auto max-w-7xl px-4 sm:mt-24 sm:px-6 lg:mt-32">
        <div class="lg:grid lg:grid-cols-12 lg:gap-8">
          <div class="sm:text-center md:max-w-2xl md:mx-auto lg:col-span-6 lg:text-left">
            <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold text-blue-600 bg-blue-50 tracking-wide uppercase mb-4 animate-fade-in-up">
              Beta Release Available
            </div>
            <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl md:text-6xl lg:text-5xl xl:text-6xl animate-fade-in-up delay-100">
              <span class="block">Find the right doctor,</span>
              <span class="block text-blue-600 xl:inline">book instantly.</span>
            </h1>
            <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0 animate-fade-in-up delay-200">
              Your health journey made simple. Browse top specialists, view real-time availability, and secure your appointment in seconds without phone calls or waiting rooms.
            </p>
            <div class="mt-8 sm:max-w-lg sm:mx-auto sm:text-center lg:text-left lg:mx-0 animate-fade-in-up delay-300">
              <div class="flex flex-col sm:flex-row gap-3">
                <button type="button" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition lg:justify-start">
                  Book an Appointment
                </button>
                <button type="button" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition lg:justify-start">
                  Register as Doctor
                </button>
              </div>
            </div>
          </div>
          <!-- Hero Graphic -->
          <div class="mt-12 relative sm:max-w-lg sm:mx-auto lg:mt-0 lg:max-w-none lg:mx-0 lg:col-span-6 lg:flex lg:items-center animate-fade-in-up delay-400">
            <div class="relative mx-auto w-full rounded-2xl shadow-xl lg:max-w-md overflow-hidden bg-white">
               <!-- Mockup of Doctor App -->
               <div class="bg-blue-50 border-b border-gray-100 px-6 py-4 flex items-center justify-between">
                 <div>
                    <h3 class="font-bold text-gray-900">Available Doctors</h3>
                    <p class="text-sm text-gray-500">Cardiology</p>
                 </div>
                 <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
               </div>
               <div class="p-6 space-y-4">
                 <div class="flex items-center space-x-4 p-4 border border-gray-100 rounded-xl hover:shadow-md transition cursor-pointer" v-for="i in 3" :key="i">
                   <div class="w-12 h-12 rounded-full bg-gray-200 relative overflow-hidden">
                      <div class="absolute inset-0 bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                        Dr
                      </div>
                   </div>
                   <div class="flex-1">
                     <h4 class="text-sm font-semibold text-gray-900">Dr. Sarah Jenkins</h4>
                     <p class="text-xs text-gray-500">10+ Years Experience</p>
                   </div>
                   <div class="text-blue-600 font-medium text-sm">
                     Book
                   </div>
                 </div>
               </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
// Homepage logic
</script>

<style>
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in-up {
  animation: fadeInUp 0.5s ease-out forwards;
  opacity: 0;
}

.delay-100 { animation-delay: 100ms; }
.delay-200 { animation-delay: 200ms; }
.delay-300 { animation-delay: 300ms; }
.delay-400 { animation-delay: 400ms; }
</style>
