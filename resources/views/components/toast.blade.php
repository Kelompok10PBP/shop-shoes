<div 
    x-data
    x-show="$store.toast.visible" 
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    class="fixed bottom-6 right-6 z-50 max-w-sm w-full"
    style="display: none;"
>
    <div 
        :class="{
            'bg-primary text-on-primary': $store.toast.type === 'success',
            'bg-error text-on-error': $store.toast.type === 'error',
            'bg-surface-container-highest text-on-surface': $store.toast.type === 'info'
        }"
        class="rounded-xl shadow-xl p-4 flex items-center justify-between gap-3 border border-surface-container"
    >
        <div class="flex items-center gap-2.5">
            <template x-if="$store.toast.type === 'success'">
                <span class="material-symbols-outlined text-xl text-primary-fixed">check_circle</span>
            </template>
            <template x-if="$store.toast.type === 'error'">
                <span class="material-symbols-outlined text-xl text-error-container">error</span>
            </template>
            <template x-if="$store.toast.type === 'info'">
                <span class="material-symbols-outlined text-xl text-primary">info</span>
            </template>
            <span class="font-label-md text-label-md font-medium" x-text="$store.toast.message"></span>
        </div>
        
        <button @click="$store.toast.close()" class="opacity-75 hover:opacity-100 p-1" type="button" aria-label="Dismiss toast">
            <span class="material-symbols-outlined text-base">close</span>
        </button>
    </div>
</div>
