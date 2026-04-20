<template>
  <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden flex flex-col h-full cursor-pointer group" @click="$emit('select', doctor)">
    <div class="p-6 flex-grow">
      <div class="flex items-center gap-4 mb-4">
        <div class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-white flex items-center justify-center font-bold text-lg flex-shrink-0 shadow-md">
          {{ doctor.user?.full_name?.charAt(0) || 'D' }}
        </div>
        <div class="min-w-0">
          <h3 class="text-base font-bold text-gray-900 truncate">{{ doctor.user?.full_name || 'Doctor' }}</h3>
          <p class="text-sm font-medium text-blue-600">{{ doctor.specialized_category?.name || 'General' }}</p>
        </div>
      </div>
      
      <div class="space-y-2 text-sm text-gray-500">
        <div class="flex items-center gap-2">
          <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
          {{ doctor.years_of_experience || 0 }} Years Experience
        </div>
      </div>
    </div>
    
    <div class="px-6 py-3.5 bg-gray-50 border-t border-gray-100 flex justify-between items-center group-hover:bg-blue-50 transition-colors">
      <span class="font-medium text-sm text-gray-700 group-hover:text-blue-700 transition-colors">View Details</span>
      <span class="flex h-7 w-7 items-center justify-center rounded-full bg-blue-100 text-blue-600 group-hover:bg-blue-200 transition-colors">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
      </span>
    </div>
  </div>
</template>

<script setup>
defineProps({
  doctor: {
    type: Object,
    required: true
  }
});

defineEmits(['select']);
</script>
