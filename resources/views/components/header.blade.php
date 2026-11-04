@php
    $currentRoute = request()->route() ? request()->route()->getName() : '';
@endphp

<header class="w-full bg-surface-container-lowest/95 backdrop-blur-md shadow-[0_1px_8px_rgba(0,0,0,0.04)]" x-data="{ mobileMenuOpen: false }">
    <div class="h-20 max-w-[1440px] mx-auto px-margin-mobile lg:px-margin flex items-center justify-between gap-gutter">
        
        <!-- Brand Logo -->
        <div class="flex items-center gap-space-md">
            <a class="flex items-center gap-space-xs" href="{{ route('home') }}">
                <img alt="Stride Brand Wordmark Logo" class="h-8 w-auto object-contain" src="/images/stride-logo.svg" onerror="this.onerror=null; this.src='/storage/stride-logo.svg';" />
                <span class="font-headline-sm text-headline-sm font-bold tracking-tight text-primary">STRIDE</span>
            </a>
        </div>

        <!-- Desktop Navigation -->
        <nav class="hidden xl:flex items-center gap-space-lg">
            <a class="font-label-lg text-label-lg transition-colors {{ request()->is('/') ? 'text-primary font-bold' : 'text-on-surface-variant hover:text-on-surface' }}" href="{{ route('home') }}">
                Home
            </a>
            <a class="font-label-lg text-label-lg transition-colors {{ request()->is('shop') && !request()->has('sale') ? 'text-primary font-bold' : 'text-on-surface-variant hover:text-on-surface' }}" href="{{ route('shop') }}">
                Shop
            </a>
            <a class="font-label-lg text-label-lg transition-colors {{ request()->is('collections*') ? 'text-primary font-bold' : 'text-on-surface-variant hover:text-on-surface' }}" href="{{ route('collections.index') }}">
                Collections
            </a>
            <a class="font-label-lg text-label-lg transition-colors {{ request()->is('best-sellers') ? 'text-primary font-bold' : 'text-on-surface-variant hover:text-on-surface' }}" href="{{ route('best-sellers') }}">
                Best Sellers
            </a>
            <a class="font-label-lg text-label-lg transition-colors {{ request()->is('new-arrivals') ? 'text-primary font-bold' : 'text-on-surface-variant hover:text-on-surface' }}" href="{{ route('new-arrivals') }}">
                New Arrivals
            </a>

            <!-- Pages Dropdown -->
            <div class="relative group" x-data="{ open: false }">
                <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-1 font-label-lg text-label-lg text-on-surface-variant hover:text-on-surface transition-colors" type="button">
                    <span>Pages</span>
                    <span class="material-symbols-outlined text-base">expand_more</span>
                </button>
                <div class="absolute left-0 top-full pt-2 w-52 hidden group-hover:block z-50">
                    <div class="bg-surface-container-lowest rounded-xl shadow-lg border border-surface-container py-2">
                        <a class="flex items-center gap-2 px-space-md py-2 font-body-sm text-body-sm text-on-surface hover:bg-surface-container transition-colors" href="{{ route('pages.about') }}">
                            Our Story
                        </a>
                        <a class="flex items-center gap-2 px-space-md py-2 font-body-sm text-body-sm text-on-surface hover:bg-surface-container transition-colors" href="{{ route('pages.size-guide') }}">
                            Size Guide
                        </a>
                        <a class="flex items-center gap-2 px-space-md py-2 font-body-sm text-body-sm text-on-surface hover:bg-surface-container transition-colors" href="{{ route('pages.shipping') }}">
                            Shipping &amp; Returns
                        </a>
                        <a class="flex items-center gap-2 px-space-md py-2 font-body-sm text-body-sm text-on-surface hover:bg-surface-container transition-colors" href="{{ route('pages.faq') }}">
                            Help Center / FAQ
                        </a>
                        <a class="flex items-center gap-2 px-space-md py-2 font-body-sm text-body-sm text-on-surface hover:bg-surface-container transition-colors" href="{{ route('contact.show') }}">
                            Contact Concierge
                        </a>
                        <a class="flex items-center gap-2 px-space-md py-2 font-body-sm text-body-sm text-on-surface hover:bg-surface-container transition-colors" href="{{ route('order-tracking.index') }}">
                            Track Your Order
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Right Action Icons -->
        <div class="flex items-center gap-space-sm">
            
            <!-- Live Search Trigger -->
            <button aria-label="Search storefront" @click="$dispatch('open-search')" class="w-10 h-10 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container hover:text-on-surface transition-colors" type="button">
                <span class="material-symbols-outlined text-xl">search</span>
            </button>

            <!-- Account Dropdown -->
            <div class="relative group">
                <button aria-label="Account menu" class="flex items-center gap-space-xs p-1 rounded-full hover:bg-surface-container transition-colors" type="button">
                    @auth
                        <div class="w-8 h-8 rounded-full bg-primary-container text-on-primary font-bold text-xs flex items-center justify-center">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    @else
                        <span class="material-symbols-outlined text-2xl text-on-surface-variant">account_circle</span>
                    @endauth
                    <span class="material-symbols-outlined text-base text-on-surface-variant">expand_more</span>
                </button>

                <!-- Dropdown Menu -->
                <div class="absolute right-0 top-full pt-2 w-52 hidden group-hover:block z-50">
                    <div class="bg-surface-container-lowest rounded-xl shadow-[0_4px_6px_-1px_rgba(0,0,0,0.05),0_2px_4px_-2px_rgba(0,0,0,0.05)] border border-surface-container py-2">
                        @auth
                            <div class="px-space-md py-2 border-b border-surface-container">
                                <p class="font-label-md text-label-md text-on-surface font-bold truncate">{{ Auth::user()->name }}</p>
                                <p class="font-body-sm text-[11px] text-on-surface-variant truncate">{{ Auth::user()->email }}</p>
                            </div>

                            @if(Auth::user()->isAdmin())
                                <a class="flex items-center gap-space-sm px-space-md py-2 font-body-sm text-body-sm text-primary font-bold hover:bg-surface-container" href="{{ route('admin.dashboard') }}">
                                    <span class="material-symbols-outlined text-base">admin_panel_settings</span>
                                    <span>Admin Panel</span>
                                </a>
                            @endif

                            <a class="flex items-center gap-space-sm px-space-md py-2 font-body-sm text-body-sm text-on-surface hover:bg-surface-container" href="{{ route('account.profile') }}">
                                <span class="material-symbols-outlined text-base">person</span>
                                <span>Profile</span>
                            </a>
                            <a class="flex items-center gap-space-sm px-space-md py-2 font-body-sm text-body-sm text-on-surface hover:bg-surface-container" href="{{ route('account.orders') }}">
                                <span class="material-symbols-outlined text-base">package_2</span>
                                <span>Orders</span>
                            </a>
                            <a class="flex items-center gap-space-sm px-space-md py-2 font-body-sm text-body-sm text-on-surface hover:bg-surface-container" href="{{ route('wishlist.index') }}">
                                <span class="material-symbols-outlined text-base">favorite</span>
                                <span>Wishlist</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-space-sm px-space-md py-2 font-body-sm text-body-sm text-error hover:bg-surface-container text-left">
                                    <span class="material-symbols-outlined text-base">logout</span>
                                    <span>Logout</span>
                                </button>
                            </form>
                        @else
                            <a class="flex items-center gap-space-sm px-space-md py-2 font-body-sm text-body-sm text-on-surface font-semibold hover:bg-surface-container" href="{{ route('login') }}">
                                <span class="material-symbols-outlined text-base">login</span>
                                <span>Sign In</span>
                            </a>
                            <a class="flex items-center gap-space-sm px-space-md py-2 font-body-sm text-body-sm text-on-surface-variant hover:bg-surface-container" href="{{ route('register') }}">
                                <span class="material-symbols-outlined text-base">person_add</span>
                                <span>Create Account</span>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Mini Cart Trigger Button with Live Alpine Count Badge -->
            <button aria-label="Shopping cart" @click="$store.cart.toggle()" class="relative flex items-center justify-center w-10 h-10 rounded-full text-on-surface-variant hover:bg-surface-container hover:text-on-surface transition-colors" type="button">
                <span class="material-symbols-outlined text-xl">shopping_bag</span>
                <span x-show="$store.cart.totalItems > 0" x-text="$store.cart.totalItems" class="absolute top-1 right-1 w-4 h-4 rounded-full bg-primary text-on-primary font-label-sm text-[10px] leading-none flex items-center justify-center font-bold" style="display: none;">
                    0
                </span>
            </button>

            <!-- Mobile Hamburger Button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="xl:hidden w-10 h-10 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container transition-colors" type="button" aria-label="Open navigation menu">
                <span class="material-symbols-outlined text-2xl" x-text="mobileMenuOpen ? 'close' : 'menu'">menu</span>
            </button>

        </div>
    </div>

    <!-- Mobile Slide-In Nav Menu -->
    <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="xl:hidden bg-surface-container-lowest border-t border-surface-container px-margin-mobile py-4 space-y-3" style="display: none;">
        <a class="block font-label-lg text-label-lg py-2 {{ request()->is('/') ? 'text-primary font-bold' : 'text-on-surface' }}" href="{{ route('home') }}">Home</a>
        <a class="block font-label-lg text-label-lg py-2 {{ request()->is('shop') ? 'text-primary font-bold' : 'text-on-surface' }}" href="{{ route('shop') }}">Shop</a>
        <a class="block font-label-lg text-label-lg py-2 {{ request()->is('collections*') ? 'text-primary font-bold' : 'text-on-surface' }}" href="{{ route('collections.index') }}">Collections</a>
        <a class="block font-label-lg text-label-lg py-2 {{ request()->is('best-sellers') ? 'text-primary font-bold' : 'text-on-surface' }}" href="{{ route('best-sellers') }}">Best Sellers</a>
        <a class="block font-label-lg text-label-lg py-2 {{ request()->is('new-arrivals') ? 'text-primary font-bold' : 'text-on-surface' }}" href="{{ route('new-arrivals') }}">New Arrivals</a>
        <div class="pt-2 border-t border-surface-container">
            <span class="font-label-sm text-label-sm text-outline uppercase">Company</span>
            <div class="mt-2 space-y-2">
                <a class="block font-body-sm text-body-sm text-on-surface-variant" href="{{ route('pages.about') }}">Our Story</a>
                <a class="block font-body-sm text-body-sm text-on-surface-variant" href="{{ route('pages.size-guide') }}">Size Guide</a>
                <a class="block font-body-sm text-body-sm text-on-surface-variant" href="{{ route('pages.shipping') }}">Shipping &amp; Returns</a>
                <a class="block font-body-sm text-body-sm text-on-surface-variant" href="{{ route('contact.show') }}">Contact Concierge</a>
            </div>
        </div>
    </div>
</header>
