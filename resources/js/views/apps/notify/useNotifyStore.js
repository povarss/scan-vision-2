export const useNotifyStore = defineStore("notify", {
  // ℹ️ arrow function recommended for full type inference
  state: () => ({
    isOpen: false,
    title: null,
    message: null,
  }),
  actions: {
    showNotification(title, message) {
      this.isOpen = true;
      this.title = title;
      this.message = message;
    },
    hideNotification() {
      this.isOpen = false;
      this.title = null;
      this.message = null;
    },
  },
});
