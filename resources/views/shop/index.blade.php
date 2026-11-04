<x-app-layout title="Koleksi Sepatu | Stride Footwear">
    <div class="max-w-[1440px] mx-auto w-full px-margin-mobile lg:px-margin py-space-lg">
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-on-surface-variant font-label-sm text-label-sm mb-space-md">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-on-surface font-bold">Katalog Sepatu</span>
        </nav>

        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-space-lg gap-4">
            <div>
                <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight font-bold">Katalog Sepatu</h1>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1">
                    Temukan siluet performa terbaik dari road, trail, track, hingga lifestyle.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <span class="font-label-sm text-xs text-on-surface-variant font-bold">{{ $products->total() }} Model Tersedia</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter">
            
            <!-- Filters Sidebar (3 cols) -->
            <aside class="lg:col-span-3">
                <form action="{{ route('shop') }}" method="GET" class="bg-surface-container-lowest rounded-2xl p-space-md border border-surface-container/60 shadow-sm flex flex-col gap-5 sticky top-32">
                    
                    <div class="flex items-center justify-between pb-3 border-b border-surface-container">
                        <span class="font-headline-sm text-base text-on-surface font-bold flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-lg text-primary">filter_alt</span>
                            <span>Filter Produk</span>
                        </span>
                        <a href="{{ route('shop') }}" class="text-xs text-primary font-bold hover:underline">Reset</a>
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <h4 class="font-label-sm text-xs uppercase tracking-wider text-on-surface font-bold mb-2">Kategori</h4>
                        <div class="flex flex-col gap-1.5">
                            <label class="flex items-center gap-2 text-sm text-on-surface cursor-pointer">
                                <input type="radio" name="category" value="" {{ !request('category') ? 'checked' : '' }} onchange="this.form.submit()" class="text-primary focus:ring-primary">
                                <span>Semua Kategori</span>
                            </label>
                            @foreach($categories as $cat)
                                <label class="flex items-center justify-between text-sm text-on-surface cursor-pointer">
                                    <div class="flex items-center gap-2">
                                        <input type="radio" name="category" value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'checked' : '' }} onchange="this.form.submit()" class="text-primary focus:ring-primary">
                                        <span>{{ $cat->name }}</span>
                                    </div>
                                    <span class="text-xs text-outline">({{ $cat->products_count }})</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Size Filter -->
                    <div class="pt-3 border-t border-surface-container">
                        <h4 class="font-label-sm text-xs uppercase tracking-wider text-on-surface font-bold mb-2">Ukuran (US)</h4>
                        <div class="grid grid-cols-4 gap-1.5">
                            @foreach($availableSizes as $sz)
                                <label class="border rounded p-1.5 text-center text-xs font-semibold cursor-pointer {{ request('size') === $sz ? 'bg-primary text-on-primary border-primary' : 'bg-surface-container-low border-surface-container hover:border-primary' }}">
                                    <input type="radio" name="size" value="{{ $sz }}" {{ request('size') === $sz ? 'checked' : '' }} onchange="this.form.submit()" class="sr-only">
                                    <span>{{ $sz }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Sort -->
                    <div class="pt-3 border-t border-surface-container">
                        <h4 class="font-label-sm text-xs uppercase tracking-wider text-on-surface font-bold mb-2">Urutkan</h4>
                        <select name="sort" onchange="this.form.submit()" class="w-full h-10 px-3 rounded-lg bg-surface-container-low border border-surface-container text-xs font-bold text-on-surface">
                            <option value="featured" {{ request('sort') === 'featured' ? 'selected' : '' }}>Paling Populer</option>
                            <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                            <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                        </select>
                    </div>

                </form>
            </aside>

            <!-- Products Grid (9 cols) -->
            <main class="lg:col-span-9 flex flex-col justify-between">
                <div>
                    @include('partials.shop-products', ['products' => $products])
                </div>

                <!-- Pagination -->
                <div class="mt-space-lg pt-space-md border-t border-surface-container">
                    {{ $products->links() }}
                </div>
            </main>

        </div>

    </div>
</x-app-layout>
