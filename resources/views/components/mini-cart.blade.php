<!-- Cart Backdrop -->
<div 
    x-show="$store.cart.isOpen" 
    x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click="$store.cart.close()" 
    @keydown.escape.window="$store.cart.close()"
    class="fixed inset-0 bg-on-surface/40 z-50 backdrop-blur-xs"
    style="display: none;"
></div>

<!-- Cart Drawer Slide-over -->
<aside 
    x-show="$store.cart.isOpen"
    x-transition:enter="transition ease-in-out duration-300 transform"
    x-transition:enter-start="translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in-out duration-300 transform"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="translate-x-full"
    class="fixed top-0 right-0 w-full max-w-md h-full bg-surface-container-lowest z-50 shadow-[0_4px_6px_-1px_rgba(0,0,0,0.05),0_2px_4px_-2px_rgba(0,0,0,0.05)] flex flex-col"
    style="display: none;"
>
    <!-- Header -->
    <div class="p-space-md flex items-center justify-between border-b border-surface-container">
        <div class="flex items-center gap-space-xs">
            <span class="font-headline-sm text-headline-sm text-on-surface">Your Bag</span>
            <span class="font-label-sm text-label-sm text-on-surface-variant" x-text="`(${$store.cart.totalItems} Items)`"></span>
        </div>
        <button @click="$store.cart.close()" class="w-8 h-8 flex items-center justify-center text-on-surface-variant hover:text-on-surface hover:bg-surface-container rounded-full transition-colors" type="button" aria-label="Close bag">
            <span class="material-symbols-outlined text-xl">close</span>
        </button>
    </div>

    <!-- Free Shipping Progress -->
    <div class="px-space-md py-space-sm bg-surface-container-low border-b border-surface-container">
        <div class="flex items-center justify-between font-label-sm text-label-sm text-on-surface-variant mb-1">
            <span x-text="$store.cart.freeShippingUnlocked ? 'Gratis Ongkir Diaktifkan' : `Tambah Rp ${Number($store.cart.freeShippingRemaining).toLocaleString('id-ID')} lagi untuk Gratis Ongkir`"></span>
            <span class="font-bold text-primary" x-text="$store.cart.freeShippingUnlocked ? 'Unlocked!' : `${$store.cart.freeShippingProgress}%`"></span>
        </div>
        <div class="w-full h-1.5 bg-surface-variant rounded-full overflow-hidden">
            <div class="h-full bg-primary-container rounded-full transition-all duration-500" :style="`width: ${$store.cart.freeShippingProgress}%`"></div>
        </div>
    </div>

    <!-- Cart Items List -->
    <div class="flex-1 overflow-y-auto px-space-md py-space-sm divide-y divide-surface-container">
        
        <!-- Empty State -->
        <template x-if="$store.cart.items.length === 0">
            <div class="py-16 text-center flex flex-col items-center justify-center">
                <div class="w-16 h-16 rounded-full bg-surface-container flex items-center justify-center mb-4 text-outline">
                    <span class="material-symbols-outlined text-3xl">shopping_bag</span>
                </div>
                <p class="font-headline-sm text-headline-sm text-on-surface mb-2">Your Bag is Empty</p>
                <p class="font-body-md text-body-md text-on-surface-variant max-w-xs mb-6">Discover our biomechanically engineered footwear and find your perfect stride.</p>
                <a @click="$store.cart.close()" href="{{ route('shop') }}" class="px-6 py-2.5 rounded-lg bg-primary text-on-primary font-label-md text-label-md hover:bg-primary-container transition-colors">
                    Explore Footwear
                </a>
            </div>
        </template>

        <!-- Reactive Item List -->
        <template x-for="item in $store.cart.items" :key="item.id">
            <div class="py-space-md flex gap-space-md">
                <!-- Thumbnail -->
                <div class="w-20 h-20 bg-surface-container rounded-lg flex-shrink-0 overflow-hidden flex items-center justify-center">
                    <img :src="item.image" :alt="item.name" class="w-full h-full object-cover" />
                </div>
                
                <!-- Details & Stepper -->
                <div class="flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between font-label-lg text-label-lg text-on-surface">
                            <span x-text="item.name" class="font-bold line-clamp-1"></span>
                            <span x-text="`Rp ${Number(item.unit_price).toLocaleString('id-ID')}`"></span>
                        </div>
                        <div class="font-body-sm text-body-sm text-on-surface-variant mt-0.5" x-text="item.variant_label || 'Standard Edition'"></div>
                    </div>

                    <div class="flex items-center justify-between mt-space-sm">
                        <!-- Qty Stepper -->
                        <div class="flex items-center bg-surface-container-low rounded-lg px-2 py-1 gap-2">
                            <button @click="$store.cart.updateQuantity(item.id, item.quantity - 1)" class="text-on-surface-variant hover:text-on-surface text-xs font-bold w-4 h-4 flex items-center justify-center" type="button" aria-label="Decrease quantity">-</button>
                            <span class="font-label-sm text-label-sm text-on-surface" x-text="item.quantity"></span>
                            <button @click="$store.cart.updateQuantity(item.id, item.quantity + 1)" class="text-on-surface-variant hover:text-on-surface text-xs font-bold w-4 h-4 flex items-center justify-center" type="button" aria-label="Increase quantity">+</button>
                        </div>

                        <!-- Remove Button -->
                        <button @click="$store.cart.remove(item.id)" class="font-label-sm text-label-sm text-outline hover:text-error transition-colors" type="button">
                            Remove
                        </button>
                    </div>
                </div>
            </div>
        </template>

    </div>

    <!-- Footer Checkout Summary -->
    <div x-show="$store.cart.items.length > 0" class="p-space-md bg-surface-container-lowest border-t border-surface-container shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
        <div class="flex justify-between font-headline-sm text-headline-sm text-on-surface mb-space-sm">
            <span>Subtotal</span>
            <span x-text="`Rp ${Number($store.cart.subtotal).toLocaleString('id-ID')}`" class="font-bold"></span>
        </div>
        <p class="font-body-sm text-body-sm text-on-surface-variant mb-space-md">
            Taxes and calculated shipping calculated at checkout.
        </p>
        <a href="{{ route('checkout.index') }}" class="block w-full text-center bg-primary-container text-on-primary py-3 rounded-lg font-label-lg text-label-lg hover:bg-primary transition-colors mb-2 shadow-sm">
            Proceed to Checkout
        </a>
        <a href="{{ route('cart.index') }}" class="block w-full text-center bg-surface-container-low text-on-surface py-3 rounded-lg font-label-lg text-label-lg hover:bg-surface-container transition-colors">
            View Full Cart
        </a>
    </div>
</aside>
