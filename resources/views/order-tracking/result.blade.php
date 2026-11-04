<x-app-layout title="Status Pesanan #{{ $orderNumber }} | Stride Footwear">
    <div class="max-w-[1440px] mx-auto w-full px-margin-mobile lg:px-margin py-space-lg">
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-on-surface-variant font-label-sm text-label-sm mb-space-md">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <a href="{{ route('order-tracking.index') }}" class="hover:text-primary transition-colors">Lacak Pesanan</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-on-surface font-bold">Hasil Pelacakan</span>
        </nav>

        @if(!$order)
            <div class="max-w-xl mx-auto py-16 text-center bg-surface-container-lowest rounded-2xl border border-surface-container p-space-lg">
                <span class="material-symbols-outlined text-5xl text-outline mb-3">search_off</span>
                <h2 class="font-headline-sm text-xl font-bold text-on-surface">Pesanan Tidak Ditemukan</h2>
                <p class="font-body-md text-sm text-on-surface-variant mt-2 mb-6">
                    Kami tidak dapat menemukan pesanan dengan nomor <strong>{{ $orderNumber }}</strong> dan email <strong>{{ $email }}</strong>. Silakan periksa kembali data yang Anda masukkan.
                </p>
                <a href="{{ route('order-tracking.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg bg-primary text-on-primary font-label-md text-sm font-bold">
                    <span>Coba Lagi</span>
                </a>
            </div>
        @else
            <div class="max-w-3xl mx-auto flex flex-col gap-6">
                <!-- Status Card -->
                <div class="bg-surface-container-lowest rounded-2xl p-space-md lg:p-space-lg border border-surface-container shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-surface-container gap-2">
                        <div>
                            <span class="text-xs uppercase tracking-wider text-outline font-bold">Nomor Pesanan</span>
                            <h1 class="font-mono text-2xl font-bold text-on-surface mt-0.5">#{{ $order->order_number }}</h1>
                            <p class="text-xs text-on-surface-variant">{{ $order->created_at->format('d M Y, H:i') }} WIB</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 rounded-full bg-secondary-fixed text-on-secondary-fixed text-xs font-bold uppercase tracking-wider">
                                STATUS: {{ strtoupper($order->status) }}
                            </span>
                            <a 
                                href="{{ route('checkout.success', $order->order_number) }}" 
                                class="px-4 py-2 rounded-lg bg-primary text-on-primary text-xs font-bold hover:bg-primary-container transition-colors flex items-center gap-1.5 shadow-sm"
                            >
                                <span class="material-symbols-outlined text-sm">receipt_long</span>
                                <span>Buka Struk Lengkap</span>
                            </a>
                        </div>
                    </div>

                    <!-- Steps Timeline -->
                    <div class="py-6 border-b border-surface-container">
                        <div class="grid grid-cols-4 gap-2 text-center text-xs">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold mb-1">
                                    <span class="material-symbols-outlined text-sm">done</span>
                                </div>
                                <span class="font-bold text-on-surface">Pesanan Masuk</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'bg-primary text-on-primary' : 'bg-surface-container text-outline' }} flex items-center justify-center font-bold mb-1">
                                    <span class="material-symbols-outlined text-sm">inventory_2</span>
                                </div>
                                <span class="font-bold text-on-surface">Diproses & Lunas</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full {{ in_array($order->status, ['shipped', 'delivered']) ? 'bg-primary text-on-primary' : 'bg-surface-container text-outline' }} flex items-center justify-center font-bold mb-1">
                                    <span class="material-symbols-outlined text-sm">local_shipping</span>
                                </div>
                                <span class="font-bold text-on-surface">Dikirim</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full {{ $order->status === 'delivered' ? 'bg-primary text-on-primary' : 'bg-surface-container text-outline' }} flex items-center justify-center font-bold mb-1">
                                    <span class="material-symbols-outlined text-sm">check_circle</span>
                                </div>
                                <span class="font-bold text-on-surface">Selesai</span>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items Preview -->
                    <div class="pt-4">
                        <h3 class="font-label-sm text-xs font-bold uppercase tracking-wider text-on-surface mb-3">Item Pesanan:</h3>
                        <div class="divide-y divide-surface-container">
                            @foreach($order->items as $item)
                                <div class="py-3 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 bg-surface-container rounded-lg p-1 flex items-center justify-center">
                                            <img src="{{ $item->product?->display_image ?? '/images/placeholder-shoe.png' }}" alt="{{ $item->product_name }}" class="w-full h-full object-contain" />
                                        </div>
                                        <div>
                                            <p class="font-label-md text-sm font-bold text-on-surface">{{ $item->product_name }}</p>
                                            <p class="text-xs text-on-surface-variant">Qty: {{ $item->quantity }} &bull; Rp {{ number_format($item->unit_price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    <span class="font-mono text-sm font-bold text-on-surface">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Summary & Struk Link -->
                    <div class="pt-4 mt-2 border-t border-surface-container flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="font-body-sm text-sm">
                            <span class="text-on-surface-variant">Total Pembayaran:</span>
                            <span class="font-mono font-bold text-primary text-lg ml-1">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('checkout.receipt', $order->order_number) }}" target="_blank" class="px-4 py-2 rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface font-label-md text-xs font-bold transition-colors">
                                Cetak Struk (Thermal)
                            </a>
                            <a href="{{ route('checkout.success', $order->order_number) }}" class="px-4 py-2 rounded-lg bg-primary text-on-primary font-label-md text-xs font-bold hover:bg-primary-container transition-colors">
                                Lihat Struk Resmi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
</x-app-layout>
