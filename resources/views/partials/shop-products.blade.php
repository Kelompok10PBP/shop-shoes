<div class="grid grid-cols-2 md:grid-cols-3 gap-gutter">
    @forelse($products as $product)
        <x-product-card :product="$product" />
    @empty
        <div class="col-span-full py-16 text-center">
            <span class="material-symbols-outlined text-4xl text-outline mb-2">search_off</span>
            <p class="font-headline-sm text-lg text-on-surface font-bold">Tidak ada produk ditemukan</p>
            <p class="font-body-sm text-sm text-on-surface-variant mt-1">Coba ganti filter kategori, ukuran, atau rentang harga.</p>
        </div>
    @endforelse
</div>
