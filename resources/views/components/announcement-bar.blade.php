@php
    $shippingText = \App\Models\Setting::get('announcement_shipping', 'Gratis Ongkir untuk Pesanan di Atas Rp 500.000');
    $guaranteeText = \App\Models\Setting::get('announcement_guarantee', '30-Day Money Back Guarantee');
    $ratingText = \App\Models\Setting::get('announcement_rating', 'Excellent 4.9 out of 5 Rating');
@endphp

<aside aria-label="Store Announcements" class="w-full bg-primary text-on-primary py-2 px-margin-mobile lg:px-margin">
    <div class="max-w-[1440px] mx-auto flex items-center justify-between font-label-sm text-label-sm">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-base">local_shipping</span>
            <span>{{ $shippingText }}</span>
        </div>
        <div class="hidden md:flex items-center gap-2">
            <span class="material-symbols-outlined text-base">verified_user</span>
            <span>{{ $guaranteeText }}</span>
        </div>
        <div class="hidden lg:flex items-center gap-2">
            <span class="material-symbols-outlined text-base text-tertiary-fixed-dim">star</span>
            <span>{{ $ratingText }}</span>
        </div>
    </div>
</aside>
