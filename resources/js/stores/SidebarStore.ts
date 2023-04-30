import { defineStore } from "pinia";
import { ref } from "vue";


export const useSidebarStore = defineStore('sidebar', () => {
  const isOpen = ref(false);
  function toggleSidebar() {
    isOpen.value = !isOpen;
  }

  return { isOpen, toggleSidebar }
})