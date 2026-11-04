<x-app-layout title="Checkout & Pembayaran | Stride Footwear">
    <div class="max-w-[1440px] mx-auto w-full px-margin-mobile lg:px-margin py-space-lg">
        
        <!-- Breadcrumbs & Heading -->
        <nav class="flex items-center gap-2 text-on-surface-variant font-label-sm text-label-sm mb-space-md">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <a href="{{ route('cart.index') }}" class="hover:text-primary transition-colors">Bag</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-on-surface font-bold">Checkout & Pembayaran</span>
        </nav>

        <div class="flex items-center justify-between mb-space-lg">
            <div>
                <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight font-bold">Checkout</h1>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1">Lengkapi data pengiriman dan pilih metode pembayaran untuk memproses pesanan dan mencetak struk.</p>
            </div>
            <div class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-full bg-secondary-fixed text-on-secondary-fixed font-label-sm text-label-sm font-semibold">
                <span class="material-symbols-outlined text-base">verified_user</span>
                <span>Transaksi Terenkripsi 256-bit SSL</span>
            </div>
        </div>

        @if(session('error'))
            <div class="mb-6 p-4 rounded-xl bg-error-container text-on-error-container border border-error/20 flex items-center gap-3">
                <span class="material-symbols-outlined text-xl text-error">error</span>
                <span class="font-body-md text-body-md">{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-error-container text-on-error-container border border-error/20 flex flex-col gap-1">
                <div class="flex items-center gap-2 font-bold text-error">
                    <span class="material-symbols-outlined text-xl">warning</span>
                    <span>Terdapat data yang belum lengkap:</span>
                </div>
                <ul class="list-disc list-inside text-sm mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form 
            action="{{ route('checkout.store') }}" 
            method="POST" 
            id="checkout-form"
            x-data="{
                shippingMethod: '{{ old('shipping_method', 'standard') }}',
                paymentMethod: '{{ old('payment_method', 'card') }}',
                standardFee: {{ $cart['subtotal'] >= $cart['free_shipping_threshold'] ? 0 : 0 }},
                expressFee: 45000,
                subtotal: {{ $cart['subtotal'] }},
                discount: {{ $cart['discount_amount'] ?? 0 }},
                taxRate: 0.11,
                isSubmitting: false,

                formatRupiah(num) {
                    return 'Rp ' + Number(num || 0).toLocaleString('id-ID');
                },

                get shippingFee() {
                    return this.shippingMethod === 'express' ? this.expressFee : this.standardFee;
                },
                get taxableAmount() {
                    return Math.max(0, this.subtotal - this.discount);
                },
                get taxAmount() {
                    return Math.round(this.taxableAmount * this.taxRate);
                },
                get grandTotal() {
                    return Math.round(this.taxableAmount + this.shippingFee + this.taxAmount);
                },

                fillDemoCard() {
                    document.getElementById('card_name').value = 'Budi Santoso';
                    document.getElementById('card_number').value = '4242 4242 4242 4242';
                    document.getElementById('card_expiry').value = '12/28';
                    document.getElementById('card_cvv').value = '123';
                    Alpine.store('toast').show('Data kartu demo terisi otomatis!');
                },

                fillDemoCustomer() {
                    const fields = {
                        'recipient_name': 'Budi Santoso',
                        'guest_email': 'budi.santoso@example.com',
                        'guest_phone': '081234567890',
                        'address_line1': 'Jl. Sudirman No. 45 Tower B Lt. 12',
                        'address_line2': 'Kecamatan Setiabudi',
                        'city': 'Jakarta Selatan',
                        'state': 'DKI Jakarta',
                        'postal_code': '12920'
                    };
                    for (const [id, val] of Object.entries(fields)) {
                        const el = document.getElementById(id);
                        if (el) el.value = val;
                    }
                    Alpine.store('toast').show('Data pembeli otomatis terisi!');
                }
            }"
            class="grid grid-cols-1 lg:grid-cols-12 gap-gutter"
        >
            @csrf

            <!-- LEFT COLUMN: Forms (8 Cols) -->
            <div class="lg:col-span-7 xl:col-span-8 flex flex-col gap-space-lg">

                <!-- 1. Customer & Shipping Address -->
                <div class="bg-surface-container-lowest rounded-2xl p-space-md lg:p-space-lg border border-surface-container/40 shadow-sm">
                    <div class="flex items-center justify-between pb-space-sm mb-space-md border-b border-surface-container">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-full bg-primary-container text-on-primary flex items-center justify-center font-bold text-sm">1</span>
                            <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Informasi Pelanggan & Pengiriman</h2>
                        </div>
                        <button 
                            type="button" 
                            @click="fillDemoCustomer()" 
                            class="text-xs px-2.5 py-1 rounded-lg bg-surface-container hover:bg-surface-container-high text-primary font-semibold transition-colors flex items-center gap-1"
                        >
                            <span class="material-symbols-outlined text-sm">bolt</span>
                            <span>Isi Data Demo Cepat</span>
                        </button>
                    </div>

                    @if($savedAddresses->isNotEmpty())
                        <div class="mb-4">
                            <label class="block font-label-md text-label-md text-on-surface mb-2 font-semibold">Gunakan Alamat Tersimpan</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                                @foreach($savedAddresses as $addr)
                                    <label class="border border-surface-container rounded-xl p-3 flex items-start gap-3 cursor-pointer hover:border-primary transition-colors">
                                        <input type="radio" name="address_id" value="{{ $addr->id }}" class="mt-1 text-primary focus:ring-primary" {{ $loop->first ? 'checked' : '' }}>
                                        <div>
                                            <p class="font-label-md text-label-md text-on-surface font-bold">{{ $addr->recipient_name }} ({{ $addr->label ?? 'Alamat' }})</p>
                                            <p class="font-body-sm text-body-sm text-on-surface-variant">{{ $addr->address_line1 }}, {{ $addr->city }}</p>
                                            <p class="font-body-sm text-[12px] text-outline">{{ $addr->phone }}</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <p class="text-xs text-on-surface-variant mb-2">Atau masukkan alamat baru di bawah ini:</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label for="recipient_name" class="block font-label-md text-label-md text-on-surface mb-1 font-semibold">Nama Lengkap Penerima <span class="text-error">*</span></label>
                            <input 
                                type="text" 
                                id="recipient_name" 
                                name="recipient_name" 
                                required 
                                value="{{ old('recipient_name', Auth::user()?->name ?? 'Budi Santoso') }}"
                                class="w-full h-11 px-3 rounded-lg bg-surface-container-low border border-surface-container focus:border-primary focus:bg-surface-container-lowest focus:outline-none transition-colors font-body-md text-on-surface"
                                placeholder="Contoh: Budi Santoso"
                            />
                        </div>

                        <div>
                            <label for="guest_email" class="block font-label-md text-label-md text-on-surface mb-1 font-semibold">Email Konfirmasi / Struk <span class="text-error">*</span></label>
                            <input 
                                type="email" 
                                id="guest_email" 
                                name="guest_email" 
                                required 
                                value="{{ old('guest_email', Auth::user()?->email ?? 'budi.santoso@example.com') }}"
                                class="w-full h-11 px-3 rounded-lg bg-surface-container-low border border-surface-container focus:border-primary focus:bg-surface-container-lowest focus:outline-none transition-colors font-body-md text-on-surface"
                                placeholder="email@domain.com"
                            />
                        </div>

                        <div>
                            <label for="guest_phone" class="block font-label-md text-label-md text-on-surface mb-1 font-semibold">Nomor WhatsApp / HP <span class="text-error">*</span></label>
                            <input 
                                type="tel" 
                                id="guest_phone" 
                                name="guest_phone" 
                                required 
                                oninput="document.getElementById('phone').value = this.value"
                                value="{{ old('guest_phone', Auth::user()?->phone ?? '081234567890') }}"
                                class="w-full h-11 px-3 rounded-lg bg-surface-container-low border border-surface-container focus:border-primary focus:bg-surface-container-lowest focus:outline-none transition-colors font-body-md text-on-surface"
                                placeholder="081234567890"
                            />
                            <input type="hidden" name="phone" id="phone" value="{{ old('phone', Auth::user()?->phone ?? '081234567890') }}">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="address_line1" class="block font-label-md text-label-md text-on-surface mb-1 font-semibold">Alamat Lengkap <span class="text-error">*</span></label>
                            <input 
                                type="text" 
                                id="address_line1" 
                                name="address_line1" 
                                required 
                                value="{{ old('address_line1', 'Jl. Jenderal Sudirman No. 45, Tower B Lt. 12') }}"
                                class="w-full h-11 px-3 rounded-lg bg-surface-container-low border border-surface-container focus:border-primary focus:bg-surface-container-lowest focus:outline-none transition-colors font-body-md text-on-surface"
                                placeholder="Nama jalan, nomor rumah, RT/RW, gedung/patokan"
                            />
                        </div>

                        <div class="sm:col-span-2">
                            <label for="address_line2" class="block font-label-md text-label-md text-on-surface mb-1 font-semibold">Kelurahan / Kecamatan / Keterangan (Opsional)</label>
                            <input 
                                type="text" 
                                id="address_line2" 
                                name="address_line2" 
                                value="{{ old('address_line2', 'Kecamatan Setiabudi') }}"
                                class="w-full h-11 px-3 rounded-lg bg-surface-container-low border border-surface-container focus:border-primary focus:bg-surface-container-lowest focus:outline-none transition-colors font-body-md text-on-surface"
                                placeholder="Contoh: Titip di pos satpam jika tidak ada orang"
                            />
                        </div>

                        <div>
                            <label for="city" class="block font-label-md text-label-md text-on-surface mb-1 font-semibold">Kota / Kabupaten <span class="text-error">*</span></label>
                            <input 
                                type="text" 
                                id="city" 
                                name="city" 
                                required 
                                value="{{ old('city', 'Jakarta Selatan') }}"
                                class="w-full h-11 px-3 rounded-lg bg-surface-container-low border border-surface-container focus:border-primary focus:bg-surface-container-lowest focus:outline-none transition-colors font-body-md text-on-surface"
                                placeholder="Kota"
                            />
                        </div>

                        <div>
                            <label for="state" class="block font-label-md text-label-md text-on-surface mb-1 font-semibold">Provinsi <span class="text-error">*</span></label>
                            <input 
                                type="text" 
                                id="state" 
                                name="state" 
                                required 
                                value="{{ old('state', 'DKI Jakarta') }}"
                                class="w-full h-11 px-3 rounded-lg bg-surface-container-low border border-surface-container focus:border-primary focus:bg-surface-container-lowest focus:outline-none transition-colors font-body-md text-on-surface"
                                placeholder="Provinsi"
                            />
                        </div>

                        <div>
                            <label for="postal_code" class="block font-label-md text-label-md text-on-surface mb-1 font-semibold">Kode Pos <span class="text-error">*</span></label>
                            <input 
                                type="text" 
                                id="postal_code" 
                                name="postal_code" 
                                required 
                                value="{{ old('postal_code', '12920') }}"
                                class="w-full h-11 px-3 rounded-lg bg-surface-container-low border border-surface-container focus:border-primary focus:bg-surface-container-lowest focus:outline-none transition-colors font-body-md text-on-surface"
                                placeholder="12920"
                            />
                        </div>

                        <div>
                            <label for="country" class="block font-label-md text-label-md text-on-surface mb-1 font-semibold">Negara</label>
                            <input 
                                type="text" 
                                id="country" 
                                name="country" 
                                readonly 
                                value="Indonesia"
                                class="w-full h-11 px-3 rounded-lg bg-surface-container text-on-surface-variant border border-surface-container cursor-not-allowed font-body-md"
                            />
                        </div>
                    </div>
                </div>

                <!-- 2. Shipping Method -->
                <div class="bg-surface-container-lowest rounded-2xl p-space-md lg:p-space-lg border border-surface-container/40 shadow-sm">
                    <div class="flex items-center gap-2 pb-space-sm mb-space-md border-b border-surface-container">
                        <span class="w-7 h-7 rounded-full bg-primary-container text-on-primary flex items-center justify-center font-bold text-sm">2</span>
                        <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Metode Pengiriman</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <!-- Standard -->
                        <label 
                            :class="shippingMethod === 'standard' ? 'border-primary bg-surface-container-low/50 ring-2 ring-primary/20' : 'border-surface-container hover:border-outline'"
                            class="border rounded-xl p-4 flex items-start gap-3 cursor-pointer transition-all"
                        >
                            <input 
                                type="radio" 
                                name="shipping_method" 
                                value="standard" 
                                x-model="shippingMethod" 
                                class="mt-1 text-primary focus:ring-primary"
                            />
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="font-label-lg text-label-lg text-on-surface font-bold">Standard Delivery</span>
                                    <span class="font-label-md text-label-md text-primary font-bold">
                                        {{ $cart['subtotal'] >= $cart['free_shipping_threshold'] ? 'GRATIS' : 'Rp 20.000' }}
                                    </span>
                                </div>
                                <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">Estimasi 3 - 5 hari kerja dengan kurir reguler bergaransi.</p>
                                @if($cart['subtotal'] >= $cart['free_shipping_threshold'])
                                    <span class="inline-block mt-2 px-2 py-0.5 rounded-full bg-secondary-fixed text-on-secondary-fixed text-[11px] font-bold">
                                        Free Shipping Promo Diaktifkan
                                    </span>
                                @endif
                            </div>
                        </label>

                        <!-- Express -->
                        <label 
                            :class="shippingMethod === 'express' ? 'border-primary bg-surface-container-low/50 ring-2 ring-primary/20' : 'border-surface-container hover:border-outline'"
                            class="border rounded-xl p-4 flex items-start gap-3 cursor-pointer transition-all"
                        >
                            <input 
                                type="radio" 
                                name="shipping_method" 
                                value="express" 
                                x-model="shippingMethod" 
                                class="mt-1 text-primary focus:ring-primary"
                            />
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="font-label-lg text-label-lg text-on-surface font-bold">Express Priority</span>
                                    <span class="font-label-md text-label-md text-on-surface font-bold">Rp 45.000</span>
                                </div>
                                <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">Estimasi 1 - 2 hari kerja, prioritas dispatch & protective bio-box packing.</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- 3. Payment Method -->
                <div class="bg-surface-container-lowest rounded-2xl p-space-md lg:p-space-lg border border-surface-container/40 shadow-sm">
                    <div class="flex items-center justify-between pb-space-sm mb-space-md border-b border-surface-container">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-full bg-primary-container text-on-primary flex items-center justify-center font-bold text-sm">3</span>
                            <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Metode Pembayaran</h2>
                        </div>
                        <span class="text-xs text-on-surface-variant">Struk akan diterbitkan otomatis setelah pembayaran</span>
                    </div>

                    <div class="flex flex-col gap-3 mb-4">
                        <!-- Option A: Credit / Debit Card -->
                        <label 
                            :class="paymentMethod === 'card' ? 'border-primary bg-surface-container-low/30 ring-2 ring-primary/20' : 'border-surface-container hover:border-outline'"
                            class="border rounded-xl p-4 cursor-pointer transition-all"
                        >
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" value="card" x-model="paymentMethod" class="text-primary focus:ring-primary" />
                                    <div>
                                        <span class="font-label-lg text-label-lg text-on-surface font-bold">Kartu Kredit / Debit Online</span>
                                        <p class="font-body-sm text-body-sm text-on-surface-variant">Visa, Mastercard, JCB (Konfirmasi Otomatis & Cetak Struk Instan)</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5 text-on-surface-variant">
                                    <span class="material-symbols-outlined text-2xl text-primary">credit_card</span>
                                </div>
                            </div>

                            <!-- Card Form Fields -->
                            <div x-show="paymentMethod === 'card'" x-transition class="mt-4 pt-4 border-t border-surface-container/60 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="sm:col-span-2 flex justify-between items-center">
                                    <span class="font-label-sm text-xs text-on-surface-variant">Data Kartu Transaksi:</span>
                                    <button 
                                        type="button" 
                                        @click.prevent="fillDemoCard()" 
                                        class="text-xs px-2 py-1 rounded bg-secondary-container text-on-secondary-container font-semibold hover:bg-secondary-fixed transition-colors flex items-center gap-1"
                                    >
                                        <span class="material-symbols-outlined text-xs">auto_fix_high</span>
                                        <span>Gunakan Kartu Demo</span>
                                    </button>
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block font-label-sm text-xs text-on-surface-variant mb-1 font-semibold">Nama Pemegang Kartu</label>
                                    <input type="text" id="card_name" name="card_name" value="Budi Santoso" class="w-full h-10 px-3 rounded-lg bg-surface-container-lowest border border-surface-container focus:border-primary text-sm font-body-md" placeholder="Nama Sesuai Kartu" />
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block font-label-sm text-xs text-on-surface-variant mb-1 font-semibold">Nomor Kartu (16 Digit)</label>
                                    <input type="text" id="card_number" name="card_number" value="4242 4242 4242 4242" maxlength="19" class="w-full h-10 px-3 rounded-lg bg-surface-container-lowest border border-surface-container focus:border-primary text-sm font-mono" placeholder="4242 •••• •••• 4242" />
                                </div>
                                <div>
                                    <label class="block font-label-sm text-xs text-on-surface-variant mb-1 font-semibold">Masa Berlaku (MM/YY)</label>
                                    <input type="text" id="card_expiry" name="card_expiry" value="12/28" maxlength="5" class="w-full h-10 px-3 rounded-lg bg-surface-container-lowest border border-surface-container focus:border-primary text-sm font-mono" placeholder="MM/YY" />
                                </div>
                                <div>
                                    <label class="block font-label-sm text-xs text-on-surface-variant mb-1 font-semibold">CVV / CVC</label>
                                    <input type="password" id="card_cvv" name="card_cvv" value="123" maxlength="4" class="w-full h-10 px-3 rounded-lg bg-surface-container-lowest border border-surface-container focus:border-primary text-sm font-mono" placeholder="•••" />
                                </div>
                            </div>
                        </label>

                        <!-- Option B: Bank Transfer / Virtual Account -->
                        <label 
                            :class="paymentMethod === 'bank_transfer' ? 'border-primary bg-surface-container-low/30 ring-2 ring-primary/20' : 'border-surface-container hover:border-outline'"
                            class="border rounded-xl p-4 cursor-pointer transition-all"
                        >
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" value="bank_transfer" x-model="paymentMethod" class="text-primary focus:ring-primary" />
                                    <div>
                                        <span class="font-label-lg text-label-lg text-on-surface font-bold">Transfer Bank / Virtual Account (VA)</span>
                                        <p class="font-body-sm text-body-sm text-on-surface-variant">BCA, Mandiri, BRI, BNI (Verifikasi Otomatis 24 Jam)</p>
                                    </div>
                                </div>
                                <span class="material-symbols-outlined text-2xl text-primary">account_balance</span>
                            </div>

                            <div x-show="paymentMethod === 'bank_transfer'" x-transition class="mt-4 pt-4 border-t border-surface-container/60">
                                <label class="block font-label-sm text-xs text-on-surface-variant mb-1.5 font-semibold">Pilih Bank Pembayaran:</label>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                    <label class="border border-surface-container rounded-lg p-2.5 text-center cursor-pointer hover:border-primary">
                                        <input type="radio" name="bank_name" value="BCA" checked class="text-primary focus:ring-primary mr-1" />
                                        <span class="font-bold text-sm text-on-surface">BCA VA</span>
                                    </label>
                                    <label class="border border-surface-container rounded-lg p-2.5 text-center cursor-pointer hover:border-primary">
                                        <input type="radio" name="bank_name" value="Mandiri" class="text-primary focus:ring-primary mr-1" />
                                        <span class="font-bold text-sm text-on-surface">Mandiri VA</span>
                                    </label>
                                    <label class="border border-surface-container rounded-lg p-2.5 text-center cursor-pointer hover:border-primary">
                                        <input type="radio" name="bank_name" value="BRI" class="text-primary focus:ring-primary mr-1" />
                                        <span class="font-bold text-sm text-on-surface">BRI VA</span>
                                    </label>
                                    <label class="border border-surface-container rounded-lg p-2.5 text-center cursor-pointer hover:border-primary">
                                        <input type="radio" name="bank_name" value="BNI" class="text-primary focus:ring-primary mr-1" />
                                        <span class="font-bold text-sm text-on-surface">BNI VA</span>
                                    </label>
                                </div>
                                <p class="text-xs text-on-surface-variant mt-2">Nomor Virtual Account & Struk Pembayaran Resmi akan langsung ditampilkan setelah klik bayar.</p>
                            </div>
                        </label>

                        <!-- Option C: Cash on Delivery (COD) -->
                        <label 
                            :class="paymentMethod === 'cod' ? 'border-primary bg-surface-container-low/30 ring-2 ring-primary/20' : 'border-surface-container hover:border-outline'"
                            class="border rounded-xl p-4 cursor-pointer transition-all"
                        >
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" value="cod" x-model="paymentMethod" class="text-primary focus:ring-primary" />
                                    <div>
                                        <span class="font-label-lg text-label-lg text-on-surface font-bold">Bayar di Tempat (COD)</span>
                                        <p class="font-body-sm text-body-sm text-on-surface-variant">Bayar tunai kepada kurir saat pesanan sepatu sampai di tangan Anda</p>
                                    </div>
                                </div>
                                <span class="material-symbols-outlined text-2xl text-primary">payments</span>
                            </div>
                        </label>
                    </div>

                    <!-- Notes Field -->
                    <div class="mt-4 pt-4 border-t border-surface-container">
                        <label for="notes" class="block font-label-md text-label-md text-on-surface mb-1 font-semibold">Catatan Khusus untuk Penjual / Kurir (Opsional)</label>
                        <textarea 
                            name="notes" 
                            id="notes" 
                            rows="2" 
                            class="w-full p-3 rounded-lg bg-surface-container-low border border-surface-container focus:border-primary focus:bg-surface-container-lowest focus:outline-none transition-colors font-body-sm text-on-surface"
                            placeholder="Contoh: Mohon hubungi sebelum mengantar atau cantumkan ucapan selamat ulang tahun"
                        >{{ old('notes') }}</textarea>
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN: Order Summary (4-5 Cols) -->
            <div class="lg:col-span-5 xl:col-span-4">
                <div class="bg-surface-container-lowest rounded-2xl p-space-md lg:p-space-lg border border-surface-container/40 shadow-sm sticky top-32">
                    
                    <div class="flex items-center justify-between pb-space-sm mb-space-md border-b border-surface-container">
                        <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Ringkasan Pesanan</h2>
                        <span class="font-label-sm text-label-sm text-on-surface-variant font-bold">{{ $cart['total_items'] }} Barang</span>
                    </div>

                    <!-- Item List Preview -->
                    <div class="divide-y divide-surface-container max-h-72 overflow-y-auto mb-4 pr-1">
                        @foreach($cart['items'] as $item)
                            <div class="py-3 flex items-center gap-3">
                                <div class="w-14 h-14 bg-surface-container rounded-lg overflow-hidden flex-shrink-0 flex items-center justify-center">
                                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-contain p-1" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-label-md text-label-md text-on-surface font-bold truncate">{{ $item['name'] }}</h4>
                                    <p class="font-body-sm text-[12px] text-on-surface-variant">
                                        {{ $item['variant_label'] ?: 'Standard' }} &times; {{ $item['quantity'] }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <span class="font-label-md text-label-md text-on-surface font-bold">
                                        Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Price Calculations -->
                    <div class="flex flex-col gap-2.5 pt-3 border-t border-surface-container text-body-md font-body-md">
                        <div class="flex justify-between text-on-surface-variant">
                            <span>Subtotal Produk</span>
                            <span class="font-semibold text-on-surface">Rp {{ number_format($cart['subtotal'], 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between text-on-surface-variant">
                            <span>Biaya Pengiriman</span>
                            <span class="font-semibold text-on-surface" x-text="shippingFee === 0 ? 'GRATIS' : formatRupiah(shippingFee)"></span>
                        </div>

                        @if(($cart['discount_amount'] ?? 0) > 0)
                            <div class="flex justify-between text-primary font-semibold">
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">local_offer</span>
                                    <span>Diskon Promo</span>
                                </span>
                                <span>-Rp {{ number_format($cart['discount_amount'], 0, ',', '.') }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between text-on-surface-variant">
                            <span>Pajak (PPN 11%)</span>
                            <span class="font-semibold text-on-surface" x-text="formatRupiah(taxAmount)"></span>
                        </div>

                        <!-- Grand Total -->
                        <div class="flex justify-between items-baseline pt-3 mt-1 border-t border-surface-container text-on-surface">
                            <span class="font-headline-sm text-headline-sm font-bold">Total Pembayaran</span>
                            <span class="font-headline-lg text-2xl text-primary font-bold" x-text="formatRupiah(grandTotal)"></span>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="mt-space-lg flex flex-col gap-2">
                        <button 
                            type="submit" 
                            class="w-full h-12 rounded-lg bg-primary text-on-primary font-label-lg text-label-lg hover:bg-primary-container transition-all flex items-center justify-center gap-2 shadow-md hover:shadow-lg font-bold cursor-pointer"
                        >
                            <span class="material-symbols-outlined">receipt_long</span>
                            <span>Bayar Sekarang & Tampilkan Struk</span>
                        </button>

                        <button 
                            type="button" 
                            @click="fillDemoCustomer(); fillDemoCard(); setTimeout(() => document.getElementById('checkout-form').submit(), 300);"
                            class="w-full h-10 rounded-lg bg-secondary-fixed text-on-secondary-fixed hover:bg-secondary-container transition-all flex items-center justify-center gap-2 font-label-md text-xs font-bold"
                        >
                            <span class="material-symbols-outlined text-sm">flash_on</span>
                            <span>1-Klik Isi Demo & Bayar Langsung</span>
                        </button>

                        <div class="flex items-center justify-center gap-4 mt-4 text-outline font-label-sm text-[11px]">
                            <span class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm text-primary">verified</span>
                                <span>Garansi 30 Hari</span>
                            </span>
                            <span class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm text-primary">lock</span>
                                <span>Pembayaran Aman</span>
                            </span>
                        </div>
                    </div>

                </div>
            </div>

        </form>

    </div>
</x-app-layout>
