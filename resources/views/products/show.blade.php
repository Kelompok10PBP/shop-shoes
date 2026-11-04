<x-app-layout :title="$product->name . ' | Stride Footwear'">
    @php
        $variants = $product->variants;
        $firstVariant = $variants->first();
        $colors = $variants->unique('color_name')->filter(fn($v) => !empty($v->color_name));
        $finalPrice = $product->final_price;
        $hasDiscount = $product->compare_at_price && $product->compare_at_price > $finalPrice;
        $primaryImg = $product->display_image;
        $allImages = $product->images->isNotEmpty() ? $product->images->pluck('image_path')->toArray() : [$primaryImg];
    @endphp

    <div 
        x-data="{
            activeImage: '{{ $primaryImg }}',
            selectedVariantId: {{ $firstVariant ? $firstVariant->id : 'null' }},
            selectedSize: '{{ $firstVariant ? $firstVariant->size : '' }}',
            selectedColor: '{{ $colors->first()->color_name ?? '' }}',
            selectedStock: {{ $firstVariant ? $firstVariant->stock : 10 }},
            quantity: 1,

            selectVariant(id, size, stock) {
                this.selectedVariantId = id;
                this.selectedSize = size;
                this.selectedStock = stock;
                if (this.quantity > stock) this.quantity = Math.max(1, stock);
            }
        }"
        class="max-w-[1440px] mx-auto w-full px-margin-mobile lg:px-margin py-space-lg"
    >
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-on-surface-variant font-label-sm text-label-sm mb-space-md">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <a href="{{ route('shop') }}" class="hover:text-primary transition-colors">Shop</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            @if($product->category)
                <a href="{{ route('shop', ['category' => $product->category->slug]) }}" class="hover:text-primary transition-colors">{{ $product->category->name }}</a>
                <span class="material-symbols-outlined text-xs">chevron_right</span>
            @endif
            <span class="text-on-surface font-bold truncate">{{ $product->name }}</span>
        </nav>

        <!-- Product Hero Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter mb-space-xl">
            
            <!-- Gallery Column (7 cols) -->
            <div class="lg:col-span-7 flex flex-col gap-4">
                <!-- Main Image Preview Container -->
                <div class="aspect-square w-full bg-surface-container-lowest rounded-2xl border border-surface-container/60 p-6 sm:p-10 flex items-center justify-center relative overflow-hidden shadow-sm">
                    @if($product->badge)
                        <span class="absolute top-4 left-4 px-3 py-1 rounded-full font-label-sm text-xs font-bold z-10 bg-primary-container text-on-primary">
                            {{ $product->badge }}
                        </span>
                    @elseif($hasDiscount)
                        <span class="absolute top-4 left-4 px-3 py-1 rounded-full font-label-sm text-xs font-bold z-10 bg-primary-container text-on-primary">
                            SALE {{ $product->discount_percent }}% OFF
                        </span>
                    @endif

                    <button 
                        aria-label="Add to wishlist" 
                        @click="$store.wishlist.toggle({{ $product->id }}, $el)" 
                        class="absolute top-4 right-4 w-10 h-10 rounded-full bg-surface-container-low text-on-surface-variant hover:text-error flex items-center justify-center shadow-sm z-10 transition-colors" 
                        type="button"
                    >
                        <span class="material-symbols-outlined text-xl">favorite</span>
                    </button>

                    <img 
                        :src="activeImage" 
                        alt="{{ $product->name }}" 
                        class="w-full h-full object-contain hover:scale-105 transition-transform duration-500 ease-out" 
                    />
                </div>

                <!-- Thumbnails Carousel/Grid -->
                @if(count($allImages) > 1)
                    <div class="flex items-center gap-3 overflow-x-auto pb-2">
                        @foreach($allImages as $img)
                            <button 
                                type="button" 
                                @click="activeImage = '{{ $img }}'"
                                :class="activeImage === '{{ $img }}' ? 'border-primary ring-2 ring-primary/20' : 'border-surface-container'"
                                class="w-20 h-20 rounded-xl bg-surface-container-lowest border p-2 flex items-center justify-center flex-shrink-0 transition-all hover:border-primary"
                            >
                                <img src="{{ $img }}" alt="Thumbnail" class="w-full h-full object-contain" />
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Details & Purchase Column (5 cols) -->
            <div class="lg:col-span-5 flex flex-col justify-between">
                <div>
                    <!-- Brand & Rating -->
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-label-sm text-xs uppercase tracking-widest text-primary font-bold">
                            {{ $product->brand ? $product->brand->name : 'STRIDE FOOTWEAR' }}
                        </span>
                        <div class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-tertiary-fixed-dim text-base" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="font-label-sm text-sm text-on-surface font-bold">{{ number_format($product->average_rating, 1) }}</span>
                            <span class="text-xs text-on-surface-variant">({{ $product->reviews_count }} reviews)</span>
                        </div>
                    </div>

                    <!-- Title -->
                    <h1 class="font-headline-lg text-3xl sm:text-4xl text-on-surface font-bold tracking-tight mb-2">
                        {{ $product->name }}
                    </h1>

                    @if($product->subtitle)
                        <p class="font-body-md text-base text-on-surface-variant mb-4">
                            {{ $product->subtitle }}
                        </p>
                    @endif

                    <!-- Price Block -->
                    <div class="flex items-baseline gap-3 mb-6 p-4 rounded-xl bg-surface-container-low border border-surface-container">
                        <span class="font-headline-lg text-3xl font-extrabold text-primary font-mono">
                            Rp {{ number_format($finalPrice, 0, ',', '.') }}
                        </span>
                        @if($hasDiscount)
                            <span class="font-body-md text-lg text-outline line-through font-mono">
                                Rp {{ number_format($product->compare_at_price, 0, ',', '.') }}
                            </span>
                            <span class="px-2 py-0.5 rounded-full bg-secondary-fixed text-on-secondary-fixed text-xs font-bold uppercase">
                                Save {{ $product->discount_percent }}%
                            </span>
                        @endif
                    </div>

                    <!-- Description -->
                    <div class="font-body-md text-sm text-on-surface-variant leading-relaxed mb-6">
                        {{ $product->description ?? 'Precision-engineered high-performance shoe constructed with proprietary responsive bio-foam cushioning for maximum energy return and seamless comfort on road, trail, or street.' }}
                    </div>

                    <!-- Color Swatches -->
                    @if($colors->count() > 0)
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-label-sm text-xs uppercase tracking-wider text-on-surface font-bold">Warna Pilihan:</span>
                                <span class="text-xs text-on-surface font-semibold" x-text="selectedColor"></span>
                            </div>
                            <div class="flex items-center gap-2">
                                @foreach($colors as $col)
                                    <button 
                                        type="button" 
                                        @click="selectedColor = '{{ $col->color_name }}'"
                                        :class="selectedColor === '{{ $col->color_name }}' ? 'ring-2 ring-primary ring-offset-2 scale-110' : 'hover:scale-105'"
                                        class="w-7 h-7 rounded-full border border-surface-container transition-all"
                                        style="background-color: {{ $col->color_hex }};"
                                        title="{{ $col->color_name }}"
                                    ></button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Size Selector -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-label-sm text-xs uppercase tracking-wider text-on-surface font-bold">Pilih Ukuran Sepatu (US):</span>
                            <span class="text-xs text-primary font-semibold">Tersedia: <span x-text="selectedStock"></span> pasang</span>
                        </div>
                        
                        <div class="grid grid-cols-4 sm:grid-cols-6 gap-2">
                            @foreach($variants as $variant)
                                @php
                                    $isOos = $variant->stock <= 0;
                                @endphp
                                <button 
                                    type="button" 
                                    @if($isOos) disabled @endif
                                    @click="selectVariant({{ $variant->id }}, '{{ $variant->size }}', {{ $variant->stock }})"
                                    :class="selectedVariantId === {{ $variant->id }} ? 'bg-primary text-on-primary ring-2 ring-primary/20' : 'bg-surface-container-low text-on-surface hover:bg-surface-container'"
                                    class="h-11 rounded-lg font-label-md text-sm font-semibold transition-all flex flex-col items-center justify-center border border-surface-container {{ $isOos ? 'opacity-40 cursor-not-allowed line-through' : '' }}"
                                >
                                    <span>{{ $variant->size }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Quantity Stepper -->
                    <div class="flex items-center gap-4 mb-6">
                        <span class="font-label-sm text-xs uppercase tracking-wider text-on-surface font-bold">Jumlah:</span>
                        <div class="flex items-center bg-surface-container-low rounded-lg p-1 border border-surface-container">
                            <button 
                                type="button" 
                                @click="if(quantity > 1) quantity--"
                                class="w-8 h-8 rounded hover:bg-surface-container flex items-center justify-center font-bold text-on-surface"
                            >-</button>
                            <span class="font-label-md text-sm font-bold w-10 text-center text-on-surface" x-text="quantity"></span>
                            <button 
                                type="button" 
                                @click="if(quantity < selectedStock) quantity++"
                                class="w-8 h-8 rounded hover:bg-surface-container flex items-center justify-center font-bold text-on-surface"
                            >+</button>
                        </div>
                    </div>

                    <!-- Action Buttons: Buy Now & Add to Bag -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-surface-container">
                        
                        <!-- BUY NOW (Straight to Checkout & Receipt) -->
                        <form action="{{ route('cart.buy-now') }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="product_variant_id" :value="selectedVariantId">
                            <input type="hidden" name="quantity" :value="quantity">
                            <button 
                                type="submit" 
                                class="w-full h-12 rounded-lg bg-primary text-on-primary font-label-lg text-label-lg hover:bg-primary-container transition-all flex items-center justify-center gap-2 shadow-md hover:shadow-lg font-bold"
                            >
                                <span class="material-symbols-outlined">bolt</span>
                                <span>Beli Sekarang (Langsung Bayar)</span>
                            </button>
                        </form>

                        <!-- ADD TO BAG -->
                        <button 
                            type="button" 
                            @click="$store.cart.add({{ $product->id }}, selectedVariantId, quantity)"
                            class="flex-1 h-12 rounded-lg bg-surface-container-low text-on-surface hover:bg-surface-container border border-surface-container font-label-lg text-label-lg transition-all flex items-center justify-center gap-2 font-bold"
                        >
                            <span class="material-symbols-outlined">add_shopping_cart</span>
                            <span>Tambah ke Keranjang</span>
                        </button>

                    </div>

                    <!-- Trust Pillars -->
                    <div class="grid grid-cols-2 gap-3 mt-6 pt-6 border-t border-surface-container text-xs text-on-surface-variant">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-lg">local_shipping</span>
                            <span>Pengiriman Gratis Pesanan Rp 500rb+</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-lg">published_with_changes</span>
                            <span>Garansi Tukar Ukuran 30 Hari</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-lg">verified</span>
                            <span>100% Produk Original Bergaransi</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-lg">receipt_long</span>
                            <span>Struk Pembayaran Sah & Resmi</span>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <!-- Related Products Section -->
        @if($relatedProducts->isNotEmpty())
            <div class="mt-space-xl pt-space-xl border-t border-surface-container">
                <div class="flex items-center justify-between mb-space-md">
                    <div>
                        <h2 class="font-headline-sm text-2xl text-on-surface font-bold">Produk Serupa Lainnya</h2>
                        <p class="font-body-sm text-on-surface-variant text-xs mt-0.5">Pilihan sepatu dengan profil performa serupa</p>
                    </div>
                    <a href="{{ route('shop') }}" class="font-label-md text-sm text-primary font-bold hover:underline flex items-center gap-1">
                        <span>Lihat Semua</span>
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-gutter">
                    @foreach($relatedProducts as $related)
                        <x-product-card :product="$related" />
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</x-app-layout>
