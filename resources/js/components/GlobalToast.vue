<template>
  <div class="fixed top-4 right-4 z-[100] flex flex-col gap-2 pointer-events-none">
    <transition-group name="toast">
      <div 
        v-for="notification in notifications" 
        :key="notification.id"
        :class="[
          'px-5 py-3 rounded-lg shadow-lg text-sm text-white flex items-center justify-between min-w-[320px] pointer-events-auto',
          notification.type === 'success' ? 'bg-emerald-600' : '',
          notification.type === 'error' ? 'bg-red-600' : '',
          notification.type === 'info' ? 'bg-blue-600' : ''
        ]"
      >
        <div class="flex items-center gap-2">
          <span v-if="notification.type === 'success'">✓</span>
          <span v-else-if="notification.type === 'error'">✕</span>
          <span v-else>ℹ</span>
          <span>{{ notification.message }}</span>
        </div>
        <button @click="notifStore.remove(notification.id)" class="ml-4 text-white/70 hover:text-white text-lg leading-none">
          &times;
        </button>
      </div>
    </transition-group>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useNotificationStore } from '../stores/notification';

const notifStore = useNotificationStore();
const notifications = computed(() => notifStore.notifications);
</script>

<style scoped>
.toast-enter-active,
.toast-leave-active {
  transition: all 0.3s ease;
}
.toast-enter-from {
  opacity: 0;
  transform: translateX(30px);
}
.toast-leave-to {
  opacity: 0;
  transform: translateX(30px) scale(0.95);
}
</style>
