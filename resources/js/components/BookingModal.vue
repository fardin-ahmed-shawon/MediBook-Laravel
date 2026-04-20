<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 bg-black/50 transition-opacity" @click="$emit('close')"></div>
      <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
      <div class="inline-block align-bottom bg-white rounded-2xl px-6 pt-6 pb-5 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-10">
        <div>
          <h3 class="text-lg font-bold text-gray-900 mb-4">Book Appointment</h3>
          
          <div class="bg-blue-50 p-4 rounded-xl mb-5 border border-blue-100 space-y-1">
            <p class="text-sm text-blue-900 font-semibold">Appointment Summary</p>
            <p class="text-sm text-blue-700">📍 {{ appointment?.hospital_name }} — {{ appointment?.chamber_location }}</p>
            <p class="text-sm text-blue-700">📅 {{ schedule?.appointment_day }}</p>
            <p class="text-sm text-blue-700">🕐 {{ schedule?.available_start_time }}</p>
            <p class="text-sm text-blue-700">💰 BDT {{ appointment?.visiting_fee }}</p>
          </div>
          
          <form @submit.prevent="confirmBooking">
            <div class="mb-5">
              <label class="block text-sm font-medium text-gray-700 mb-1">Select Appointment Date</label>
              <input type="date" required v-model="form.appointment_date" 
                class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
              <p class="text-xs text-gray-500 mt-1">Please select a future {{ schedule?.appointment_day }}.</p>
            </div>
            
            <div class="flex gap-3 justify-end">
              <button type="button" @click="$emit('close')" 
                class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
                Cancel
              </button>
              <button type="submit" :disabled="loading" 
                class="px-5 py-2.5 border border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition disabled:opacity-60">
                {{ loading ? 'Booking...' : 'Confirm Booking' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import api from '../api';
import { useNotificationStore } from '../stores/notification';

const props = defineProps({
  show: Boolean,
  appointment: Object,
  schedule: Object
});

const emit = defineEmits(['close', 'booked']);
const notifStore = useNotificationStore();
const loading = ref(false);

const form = reactive({
  appointment_date: ''
});

const confirmBooking = async () => {
  if (!form.appointment_date) return;
  
  loading.value = true;
  try {
    await api.post('/bookings', {
      appointment_id: props.appointment.id,
      appointment_schedule_id: props.schedule.id,
      appointment_date: form.appointment_date
    });
    
    notifStore.success('Appointment booked successfully!');
    form.appointment_date = '';
    emit('booked');
    emit('close');
  } catch (error) {
    const msg = error.response?.data?.message || error.response?.data?.errors?.appointment_date?.[0] || 'Failed to book appointment';
    notifStore.error(msg);
  } finally {
    loading.value = false;
  }
};
</script>
