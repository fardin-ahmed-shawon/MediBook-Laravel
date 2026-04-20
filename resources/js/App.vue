<template>
  <div class="min-h-screen bg-gray-50 font-sans text-gray-900">
    <nav class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <router-link to="/" class="flex-shrink-0 flex items-center gap-2 group">
              <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold group-hover:bg-blue-700 transition">
                +
              </div>
              <span class="font-bold text-xl tracking-tight text-blue-900 group-hover:text-blue-700 transition">MediBook</span>
            </router-link>
          </div>
          <div class="flex items-center space-x-4">
            <router-link to="/" class="text-gray-500 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition">Home</router-link>
            <a href="#" class="text-gray-500 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition">Find a Doctor</a>
            <a href="#" class="bg-blue-600 text-white hover:bg-blue-700 px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition transform hover:-translate-y-0.5">Login</a>
          </div>
        </div>
      </div>
    </nav>

    <main>
      <!-- Vue Router will inject components here -->
      <router-view v-slot="{ Component }">
        <transition name="fade" mode="out-in">
          <component :is="Component" />
        </transition>
      </router-view>
    </main>

    <footer class="bg-white border-t border-gray-200 mt-auto py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-gray-500 text-sm">
        &copy; {{ new Date().getFullYear() }} MediBook. All rights reserved.
      </div>
    </footer>
  </div>
</template>

<script setup>
// Root app logic goes here
</script>

<style>
/* Basic fade transition for routing */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease, transform 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(4px);
}
</style>
