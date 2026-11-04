<x-app-layout title="Struk Bukti Pembayaran #{{ $order->order_number }} | Stride Footwear">
    <div class="max-w-[1440px] mx-auto w-full px-margin-mobile lg:px-margin py-space-lg print:p-0 print:m-0">
        
        <!-- Breadcrumbs (Hidden on print) -->
        <nav class="flex items-center gap-2 text-on-surface-variant font-label-sm text-label-sm mb-space-md print:hidden">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <a href="{{ route('shop') }}" class="hover:text-primary transition-colors">Shop</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-on-surface font-bold">Struk Pembayaran</span>
        </nav>

        <!-- Top Celebration Header (Hidden on print) -->
        <div class="mb-space-lg text-center max-w-2xl mx-auto print:hidden">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-secondary-container text-primary mb-4 shadow-sm animate-bounce">
                <span class="material-symbols-outlined text-4xl">check_circle</span>
            </div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-secondary-fixed text-on-secondary-fixed mb-2 font-label-sm text-label-sm font-bold">
                <span class="w-2 h-2 rounded-full bg-primary"></span>
                <span>TRANSAKSI BERHASIL & LUNAS</span>
            </div>
            <h1 class="font-headline-lg text-3xl sm:text-4xl text-on-surface font-bold tracking-tight mb-2">
                Terima Kasih Atas Pesanan Anda!
            </h1>
            <p class="font-body-md text-body-md text-on-surface-variant">
                Pembayaran Anda telah kami verifikasi. Struk bukti pembayaran digital resmi telah diterbitkan di bawah ini dan salinan telah dikirimkan ke <strong class="text-on-surface">{{ $order->customer_email }}</strong>.
            </p>

            <!-- Quick Action Bar -->
            <div class="flex flex-wrap items-center justify-center gap-3 mt-6">
                <button 
                    onclick="window.print()" 
                    type="button" 
                    class="h-11 px-5 rounded-lg bg-primary text-on-primary font-label-md text-label-md hover:bg-primary-container transition-all flex items-center gap-2 shadow-sm font-semibold cursor-pointer"
                >
                    <span class="material-symbols-outlined text-lg">print</span>
                    <span>Cetak Struk (Print)</span>
                </button>

                <a 
                    href="{{ route('checkout.receipt', $order->order_number) }}" 
                    target="_blank" 
                    class="h-11 px-5 rounded-lg bg-surface-container-lowest border border-surface-container hover:bg-surface-container text-on-surface font-label-md text-label-md transition-all flex items-center gap-2 shadow-sm font-semibold"
                >
                    <span class="material-symbols-outlined text-lg">receipt</span>
                    <span>Format Thermal / Kasir</span>
                </a>

                <button 
                    type="button" 
                    onclick="navigator.clipboard.writeText('{{ $order->order_number }}'); Alpine.store('toast').show('Nomor pesanan {{ $order->order_number }} berhasil disalin!');" 
                    class="h-11 px-4 rounded-lg bg-surface-container-low text-on-surface hover:bg-surface-container font-label-md text-label-md transition-all flex items-center gap-1.5 font-semibold"
                >
                    <span class="material-symbols-outlined text-lg">content_copy</span>
                    <span>Salin No. Pesanan</span>
                </button>
            </div>
        </div>

        <!-- THE OFFICIAL STRUK PEMBAYARAN CARD (RECEIPT) -->
        <div class="max-w-3xl mx-auto bg-surface-container-lowest rounded-2xl shadow-md border border-surface-container overflow-hidden print:shadow-none print:border-none print:max-w-full">
            
            <!-- Receipt Header Strip -->
            <div class="bg-primary text-on-primary p-6 sm:p-8 relative">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="material-symbols-outlined text-2xl text-primary-fixed">sprint</span>
                            <span class="font-headline-sm text-2xl font-bold tracking-tight">STRIDE FOOTWEAR</span>
                        </div>
                        <p class="font-body-sm text-xs text-primary-fixed/80">PT Stride Footwear Indonesia &bull; Reg. No: ST-8891024</p>
                        <p class="font-body-sm text-xs text-primary-fixed/80">Grand Indonesia Level 3, Jl. M.H. Thamrin No. 1, Jakarta</p>
                    </div>

                    <div class="sm:text-right">
                        <span class="inline-block px-3 py-1 rounded-full bg-secondary-fixed text-on-secondary-fixed font-label-sm text-xs font-bold uppercase tracking-wider mb-1">
                            STRUK RESMI
                        </span>
                        <h2 class="font-mono text-xl sm:text-2xl font-bold tracking-wider text-white">
                            #{{ $order->order_number }}
                        </h2>
                        <p class="font-body-sm text-xs text-primary-fixed/90">
                            {{ $order->created_at ? $order->created_at->format('d M Y, H:i') : now()->format('d M Y, H:i') }} WIB
                        </p>
                    </div>
                </div>

                <!-- Watermark / Stamp "LUNAS" -->
                <div class="absolute right-6 top-1/2 -translate-y-1/2 opacity-15 pointer-events-none hidden sm:block">
                    <div class="border-4 border-white px-4 py-2 rounded-xl text-3xl font-extrabold tracking-widest uppercase transform rotate-12">
                        PAID / LUNAS
                    </div>
                </div>
            </div>

            <!-- Receipt Meta Grid -->
            <div class="p-6 sm:p-8 bg-surface-container-low/40 border-b border-surface-container">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-left">
                    <div>
                        <span class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant font-bold">Status Pembayaran</span>
                        <div class="mt-1 inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-secondary-fixed text-on-secondary-fixed font-label-sm text-xs font-bold">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                            <span>{{ strtoupper($order->payment?->status ?? 'LUNAS') }}</span>
                        </div>
                    </div>

                    <div>
                        <span class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant font-bold">Metode Bayar</span>
                        <p class="font-label-md text-sm font-bold text-on-surface mt-1">
                            @if(($order->payment?->payment_method ?? '') === 'card')
                                Kartu Kredit/Debit
                            @elseif(($order->payment?->payment_method ?? '') === 'bank_transfer')
                                Transfer Virtual Account
                            @elseif(($order->payment?->payment_method ?? '') === 'cod')
                                COD (Bayar di Tempat)
                            @else
                                {{ ucfirst($order->payment?->payment_method ?? 'Online Payment') }}
                            @endif
                        </p>
                    </div>

                    <div>
                        <span class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant font-bold">ID Transaksi</span>
                        <p class="font-mono text-xs font-bold text-on-surface mt-1 truncate">
                            {{ $order->payment?->transaction_id ?? ('TXN-' . strtoupper(substr(md5($order->id), 0, 10))) }}
                        </p>
                    </div>

                    <div>
                        <span class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant font-bold">Layanan Kirim</span>
                        <p class="font-label-md text-sm font-bold text-on-surface mt-1 capitalize">
                            {{ $order->shipping_method ?? 'Standard' }} Delivery
                        </p>
                    </div>
                </div>
            </div>

            <!-- Customer & Shipping Information -->
            <div class="p-6 sm:p-8 border-b border-surface-container">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-label-sm text-xs uppercase tracking-wider text-on-surface-variant font-bold mb-2 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-sm text-primary">person</span>
                            <span>Informasi Pembeli</span>
                        </h3>
                        <p class="font-label-md text-sm text-on-surface font-bold">{{ $order->customer_name }}</p>
                        <p class="font-body-sm text-xs text-on-surface-variant mt-0.5">{{ $order->customer_email }}</p>
                        <p class="font-body-sm text-xs text-on-surface-variant mt-0.5">{{ $order->guest_phone ?? ($order->shipping_address['phone'] ?? '-') }}</p>
                    </div>

                    <div>
                        <h3 class="font-label-sm text-xs uppercase tracking-wider text-on-surface-variant font-bold mb-2 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-sm text-primary">location_on</span>
                            <span>Alamat Pengiriman</span>
                        </h3>
                        @php
                            $addr = is_array($order->shipping_address) ? $order->shipping_address : json_decode($order->shipping_address, true);
                        @endphp
                        <p class="font-label-md text-sm text-on-surface font-bold">{{ $addr['recipient_name'] ?? $order->customer_name }}</p>
                        <p class="font-body-sm text-xs text-on-surface-variant mt-0.5">
                            {{ $addr['address_line1'] ?? '' }}{{ !empty($addr['address_line2']) ? ', ' . $addr['address_line2'] : '' }}
                        </p>
                        <p class="font-body-sm text-xs text-on-surface-variant mt-0.5">
                            {{ $addr['city'] ?? '' }}, {{ $addr['state'] ?? '' }} {{ $addr['postal_code'] ?? '' }}
                        </p>
                        <p class="font-body-sm text-xs text-on-surface-variant font-semibold mt-0.5">{{ $addr['country'] ?? 'Indonesia' }}</p>
                    </div>
                </div>

                @if(!empty($order->notes))
                    <div class="mt-4 p-3 rounded-lg bg-surface-container-low text-xs text-on-surface-variant">
                        <strong class="text-on-surface">Catatan Pembeli:</strong> {{ $order->notes }}
                    </div>
                @endif
            </div>

            <!-- Purchased Items Table -->
            <div class="p-6 sm:p-8 border-b border-surface-container">
                <h3 class="font-headline-sm text-base text-on-surface font-bold mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-xl">shopping_cart_checkout</span>
                    <span>Rincian Produk yang Dibeli</span>
                </h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-surface-container text-on-surface-variant font-label-sm text-xs">
                                <th class="pb-3 font-bold">Produk</th>
                                <th class="pb-3 font-bold text-center">Ukuran / Varian</th>
                                <th class="pb-3 font-bold text-center">Qty</th>
                                <th class="pb-3 font-bold text-right">Harga</th>
                                <th class="pb-3 font-bold text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-surface-container/60 font-body-sm text-sm">
                            @foreach($order->items as $item)
                                @php
                                    $variantInfo = is_array($item->variant_details) ? $item->variant_details : json_decode($item->variant_details, true);
                                    $productImg = $item->product?->display_image ?? '/images/placeholder-shoe.png';
                                @endphp
                                <tr class="py-3">
                                    <td class="py-3 pr-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 rounded-lg bg-surface-container overflow-hidden flex-shrink-0 flex items-center justify-center">
                                                <img src="{{ $productImg }}" alt="{{ $item->product_name }}" class="w-full h-full object-contain p-1" />
                                            </div>
                                            <div>
                                                <p class="font-label-md text-sm text-on-surface font-bold leading-tight">{{ $item->product_name }}</p>
                                                <p class="text-xs text-on-surface-variant">SKU: {{ $item->product?->sku ?? ('STR-' . $item->product_id) }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-center">
                                        <span class="inline-block px-2.5 py-1 rounded bg-surface-container text-xs font-semibold text-on-surface">
                                            Size {{ $variantInfo['size'] ?? ($item->variant?->size ?? 'Standard') }}
                                            @if(!empty($variantInfo['color']))
                                                &bull; {{ $variantInfo['color'] }}
                                            @endif
                                        </span>
                                    </td>
                                    <td class="py-3 text-center font-bold text-on-surface">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="py-3 text-right text-on-surface font-mono">
                                        Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 text-right text-on-surface font-bold font-mono">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Financial Calculation Summary -->
            <div class="p-6 sm:p-8 bg-surface-container-low/30">
                <div class="flex flex-col sm:flex-row justify-between gap-6">
                    
                    <!-- Left: Security & Barcode -->
                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-2 text-primary font-bold text-xs uppercase tracking-wider mb-2">
                                <span class="material-symbols-outlined text-base">verified</span>
                                <span>Verifikasi Keaslian Struk Digital</span>
                            </div>
                            <p class="font-body-sm text-xs text-on-surface-variant leading-relaxed">
                                Dokumen ini diterbitkan secara sah dan otomatis oleh sistem e-commerce Stride Footwear. Berlaku sebagai bukti pembayaran sah dan kartu garansi resmi penggantian ukuran 30 hari kalender.
                            </p>
                        </div>

                        <!-- Barcode Simulation -->
                        <div class="mt-4 pt-4 border-t border-surface-container">
                            <div class="font-mono text-center tracking-widest text-xs text-on-surface mb-1">
                                ||| | ||||| ||| |||| || ||||| |||| ||| ||||| |||| ||| ||
                            </div>
                            <p class="font-mono text-[10px] text-center text-outline uppercase tracking-wider">
                                KODE VALIDASI: {{ $order->order_number }} &bull; {{ $order->payment?->transaction_id ?? 'VERIFIED' }}
                            </p>
                        </div>
                    </div>

                    <!-- Right: Totals Box -->
                    <div class="w-full sm:w-72 flex flex-col gap-2 font-body-sm text-sm">
                        <div class="flex justify-between text-on-surface-variant">
                            <span>Subtotal Barang</span>
                            <span class="font-mono font-semibold text-on-surface">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>

                        @if($order->discount_amount > 0)
                            <div class="flex justify-between text-primary font-semibold">
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">discount</span>
                                    <span>Potongan Diskon ({{ $order->coupon_code ?? 'Promo' }})</span>
                                </span>
                                <span class="font-mono">-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between text-on-surface-variant">
                            <span>Biaya Pengiriman</span>
                            <span class="font-mono font-semibold text-on-surface">
                                {{ $order->shipping_fee == 0 ? 'GRATIS' : 'Rp ' . number_format($order->shipping_fee, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex justify-between text-on-surface-variant">
                            <span>Pajak (PPN 11%)</span>
                            <span class="font-mono font-semibold text-on-surface">Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</span>
                        </div>

                        <!-- Total Dibayar -->
                        <div class="flex justify-between items-baseline pt-3 mt-1 border-t-2 border-primary text-on-surface">
                            <span class="font-headline-sm text-base font-bold uppercase">Total Dibayar</span>
                            <span class="font-headline-lg text-2xl font-extrabold text-primary font-mono">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="text-right">
                            <span class="inline-block px-2.5 py-0.5 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-[11px] font-bold">
                                LUNAS VIA {{ strtoupper($order->payment?->payment_method ?? 'ONLINE') }}
                            </span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Receipt Footer Perforated Styling -->
            <div class="bg-surface-container p-4 text-center text-xs text-on-surface-variant border-t border-dashed border-surface-variant">
                <p class="font-semibold">Layanan Bantuan Pelanggan Stride Footwear 24/7</p>
                <p class="mt-0.5">WhatsApp Concierge: 0812-3456-7890 &bull; Email: help@stridefootwear.com</p>
            </div>

        </div>

        <!-- Bottom Navigation Links (Hidden on print) -->
        <div class="flex flex-wrap items-center justify-center gap-4 mt-space-xl print:hidden">
            <a 
                href="{{ route('shop') }}" 
                class="h-12 px-6 rounded-lg bg-surface-container-low text-on-surface hover:bg-surface-container font-label-md text-label-md transition-all flex items-center gap-2 font-semibold"
            >
                <span class="material-symbols-outlined">arrow_back</span>
                <span>Lanjut Belanja Sepatu</span>
            </a>

            <a 
                href="{{ route('order-tracking.index') }}" 
                class="h-12 px-6 rounded-lg bg-primary text-on-primary hover:bg-primary-container font-label-md text-label-md transition-all flex items-center gap-2 font-semibold shadow-sm"
            >
                <span class="material-symbols-outlined">local_shipping</span>
                <span>Lacak Status Pengiriman Pesanan</span>
            </a>
        </div>

    </div>

    <!-- Print CSS Rules to format receipt perfectly on Paper or PDF -->
    <style>
        @media print {
            body {
                background-color: white !important;
                color: black !important;
            }
            .fixed, header, footer, x-announcement-bar, x-header, x-footer, nav, .print\:hidden {
                display: none !important;
            }
            main {
                padding-top: 0 !important;
                margin: 0 !important;
            }
        }
    </style>
</x-app-layout>
