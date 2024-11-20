<div 
  x-data="{ 
    notifications: [],
    remove(id) {
      this.notifications = this.notifications.filter(n => n.id !== id);
    }
  }"
  class="fixed top-4 right-4 z-50 space-y-4"
>
  <template x-for="notification in $store.notifications.items" :key="notification.id">
    <div
      x-show="true"
      x-transition:enter="transform ease-out duration-300 transition"
      x-transition:enter-start="translate-y-2 opacity-0"
      x-transition:enter-end="translate-y-0 opacity-100"
      x-transition:leave="transition ease-in duration-100"
      x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0"
      :class="{
        'bg-red-100 border-red-400 text-red-700': notification.type === 'error',
        'bg-green-100 border-green-400 text-green-700': notification.type === 'success',
        'bg-blue-100 border-blue-400 text-blue-700': notification.type === 'info'
      }"
      class="rounded-lg border-l-4 p-4 shadow-lg"
    >
      <div class="flex items-center justify-between">
        <p class="text-sm" x-text="notification.message"></p>
        <button 
          @click="$store.notifications.items = $store.notifications.items.filter(n => n.id !== notification.id)" 
          class="text-gray-400 hover:text-gray-600"
        >
          <span class="sr-only">Tutup</span>
          <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/>
          </svg>
        </button>
      </div>
    </div>
  </template>
</div> 