export const useNotifyStore = defineStore("notify", {
  // ℹ️ arrow function recommended for full type inference
  state: () => ({
    isOpen: false,
    title: null,
    message: null,
    showOk: false,
  }),
  actions: {
    showNotification(title, message, showOk = false) {
      this.isOpen = true;
      this.title = title;
      this.message = message;
      this.showOk = showOk;
    },
    hideNotification() {
      this.isOpen = false;
      this.title = null;
      this.message = null;
    },
  },
});
