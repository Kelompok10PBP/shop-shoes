@props(['product'])

@php
    $finalPrice = $product->final_price;
    $hasDiscount = $product->compare_at_price && $product->compare_at_price > $finalPrice;
    $variants = $product->variants;
    // Extract unique color options
    $colors = $variants->unique('color_name')->filter(fn($v) => !empty($v->color_name));
    $hasMultipleSizes = $variants->count() > 1;
    $rating = $product->average_rating;
    $reviewsCount = $product->reviews_count;
    $primaryImg = $product->display_image;
@endphp

<div 
    x-data="{
        showSizes: false,
        activeImage: '{{ $primaryImg }}',
        selectedVariantId: null,
        selectedColor: '{{ $colors->first()->color_name ?? '' }}'
    }" 
    class="group bg-surface-container-lowest rounded-xl p-3 flex flex-col justify-between shadow-sm hover:shadow-md transition-shadow relative border border-surface-container/40"
>
    <div>
        <!-- Media Well -->
        <div class="aspect-square bg-surface-container rounded-lg overflow-hidden relative flex items-center justify-center mb-3">
            
            <!-- Badges -->
            @if($product->badge)
                <span class="absolute top-2 left-2 px-2 py-0.5 rounded-full font-label-sm text-[10px] font-bold z-10 
                    {{ $product->badge === 'SALE' ? 'bg-primary-container text-on-primary' : '' }}
                    {{ $product->badge === 'NEW' ? 'bg-secondary text-on-secondary' : '' }}
                    {{ $product->badge === 'WATERPROOF' ? 'bg-surface-container-high text-on-surface' : '' }}
                    {{ $product->badge === 'BEST SELLER' ? 'bg-inverse-surface text-inverse-on-surface' : 'bg-primary-container text-on-primary' }}">
                    {{ $product->badge }}
                </span>
            @elseif($hasDiscount)
                <span class="absolute top-2 left-2 px-2 py-0.5 rounded-full bg-primary-container text-on-primary font-label-sm text-[10px] font-bold z-10">
                    SALE
                </span>
            @endif

            <!-- Wishlist Heart Button -->
            <button 
                aria-label="Add to wishlist" 
                @click="$store.wishlist.toggle({{ $product->id }}, $el)" 
                class="absolute top-2 right-2 w-8 h-8 rounded-full bg-surface-container-lowest/80 text-on-surface-variant hover:text-error flex items-center justify-center shadow-sm z-10 transition-colors" 
                type="button"
            >
                <span class="material-symbols-outlined text-lg">favorite</span>
            </button>

            <!-- Product Image -->
            <a href="{{ route('products.show', $product->slug) }}" class="w-full h-full flex items-center justify-center">
                <img 
                    :src="activeImage" 
                    alt="{{ $product->name }}" 
                    class="w-full h-full object-contain p-2 group-hover:scale-105 transition-transform duration-300" 
                    loading="lazy" 
                />
            </a>
        </div>

        <!-- Color Swatches -->
        @if($colors->count() > 0)
            <div class="flex items-center gap-1.5 mb-2 px-1">
                @foreach($colors as $col)
                    <button 
                        aria-label="{{ $col->color_name }}" 
                        @click="selectedColor = '{{ $col->color_name }}'" 
                        :class="selectedColor === '{{ $col->color_name }}' ? 'ring-2 ring-primary ring-offset-1' : 'hover:ring-1 hover:ring-outline'" 
                        class="w-4 h-4 rounded-full transition-all" 
                        style="background-color: {{ $col->color_hex }};" 
                        type="button"
                    ></button>
                @endforeach
            </div>
        @endif

        <!-- Rating -->
        <div class="flex items-center gap-1 px-1 mb-1">
            <div class="flex text-tertiary-fixed-dim">
                <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
            </div>
            <span class="font-label-sm text-label-sm text-on-surface font-semibold">{{ number_format($rating, 1) }}</span>
            <span class="font-body-sm text-[11px] text-on-surface-variant">({{ $reviewsCount }})</span>
        </div>

        <!-- Title & Subtitle -->
        <a href="{{ route('products.show', $product->slug) }}">
            <h3 class="font-label-lg text-label-lg text-on-surface px-1 line-clamp-1 group-hover:text-primary transition-colors font-bold">
                {{ $product->name }}
            </h3>
        </a>
        <p class="font-body-sm text-[12px] text-on-surface-variant px-1 mb-2 line-clamp-1">
            {{ $product->subtitle ?? ($product->category ? $product->category->name : '') }}
        </p>
    </div>

    <!-- Pricing & Action Row -->
    <div class="px-1 pt-2">
        <div class="flex items-baseline gap-2 mb-3">
            <span class="font-headline-sm text-headline-sm text-on-surface font-bold">
                Rp {{ number_format($finalPrice, 0, ',', '.') }}
            </span>
            @if($hasDiscount)
                <span class="font-body-sm text-body-sm text-outline line-through">
                    Rp {{ number_format($product->compare_at_price, 0, ',', '.') }}
                </span>
            @endif
        </div>

        <!-- Add to Cart or Select Size with Popover -->
        <div class="relative" @click.outside="showSizes = false">
            @if($hasMultipleSizes)
                <button 
                    @click="showSizes = !showSizes" 
                    class="w-full h-10 rounded-lg bg-surface-container-low text-on-surface font-label-md text-label-md hover:bg-primary-container hover:text-on-primary transition-colors flex items-center justify-center gap-1 shadow-sm" 
                    type="button"
                >
                    <span class="material-symbols-outlined text-base">add_shopping_cart</span>
                    <span>Select Size</span>
                </button>

                <!-- Size Selector Popover Dropdown -->
                <div 
                    x-show="showSizes" 
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-2"
                    class="absolute bottom-12 left-0 right-0 p-3 bg-surface-container-lowest rounded-xl shadow-2xl border border-surface-container z-30" 
                    style="display: none;"
                >
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-label-sm text-label-sm text-on-surface font-bold">Select Size</span>
                        <button @click="showSizes = false" class="text-on-surface-variant hover:text-on-surface" type="button">
                            <span class="material-symbols-outlined text-sm">close</span>
                        </button>
                    </div>

                    <!-- Size Grid -->
                    <div class="grid grid-cols-4 gap-1.5 max-h-48 overflow-y-auto">
                        @foreach($variants as $variant)
                            @php
                                $isOos = $variant->stock <= 0;
                            @endphp
                            <button 
                                type="button"
                                @if($isOos) 
                                    disabled 
                                    class="py-1.5 text-center font-label-sm text-label-sm rounded bg-surface-container-high text-outline cursor-not-allowed opacity-50"
                                @else
                                    @click="$store.cart.add({{ $product->id }}, {{ $variant->id }}, 1); showSizes = false"
                                    class="py-1.5 text-center font-label-sm text-label-sm rounded bg-surface-container hover:bg-primary-container hover:text-on-primary transition-colors font-medium"
                                @endif
                            >
                                {{ $variant->size }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @else
                <button 
                    @click="$store.cart.add({{ $product->id }}, {{ $variants->first()->id ?? 'null' }}, 1)" 
                    class="w-full h-10 rounded-lg bg-surface-container-low text-on-surface font-label-md text-label-md hover:bg-primary-container hover:text-on-primary transition-colors flex items-center justify-center gap-1 shadow-sm" 
                    type="button"
                >
                    <span class="material-symbols-outlined text-base">add_shopping_cart</span>
                    <span>Add to Cart</span>
                </button>
            @endif
        </div>
    </div>
</div>
