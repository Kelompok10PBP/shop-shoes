<x-app-layout title="Shopping Bag | Stride Footwear">
    <div class="max-w-[1440px] mx-auto w-full px-margin-mobile lg:px-margin py-space-lg">
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-on-surface-variant font-label-sm text-label-sm mb-space-md">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-on-surface font-bold">Shopping Bag</span>
        </nav>

        <div class="flex items-center justify-between mb-space-lg">
            <div>
                <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight font-bold">Your Bag</h1>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1">Review your selected performance footwear before moving to checkout.</p>
            </div>
            <a href="{{ route('shop') }}" class="font-label-md text-label-md text-primary font-bold hover:underline flex items-center gap-1">
                <span>Continue Shopping</span>
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </a>
        </div>

        @if(empty($cart['items']))
            <div class="py-20 text-center flex flex-col items-center justify-center bg-surface-container-lowest rounded-2xl border border-surface-container/40 p-space-xl">
                <div class="w-20 h-20 rounded-full bg-surface-container flex items-center justify-center mb-4 text-outline">
                    <span class="material-symbols-outlined text-4xl">shopping_bag</span>
                </div>
                <h2 class="font-headline-sm text-2xl text-on-surface font-bold mb-2">Your Bag is Empty</h2>
                <p class="font-body-md text-body-md text-on-surface-variant max-w-md mb-8">
                    Looks like you haven't added any shoes to your bag yet. Explore our latest road, trail, and recovery footwear.
                </p>
                <a href="{{ route('shop') }}" class="h-12 px-8 rounded-lg bg-primary text-on-primary font-label-lg text-label-lg hover:bg-primary-container transition-colors shadow-sm font-semibold flex items-center gap-2">
                    <span class="material-symbols-outlined">explore</span>
                    <span>Browse All Shoes</span>
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter">
                
                <!-- Items Column (8 cols) -->
                <div class="lg:col-span-8 flex flex-col gap-4">
                    
                    <!-- Free shipping indicator -->
                    <div class="p-4 rounded-xl bg-surface-container-low border border-surface-container flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-2xl">local_shipping</span>
                            <div>
                                <p class="font-label-md text-sm text-on-surface font-bold">
                                    {{ $cart['free_shipping_unlocked'] ? 'Free Standard Shipping Unlocked!' : 'Add $' . number_format($cart['free_shipping_remaining'], 2) . ' more for Free Shipping' }}
                                </p>
                                <div class="w-48 sm:w-64 h-1.5 bg-surface-variant rounded-full overflow-hidden mt-1.5">
                                    <div class="h-full bg-primary rounded-full transition-all duration-300" style="width: {{ $cart['free_shipping_progress'] }}%"></div>
                                </div>
                            </div>
                        </div>
                        <span class="font-label-sm text-xs font-bold text-primary px-2.5 py-1 rounded-full bg-secondary-fixed">
                            {{ $cart['free_shipping_unlocked'] ? 'ELIGIBLE' : $cart['free_shipping_progress'] . '%' }}
                        </span>
                    </div>

                    <!-- Items Card -->
                    <div class="bg-surface-container-lowest rounded-2xl border border-surface-container/40 p-space-md lg:p-space-lg shadow-sm divide-y divide-surface-container">
                        @foreach($cart['items'] as $item)
                            <div class="py-space-md first:pt-0 last:pb-0 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-20 h-20 bg-surface-container rounded-xl overflow-hidden flex-shrink-0 flex items-center justify-center p-2">
                                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-contain" />
                                    </div>
                                    <div>
                                        <h3 class="font-headline-sm text-base text-on-surface font-bold">{{ $item['name'] }}</h3>
                                        <p class="font-body-sm text-xs text-on-surface-variant mt-0.5">
                                            {{ $item['variant_label'] ?: 'Standard' }}
                                        </p>
                                        <p class="font-mono text-sm font-bold text-primary mt-1">
                                            Rp {{ number_format($item['unit_price'], 0, ',', '.') }} each
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto">
                                    <!-- Stepper using alpine store -->
                                    <div class="flex items-center bg-surface-container-low rounded-lg p-1 gap-2 border border-surface-container">
                                        <button 
                                            type="button" 
                                            @click="$store.cart.updateQuantity({{ $item['id'] }}, {{ $item['quantity'] - 1 }}); setTimeout(() => location.reload(), 400);" 
                                            class="w-7 h-7 rounded hover:bg-surface-container flex items-center justify-center font-bold text-on-surface"
                                        >-</button>
                                        <span class="font-label-md text-sm font-bold w-6 text-center text-on-surface">{{ $item['quantity'] }}</span>
                                        <button 
                                            type="button" 
                                            @click="$store.cart.updateQuantity({{ $item['id'] }}, {{ $item['quantity'] + 1 }}); setTimeout(() => location.reload(), 400);" 
                                            class="w-7 h-7 rounded hover:bg-surface-container flex items-center justify-center font-bold text-on-surface"
                                        >+</button>
                                    </div>

                                    <div class="text-right min-w-[80px]">
                                        <span class="font-mono text-base font-bold text-on-surface">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                                    </div>

                                    <button 
                                        type="button" 
                                        @click="$store.cart.remove({{ $item['id'] }}); setTimeout(() => location.reload(), 400);" 
                                        class="text-outline hover:text-error transition-colors p-1"
                                        title="Remove item"
                                    >
                                        <span class="material-symbols-outlined text-lg">delete</span>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>

                <!-- Summary Column (4 cols) -->
                <div class="lg:col-span-4">
                    <div class="bg-surface-container-lowest rounded-2xl border border-surface-container/40 p-space-md lg:p-space-lg shadow-sm sticky top-32">
                        
                        <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold pb-3 border-b border-surface-container mb-4">
                            Summary
                        </h2>

                        <!-- Coupon Form -->
                        <form action="{{ route('cart.coupon.apply') }}" method="POST" class="mb-4">
                            @csrf
                            <label class="block font-label-sm text-xs font-semibold text-on-surface mb-1.5">Have a Promo Code?</label>
                            <div class="flex gap-2">
                                <input 
                                    type="text" 
                                    name="code" 
                                    placeholder="Enter coupon code" 
                                    class="flex-1 h-10 px-3 rounded-lg bg-surface-container-low border border-surface-container text-xs uppercase font-mono font-bold focus:border-primary focus:outline-none"
                                />
                                <button type="submit" class="h-10 px-4 rounded-lg bg-surface-container-high hover:bg-surface-container text-on-surface text-xs font-bold transition-colors">
                                    Apply
                                </button>
                            </div>
                        </form>

                        <div class="flex flex-col gap-2.5 text-sm font-body-sm pt-3 border-t border-surface-container">
                            <div class="flex justify-between text-on-surface-variant">
                                <span>Subtotal</span>
                                <span class="font-mono font-semibold text-on-surface">Rp {{ number_format($cart['subtotal'], 0, ',', '.') }}</span>
                            </div>

                            @if(($cart['discount_amount'] ?? 0) > 0)
                                <div class="flex justify-between text-primary font-semibold">
                                    <span>Discount Applied</span>
                                    <span class="font-mono">-Rp {{ number_format($cart['discount_amount'], 0, ',', '.') }}</span>
                                </div>
                            @endif

                            <div class="flex justify-between text-on-surface-variant">
                                <span>Estimated Shipping</span>
                                <span class="font-semibold text-on-surface">
                                    {{ $cart['free_shipping_unlocked'] ? 'GRATIS' : 'Rp 20.000' }}
                                </span>
                            </div>

                            <div class="flex justify-between items-baseline pt-3 mt-1 border-t border-surface-container">
                                <span class="font-headline-sm text-base font-bold text-on-surface">Estimated Total</span>
                                <span class="font-headline-lg text-2xl font-bold text-primary font-mono">
                                    Rp {{ number_format($cart['subtotal'] - ($cart['discount_amount'] ?? 0) + ($cart['free_shipping_unlocked'] ? 0 : 20000), 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <a 
                            href="{{ route('checkout.index') }}" 
                            class="w-full h-12 rounded-lg bg-primary text-on-primary hover:bg-primary-container font-label-lg text-label-lg transition-all flex items-center justify-center gap-2 mt-6 shadow-sm font-semibold"
                        >
                            <span class="material-symbols-outlined">lock</span>
                            <span>Proceed to Checkout</span>
                        </a>

                        <div class="mt-4 text-center">
                            <span class="text-[11px] text-outline font-label-sm">Free returns within 30 days &bull; Secure checkout</span>
                        </div>

                    </div>
                </div>

            </div>
        @endif

    </div>
</x-app-layout>
