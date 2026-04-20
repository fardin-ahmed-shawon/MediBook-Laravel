import { defineStore } from 'pinia';

export const useNotificationStore = defineStore('notification', {
    state: () => ({
        notifications: [],
    }),
    actions: {
        add(notification) {
            const id = Date.now() + Math.random();
            this.notifications.push({
                id,
                type: notification.type || 'info',
                message: notification.message,
                duration: notification.duration || 3500,
            });

            setTimeout(() => {
                this.remove(id);
            }, notification.duration || 3500);
        },
        remove(id) {
            this.notifications = this.notifications.filter(n => n.id !== id);
        },
        success(message) {
            this.add({ type: 'success', message });
        },
        error(message) {
            this.add({ type: 'error', message });
        },
        info(message) {
            this.add({ type: 'info', message });
        }
    }
});
