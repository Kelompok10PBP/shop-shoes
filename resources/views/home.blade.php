<x-app-layout>
    <div class="flex flex-col w-full">

        <!-- SECTION 1: HERO CONTAINER -->
        <section class="max-w-[1440px] mx-auto w-full px-margin-mobile lg:px-margin mb-space-xl">
            <div class="bg-surface-container-low rounded-2xl p-space-lg lg:p-space-xl relative overflow-hidden shadow-sm">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter items-center">
                    
                    <!-- Text & Action Column -->
                    <div class="lg:col-span-7 flex flex-col items-start z-10">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-secondary-fixed text-on-secondary-fixed mb-space-md shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-primary-container"></span>
                            <span class="font-label-sm text-label-sm uppercase tracking-wider font-bold">
                                {{ $heroBanner->pill_label ?? 'New Collection 2025' }}
                            </span>
                        </div>

                        <h1 class="font-display text-display text-on-surface mb-space-md tracking-tight leading-tight">
                            {{ $heroBanner->title ?? 'Step Into Your' }} 
                            <span class="text-primary-container">{{ $heroBanner->title_highlight ?? 'Best Pace' }}</span>
                        </h1>

                        <p class="font-body-lg text-body-lg text-on-surface-variant max-w-xl mb-space-lg">
                            {{ $heroBanner->subtitle ?? 'Precision-engineered running and everyday performance footwear built with responsive bio-foam cushioning for 10K+ runs and daily stride.' }}
                        </p>

                        <div class="flex flex-wrap items-center gap-space-sm mb-space-xl w-full sm:w-auto">
                            <a class="h-12 px-8 flex items-center justify-center rounded-lg bg-primary-container text-on-primary font-label-lg text-label-lg hover:bg-primary transition-colors shadow-sm w-full sm:w-auto font-semibold" href="{{ route('shop') }}">
                                {{ $heroBanner->cta_text ?? 'Shop Now' }}
                            </a>
                            <a class="h-12 px-8 flex items-center justify-center rounded-lg bg-surface-container-lowest text-on-surface font-label-lg text-label-lg hover:bg-surface-container transition-colors shadow-sm w-full sm:w-auto font-semibold border border-surface-container" href="{{ route('collections.index') }}">
                                {{ $heroBanner->secondary_cta_text ?? 'Explore Collections' }}
                            </a>
                        </div>

                        <!-- 4 Pillars Row -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 w-full pt-space-md bg-surface-container-lowest/60 rounded-xl p-4 border border-surface-container/30">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-xl">local_shipping</span>
                                <div>
                                    <p class="font-label-sm text-label-sm text-on-surface font-bold">Free Shipping</p>
                                    <p class="font-body-sm text-[11px] leading-tight text-on-surface-variant">Pesanan Rp 500rb+</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-xl">schedule</span>
                                <div>
                                    <p class="font-label-sm text-label-sm text-on-surface font-bold">30-Day Wear Test</p>
                                    <p class="font-body-sm text-[11px] leading-tight text-on-surface-variant">Risk-free trials</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-xl">security</span>
                                <div>
                                    <p class="font-label-sm text-label-sm text-on-surface font-bold">Secure Checkout</p>
                                    <p class="font-body-sm text-[11px] leading-tight text-on-surface-variant">256-bit SSL</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-xl">support_agent</span>
                                <div>
                                    <p class="font-label-sm text-label-sm text-on-surface font-bold">24/7 Support</p>
                                    <p class="font-body-sm text-[11px] leading-tight text-on-surface-variant">Runner experts</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hero Showcase Column -->
                    <div class="lg:col-span-5 relative flex justify-center items-center mt-space-lg lg:mt-0">
                        <div class="relative w-full aspect-square max-w-[480px] bg-surface-container-lowest rounded-2xl flex items-center justify-center p-space-md shadow-sm overflow-hidden border border-surface-container/40">
                            <img 
                                class="w-full h-full object-contain transform -rotate-12 hover:rotate-0 transition-transform duration-500 ease-out" 
                                alt="High-end studio product photograph of Stride AeroGlide high-performance running sneaker" 
                                src="{{ $heroBanner->image ?? '/storage/banners/hero-aeroglide.png' }}" 
                            />
                            <!-- Circular Discount Badge -->
                            <div class="absolute top-4 right-4 w-20 h-20 rounded-full bg-primary-container text-on-primary flex flex-col items-center justify-center text-center p-2 shadow-md">
                                <span class="font-label-sm text-[10px] tracking-widest uppercase font-bold">UP TO</span>
                                <span class="font-headline-sm text-headline-sm font-bold leading-none">{{ $heroBanner->discount_badge_percent ?? $maxDiscount }}%</span>
                                <span class="font-label-sm text-[9px] uppercase font-semibold">OFF</span>
                            </div>
                            <!-- Technical Tag -->
                            <div class="absolute bottom-4 left-4 bg-surface-container px-3 py-1.5 rounded-lg flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm text-primary">sprint</span>
                                <span class="font-label-sm text-label-sm text-on-surface font-semibold">
                                    {{ $heroBanner->tech_tag_text ?? 'BioFoam Propulsion v2' }}
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- SECTION 2: SHOP BY CATEGORY -->
        <section class="max-w-[1440px] mx-auto w-full px-margin-mobile lg:px-margin mb-space-xl">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-space-lg gap-4">
                <div>
                    <h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight font-bold">Shop by Category</h2>
                    <p class="font-body-md text-body-md text-on-surface-variant mt-1">Engineered for your routine, sport, and daily comfort</p>
                </div>
                <a class="font-label-lg text-label-lg text-primary-container hover:text-primary flex items-center gap-1 group font-semibold" href="{{ route('collections.index') }}">
                    View all collections
                    <span class="material-symbols-outlined text-base group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-gutter">
                @foreach($categories as $category)
                    <a class="group flex flex-col bg-surface-container-lowest rounded-xl p-3 shadow-sm hover:shadow-md transition-shadow border border-surface-container/40" href="{{ route('shop', ['category' => $category->slug]) }}">
                        <div class="aspect-[4/3] w-full bg-surface-container rounded-lg overflow-hidden flex items-center justify-center relative mb-3">
                            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" alt="{{ $category->name }}" src="{{ $category->image ?? '/storage/categories/running.jpg' }}" loading="lazy" />
                            <span class="absolute top-2 right-2 bg-surface-container-lowest/90 px-2 py-0.5 rounded-full font-label-sm text-[11px] text-on-surface font-semibold shadow-xs">
                                {{ $category->products_count }} models
                            </span>
                        </div>
                        <div class="flex items-center justify-between px-1">
                            <span class="font-label-lg text-label-lg text-on-surface group-hover:text-primary transition-colors font-bold">{{ $category->name }}</span>
                            <span class="material-symbols-outlined text-base text-on-surface-variant group-hover:text-primary">chevron_right</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        <!-- SECTION 3: PROMO COUNTDOWN BANNER -->
        @if($promotion)
            <section 
                x-data="{
                    targetTime: new Date('{{ $promotion->ends_at ? $promotion->ends_at->toISOString() : now()->addDays(4)->toISOString() }}').getTime(),
                    days: '00',
                    hours: '00',
                    mins: '00',
                    secs: '00',
                    ended: false,

                    init() {
                        this.update();
                        setInterval(() => this.update(), 1000);
                    },

                    update() {
                        const now = new Date().getTime();
                        const diff = this.targetTime - now;

                        if (diff <= 0) {
                            this.ended = true;
                            this.days = '00';
                            this.hours = '00';
                            this.mins = '00';
                            this.secs = '00';
                            return;
                        }

                        const d = Math.floor(diff / (1000 * 60 * 60 * 24));
                        const h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                        const s = Math.floor((diff % (1000 * 60)) / 1000);

                        this.days = d < 10 ? '0' + d : d;
                        this.hours = h < 10 ? '0' + h : h;
                        this.mins = m < 10 ? '0' + m : m;
                        this.secs = s < 10 ? '0' + s : s;
                    }
                }"
                class="max-w-[1440px] mx-auto w-full px-margin-mobile lg:px-margin mb-space-xl"
            >
                <div class="bg-surface-container-high rounded-2xl p-space-lg lg:p-space-xl relative overflow-hidden shadow-sm">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter items-center">
                        
                        <!-- Sale Text & Countdown -->
                        <div class="lg:col-span-7 flex flex-col items-start">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-tertiary-fixed text-on-tertiary-fixed mb-space-md shadow-xs">
                                <span class="material-symbols-outlined text-sm">local_fire_department</span>
                                <span class="font-label-sm text-label-sm uppercase tracking-wider font-bold">
                                    {{ $promotion->subtitle ?? 'Spring Sale Is Live!' }}
                                </span>
                            </div>

                            <h2 class="font-headline-lg text-headline-lg text-on-surface font-bold tracking-tight mb-space-sm">
                                {{ $promotion->title }}
                            </h2>

                            <p class="font-body-md text-body-md text-on-surface-variant max-w-xl mb-space-lg">
                                {{ $promotion->description }}
                            </p>

                            <!-- Countdown Display Blocks -->
                            <div x-show="!ended" class="flex items-center gap-3 mb-space-lg">
                                <div class="flex flex-col items-center justify-center bg-inverse-surface text-inverse-on-surface rounded-xl w-16 h-16 shadow-sm">
                                    <span class="font-headline-md text-headline-md font-bold leading-tight" x-text="days">04</span>
                                    <span class="font-label-sm text-[10px] text-outline-variant uppercase">Days</span>
                                </div>
                                <span class="font-headline-md text-headline-md text-on-surface-variant font-bold">:</span>
                                <div class="flex flex-col items-center justify-center bg-inverse-surface text-inverse-on-surface rounded-xl w-16 h-16 shadow-sm">
                                    <span class="font-headline-md text-headline-md font-bold leading-tight" x-text="hours">18</span>
                                    <span class="font-label-sm text-[10px] text-outline-variant uppercase">Hours</span>
                                </div>
                                <span class="font-headline-md text-headline-md text-on-surface-variant font-bold">:</span>
                                <div class="flex flex-col items-center justify-center bg-inverse-surface text-inverse-on-surface rounded-xl w-16 h-16 shadow-sm">
                                    <span class="font-headline-md text-headline-md font-bold leading-tight" x-text="mins">42</span>
                                    <span class="font-label-sm text-[10px] text-outline-variant uppercase">Mins</span>
                                </div>
                                <span class="font-headline-md text-headline-md text-on-surface-variant font-bold">:</span>
                                <div class="flex flex-col items-center justify-center bg-inverse-surface text-inverse-on-surface rounded-xl w-16 h-16 shadow-sm">
                                    <span class="font-headline-md text-headline-md font-bold leading-tight" x-text="secs">19</span>
                                    <span class="font-label-sm text-[10px] text-outline-variant uppercase">Secs</span>
                                </div>
                            </div>

                            <div x-show="ended" class="mb-space-lg py-2 px-4 rounded-lg bg-surface-container text-on-surface-variant font-label-md text-label-md font-bold" style="display: none;">
                                This sale event has concluded. Check our shop for current releases.
                            </div>

                            <a class="h-12 px-8 flex items-center justify-center rounded-lg bg-primary text-on-primary font-label-lg text-label-lg hover:bg-primary-container transition-colors shadow-sm font-semibold" href="{{ route('shop', ['sale' => 1]) }}">
                                {{ $promotion->cta_text ?? 'Shop the Sale →' }}
                            </a>
                        </div>

                        <!-- Split Lifestyle Images -->
                        <div class="lg:col-span-5 grid grid-cols-2 gap-4">
                            <div class="aspect-[3/4] rounded-xl overflow-hidden bg-surface-container-lowest shadow-sm">
                                <img class="w-full h-full object-cover" alt="Athletic runner in action" src="{{ $promotion->image_1 ?? '/storage/banners/promo-runner.jpg' }}" loading="lazy" />
                            </div>
                            <div class="aspect-[3/4] rounded-xl overflow-hidden bg-surface-container-lowest shadow-sm mt-6">
                                <img class="w-full h-full object-cover" alt="Midsole engineering close up" src="{{ $promotion->image_2 ?? '/storage/banners/promo-plate.jpg' }}" loading="lazy" />
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        @endif

        <!-- SECTION 4: TRENDING PRODUCTS (5-COLUMN GRID) -->
        <section 
            x-data="{
                activeTab: 'all',
                loading: false,
                gridHtml: '',

                async switchTab(categorySlug) {
                    this.activeTab = categorySlug;
                    this.loading = true;
                    try {
                        const res = await fetch(`/home/trending?category=${categorySlug}`, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        if (res.ok) {
                            const data = await res.json();
                            this.gridHtml = data.html;
                        }
                    } catch (e) {
                        console.error('Error filtering trending shoes', e);
                    } finally {
                        this.loading = false;
                    }
                }
            }"
            class="max-w-[1440px] mx-auto w-full px-margin-mobile lg:px-margin mb-space-xl"
        >
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-space-lg gap-4">
                <div>
                    <h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight font-bold">Trending Performance</h2>
                    <p class="font-body-md text-body-md text-on-surface-variant mt-1">Our top tested models favored by road and trail athletes</p>
                </div>

                <!-- Category Filter Tabs -->
                <div class="flex items-center gap-2 overflow-x-auto pb-1">
                    <button 
                        @click="switchTab('all')" 
                        :class="activeTab === 'all' ? 'bg-primary-container text-on-primary shadow-sm' : 'bg-surface-container-low text-on-surface hover:bg-surface-container'"
                        class="px-4 py-2 rounded-full font-label-md text-label-md transition-colors font-semibold" 
                        type="button"
                    >
                        All Trending
                    </button>
                    <button 
                        @click="switchTab('running')" 
                        :class="activeTab === 'running' ? 'bg-primary-container text-on-primary shadow-sm' : 'bg-surface-container-low text-on-surface hover:bg-surface-container'"
                        class="px-4 py-2 rounded-full font-label-md text-label-md transition-colors font-semibold" 
                        type="button"
                    >
                        Road Running
                    </button>
                    <button 
                        @click="switchTab('trail-hiking')" 
                        :class="activeTab === 'trail-hiking' ? 'bg-primary-container text-on-primary shadow-sm' : 'bg-surface-container-low text-on-surface hover:bg-surface-container'"
                        class="px-4 py-2 rounded-full font-label-md text-label-md transition-colors font-semibold" 
                        type="button"
                    >
                        Trail &amp; Hiking
                    </button>
                    <button 
                        @click="switchTab('sneakers')" 
                        :class="activeTab === 'sneakers' ? 'bg-primary-container text-on-primary shadow-sm' : 'bg-surface-container-low text-on-surface hover:bg-surface-container'"
                        class="px-4 py-2 rounded-full font-label-md text-label-md transition-colors font-semibold" 
                        type="button"
                    >
                        Everyday Sneaker
                    </button>
                </div>
            </div>

            <!-- Dynamic 5-Column Grid -->
            <div class="relative">
                <div x-show="loading" class="absolute inset-0 bg-surface/60 backdrop-blur-xs flex items-center justify-center z-10 rounded-xl" style="display: none;">
                    <div class="flex items-center gap-2 font-label-md text-primary font-bold">
                        <span class="material-symbols-outlined animate-spin text-2xl">progress_activity</span>
                        <span>Updating models...</span>
                    </div>
                </div>

                <!-- Tab Swapped Content or Initial Server-Rendered Content -->
                <div 
                    x-show="gridHtml !== ''" 
                    x-html="gridHtml" 
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4"
                ></div>

                <div 
                    x-show="gridHtml === ''" 
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4"
                >
                    @foreach($trendingProducts as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            </div>
        </section>

        <!-- SECTION 5: TRUST STRIP & SOCIAL PROOF -->
        <section class="max-w-[1440px] mx-auto w-full px-margin-mobile lg:px-margin mb-space-xl">
            <div class="bg-surface-container-low rounded-2xl p-space-lg lg:p-space-xl">
                <div class="text-center max-w-2xl mx-auto mb-space-lg">
                    <h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight mb-2 font-bold">
                        Trusted by 10,000+ Runners &amp; Athletes Worldwide
                    </h2>
                    <p class="font-body-md text-body-md text-on-surface-variant">
                        Engineered for zero-break-in comfort and tested over millions of miles across 45 countries.
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gutter">
                    <!-- Pillar 1 -->
                    <div class="bg-surface-container-lowest p-space-md rounded-xl shadow-sm flex flex-col items-center text-center border border-surface-container/30">
                        <div class="w-12 h-12 rounded-full bg-secondary-container flex items-center justify-center mb-3">
                            <span class="material-symbols-outlined text-primary text-2xl" style="font-variation-settings: 'FILL' 1;">star</span>
                        </div>
                        <span class="font-headline-sm text-headline-sm text-on-surface font-bold mb-1">{{ number_format($overallRating, 1) }} / 5.0 Rating</span>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">Over {{ number_format($totalReviews) }} verified athlete and runner reviews globally.</p>
                    </div>
                    <!-- Pillar 2 -->
                    <div class="bg-surface-container-lowest p-space-md rounded-xl shadow-sm flex flex-col items-center text-center border border-surface-container/30">
                        <div class="w-12 h-12 rounded-full bg-secondary-container flex items-center justify-center mb-3">
                            <span class="material-symbols-outlined text-primary text-2xl">local_shipping</span>
                        </div>
                        <span class="font-headline-sm text-headline-sm text-on-surface font-bold mb-1">Fast &amp; Free Shipping</span>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">Complimentary express shipping on all domestic orders over Rp 500.000.</p>
                    </div>
                    <!-- Pillar 3 -->
                    <div class="bg-surface-container-lowest p-space-md rounded-xl shadow-sm flex flex-col items-center text-center border border-surface-container/30">
                        <div class="w-12 h-12 rounded-full bg-secondary-container flex items-center justify-center mb-3">
                            <span class="material-symbols-outlined text-primary text-2xl">autorenew</span>
                        </div>
                        <span class="font-headline-sm text-headline-sm text-on-surface font-bold mb-1">30-Day Risk-Free Trial</span>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">Wear them outdoors, run 50 miles. If not satisfied, return easily.</p>
                    </div>
                    <!-- Pillar 4 -->
                    <div class="bg-surface-container-lowest p-space-md rounded-xl shadow-sm flex flex-col items-center text-center border border-surface-container/30">
                        <div class="w-12 h-12 rounded-full bg-secondary-container flex items-center justify-center mb-3">
                            <span class="material-symbols-outlined text-primary text-2xl">lock</span>
                        </div>
                        <span class="font-headline-sm text-headline-sm text-on-surface font-bold mb-1">100% Secure Checkout</span>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">End-to-end encrypted 256-bit bank-grade payment processing.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION 6: NEWSLETTER SUBSCRIPTION STRIP -->
        <section class="max-w-[1440px] mx-auto w-full px-margin-mobile lg:px-margin mb-space-xl">
            <div class="bg-primary text-on-primary rounded-2xl p-space-lg lg:p-space-xl shadow-sm flex flex-col lg:flex-row items-center justify-between gap-space-lg">
                <div class="max-w-xl">
                    <span class="font-label-sm text-label-sm text-primary-fixed uppercase tracking-widest font-bold mb-2 block">
                        Stride Club Membership
                    </span>
                    <h2 class="font-headline-lg text-headline-lg text-on-primary font-bold tracking-tight mb-2">
                        Join the Stride Running Club
                    </h2>
                    <p class="font-body-md text-body-md text-on-primary-container">
                        Receive 15% off your first footwear order plus early VIP access to limited-edition seasonal color drops.
                    </p>
                </div>
                
                <div 
                    x-data="{
                        email: '',
                        submitting: false,
                        success: false,
                        couponCode: '',
                        errorMessage: '',

                        async subscribe() {
                            if (!this.email) return;
                            this.submitting = true;
                            this.errorMessage = '';
                            const token = document.querySelector('meta[name=\"csrf-token\"]')?.getAttribute('content');
                            try {
                                const res = await fetch('{{ route('newsletter.subscribe') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': token,
                                        'X-Requested-With': 'XMLHttpRequest'
                                    },
                                    body: JSON.stringify({ email: this.email })
                                });
                                const data = await res.json();
                                if (res.ok && data.success) {
                                    this.success = true;
                                    this.couponCode = data.coupon_code || 'WELCOME15';
                                    Alpine.store('toast').show('Welcome to Stride Club! Coupon unlocked.');
                                } else {
                                    this.errorMessage = data.message || 'Could not subscribe.';
                                }
                            } catch (e) {
                                this.errorMessage = 'Network connection failed.';
                            } finally {
                                this.submitting = false;
                            }
                        }
                    }"
                    class="w-full lg:max-w-md flex flex-col gap-2"
                >
                    <form x-show="!success" @submit.prevent="subscribe()" class="flex flex-col sm:flex-row gap-2">
                        <label class="sr-only" for="newsletter-email">Email address</label>
                        <input 
                            x-model="email" 
                            class="h-12 px-4 rounded-lg bg-surface-container-lowest text-on-surface font-body-md text-body-md placeholder:text-outline flex-1 focus:outline-none focus:ring-2 focus:ring-primary-fixed" 
                            id="newsletter-email" 
                            placeholder="Enter your email" 
                            required 
                            type="email"
                        />
                        <button 
                            :disabled="submitting" 
                            class="h-12 px-6 rounded-lg bg-primary-container text-on-primary hover:bg-on-secondary-fixed-variant transition-colors font-label-lg text-label-lg font-semibold flex items-center justify-center gap-1 cursor-pointer disabled:opacity-60" 
                            type="submit"
                        >
                            <span x-show="!submitting">Subscribe</span>
                            <span x-show="submitting" class="material-symbols-outlined animate-spin text-sm" style="display: none;">progress_activity</span>
                        </button>
                    </form>

                    <!-- Error state -->
                    <div x-show="errorMessage" x-text="errorMessage" class="text-error-container font-label-sm text-xs" style="display: none;"></div>

                    <!-- Success State with Coupon Code -->
                    <div x-show="success" class="p-3 rounded-lg bg-secondary-container text-on-secondary-fixed font-label-md text-label-md flex items-center justify-between gap-2" style="display: none;">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-base text-primary">check_circle</span>
                            <span>Welcome! Use code <strong class="underline font-bold text-primary" x-text="couponCode"></strong> for 15% off.</span>
                        </div>
                    </div>

                    <p class="font-body-sm text-[12px] text-on-primary-container flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">verified_user</span>
                        <span>We respect your privacy. No spam, ever. Unsubscribe at any time.</span>
                    </p>
                </div>
            </div>
        </section>

    </div>
</x-app-layout>
