<x-app-layout :title="$title . ' | Stride Footwear'">
    <div class="max-w-[1440px] mx-auto w-full px-margin-mobile lg:px-margin py-space-lg">
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-on-surface-variant font-label-sm text-label-sm mb-space-md">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <a href="{{ route('shop') }}" class="hover:text-primary transition-colors">Shop</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-on-surface font-bold">{{ $title }}</span>
        </nav>

        <div class="mb-space-lg">
            <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight font-bold">{{ $title }}</h1>
            <p class="font-body-md text-body-md text-on-surface-variant mt-1">{{ $subtitle }}</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-gutter mb-space-lg">
            @forelse($products as $product)
                <x-product-card :product="$product" />
            @empty
                <div class="col-span-full py-16 text-center">
                    <p class="font-headline-sm text-lg text-on-surface font-bold">Tidak ada produk saat ini.</p>
                </div>
            @endforelse
        </div>

        <div class="pt-space-md border-t border-surface-container">
            {{ $products->links() }}
        </div>

    </div>
</x-app-layout>
