@forelse($products as $product)
    <x-product-card :product="$product" />
@empty
    <div class="col-span-full py-12 text-center text-on-surface-variant">
        <p class="font-body-md text-body-md">No trending performance shoes found in this category.</p>
    </div>
@endforelse
