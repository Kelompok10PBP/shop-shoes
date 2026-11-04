@php
    $footerCollections = \App\Models\Collection::where('is_active', true)->take(5)->get();
@endphp

<footer class="w-full bg-surface-container-low text-on-surface pt-space-xl pb-space-lg border-t border-surface-container">
    <div class="max-w-[1440px] mx-auto px-margin-mobile lg:px-margin">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-gutter mb-space-xl">
            
            <!-- Brand Column -->
            <div class="lg:col-span-2 pr-space-lg">
                <div class="flex items-center gap-space-xs mb-space-md">
                    <img alt="Stride Brand Wordmark Logo" class="h-8 w-auto object-contain" src="/images/stride-logo.svg" />
                    <span class="font-headline-sm text-headline-sm font-bold tracking-tight text-primary">STRIDE</span>
                </div>
                <p class="font-body-md text-body-md text-on-surface-variant mb-space-md max-w-sm">
                    Crafting timeless, biomechanically informed footwear with natural regenerative materials. Engineered for intentional movement and daily composure.
                </p>
                <div class="flex items-center gap-space-sm">
                    <a aria-label="Share" class="w-9 h-9 rounded-full bg-surface-container flex items-center justify-center text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors" href="https://twitter.com" target="_blank" rel="noopener">
                        <span class="material-symbols-outlined text-lg">share</span>
                    </a>
                    <a aria-label="Website" class="w-9 h-9 rounded-full bg-surface-container flex items-center justify-center text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors" href="{{ route('home') }}">
                        <span class="material-symbols-outlined text-lg">public</span>
                    </a>
                    <a aria-label="Email" class="w-9 h-9 rounded-full bg-surface-container flex items-center justify-center text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors" href="mailto:support@stridefootwear.com">
                        <span class="material-symbols-outlined text-lg">mail</span>
                    </a>
                </div>
            </div>

            <!-- Column 1: Shop -->
            <div>
                <div class="font-label-lg text-label-lg text-on-surface mb-space-md font-bold">Shop</div>
                <ul class="space-y-space-xs">
                    <li class="font-body-sm text-body-sm">
                        <a class="text-on-surface-variant hover:text-on-surface transition-colors" href="{{ route('shop', ['category' => 'running']) }}">Men's Footwear</a>
                    </li>
                    <li class="font-body-sm text-body-sm">
                        <a class="text-on-surface-variant hover:text-on-surface transition-colors" href="{{ route('shop', ['category' => 'sneakers']) }}">Women's Footwear</a>
                    </li>
                    <li class="font-body-sm text-body-sm">
                        <a class="text-on-surface-variant hover:text-on-surface transition-colors" href="{{ route('best-sellers') }}">Best Sellers</a>
                    </li>
                    <li class="font-body-sm text-body-sm">
                        <a class="text-on-surface-variant hover:text-on-surface transition-colors" href="{{ route('new-arrivals') }}">New Releases</a>
                    </li>
                    <li class="font-body-sm text-body-sm">
                        <a class="text-on-surface-variant hover:text-on-surface transition-colors" href="{{ route('shop', ['category' => 'accessories']) }}">Accessories &amp; Care</a>
                    </li>
                </ul>
            </div>

            <!-- Column 2: Collections -->
            <div>
                <div class="font-label-lg text-label-lg text-on-surface mb-space-md font-bold">Collections</div>
                <ul class="space-y-space-xs">
                    @foreach($footerCollections as $col)
                        <li class="font-body-sm text-body-sm">
                            <a class="text-on-surface-variant hover:text-on-surface transition-colors" href="{{ route('collections.show', $col->slug) }}">
                                {{ $col->name }}
                            </a>
                        </li>
                    @endforeach
                    <li class="font-body-sm text-body-sm">
                        <a class="text-on-surface-variant hover:text-on-surface transition-colors font-semibold" href="{{ route('shop', ['sale' => 1]) }}">
                            Archive Sale
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Column 3: Customer Care -->
            <div>
                <div class="font-label-lg text-label-lg text-on-surface mb-space-md font-bold">Customer Care</div>
                <ul class="space-y-space-xs">
                    <li class="font-body-sm text-body-sm">
                        <a class="text-on-surface-variant hover:text-on-surface transition-colors" href="{{ route('pages.shipping') }}">Shipping &amp; Returns</a>
                    </li>
                    <li class="font-body-sm text-body-sm">
                        <a class="text-on-surface-variant hover:text-on-surface transition-colors" href="{{ route('pages.size-guide') }}">Size Guide</a>
                    </li>
                    <li class="font-body-sm text-body-sm">
                        <a class="text-on-surface-variant hover:text-on-surface transition-colors" href="{{ route('order-tracking.index') }}">Track Your Order</a>
                    </li>
                    <li class="font-body-sm text-body-sm">
                        <a class="text-on-surface-variant hover:text-on-surface transition-colors" href="{{ route('pages.faq') }}">Help Center / FAQ</a>
                    </li>
                    <li class="font-body-sm text-body-sm">
                        <a class="text-on-surface-variant hover:text-on-surface transition-colors" href="{{ route('contact.show') }}">Contact Concierge</a>
                    </li>
                </ul>
            </div>

        </div>

        <!-- Bottom Bar -->
        <div class="pt-space-lg flex flex-col md:flex-row items-center justify-between gap-space-md border-t border-surface-container">
            <div class="flex items-center gap-space-md">
                <!-- Currency Badge -->
                <div class="flex items-center gap-2 bg-surface-container-lowest px-3 py-1.5 rounded-lg border border-surface-container">
                    <span class="material-symbols-outlined text-base text-on-surface-variant">language</span>
                    <span class="font-label-sm text-label-sm text-on-surface">Indonesia (Rp IDR)</span>
                </div>

                <!-- Payment Chips -->
                <div class="flex items-center gap-space-xs">
                    <span class="px-2 py-1 bg-surface-container-lowest rounded font-label-sm text-[10px] text-on-surface-variant font-bold border border-surface-container">QRIS</span>
                    <span class="px-2 py-1 bg-surface-container-lowest rounded font-label-sm text-[10px] text-on-surface-variant font-bold border border-surface-container">BCA</span>
                    <span class="px-2 py-1 bg-surface-container-lowest rounded font-label-sm text-[10px] text-on-surface-variant font-bold border border-surface-container">MANDIRI</span>
                    <span class="px-2 py-1 bg-surface-container-lowest rounded font-label-sm text-[10px] text-on-surface-variant font-bold border border-surface-container">VISA</span>
                    <span class="px-2 py-1 bg-surface-container-lowest rounded font-label-sm text-[10px] text-on-surface-variant font-bold border border-surface-container">MC</span>
                </div>
            </div>

            <div class="font-body-sm text-body-sm text-on-surface-variant">
                &copy; {{ date('Y') }} Stride Footwear Inc. All rights reserved. Crafted for motion.
            </div>
        </div>
    </div>
</footer>
