<div 
  x-data="{ notifications: [] }"
  class="fixed top-4 right-4 z-50 space-y-4"
>
  <template x-for="notification in $store.notifications.items" :key="notification.id">
    <div
      :data-notification-id="notification.id"
      x-show="true"
      :class="{
        'bg-red-100 border-t-4 border-red-500 text-red-900': notification.type === 'error',
        'bg-teal-100 border-t-4 border-teal-500 text-teal-900': notification.type === 'success',
        'bg-blue-100 border-t-4 border-blue-500 text-blue-900': notification.type === 'info'
      }"
      class="rounded-b px-4 py-3 shadow-md relative transform transition-all duration-300"
      role="alert"
    >
      <div class="flex items-center">
        <div class="py-1">
          <svg class="fill-current h-6 w-6 mr-4" :class="{
            'text-red-500': notification.type === 'error',
            'text-teal-500': notification.type === 'success', 
            'text-blue-500': notification.type === 'info'
          }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
            <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
          </svg>
        </div>
        <div>
          <p class="font-bold" x-text="notification.message"></p>
        </div>
        <button 
          @click="$store.notifications.remove(notification.id)"
          class="absolute top-0 right-0 mt-2 mr-2 text-gray-600 hover:text-gray-800 transition-colors duration-200"
        >
          <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>
  </template>
</div> 