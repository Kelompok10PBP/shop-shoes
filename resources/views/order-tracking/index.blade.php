<x-app-layout title="Lacak Pesanan | Stride Footwear">
    <div class="max-w-[1440px] mx-auto w-full px-margin-mobile lg:px-margin py-space-xl flex flex-col items-center">
        
        <div class="w-full max-w-lg bg-surface-container-lowest rounded-2xl p-space-lg sm:p-space-xl border border-surface-container shadow-sm">
            <div class="text-center mb-6">
                <div class="w-14 h-14 rounded-full bg-secondary-container text-primary mx-auto flex items-center justify-center mb-3">
                    <span class="material-symbols-outlined text-3xl">local_shipping</span>
                </div>
                <h1 class="font-headline-sm text-2xl font-bold text-on-surface">Lacak Status Pesanan</h1>
                <p class="font-body-sm text-xs text-on-surface-variant mt-1">
                    Masukkan nomor pesanan (contoh: STR-000101) dan alamat email yang Anda gunakan saat checkout untuk melihat status pengiriman dan mencetak ulang struk pembayaran.
                </p>
            </div>

            <form action="{{ route('order-tracking.lookup') }}" method="POST" class="flex flex-col gap-4">
                @csrf
                <div>
                    <label for="order_number" class="block font-label-md text-xs font-semibold text-on-surface mb-1">Nomor Pesanan</label>
                    <input 
                        type="text" 
                        name="order_number" 
                        id="order_number" 
                        required 
                        placeholder="Contoh: STR-000101" 
                        class="w-full h-11 px-3 rounded-lg bg-surface-container-low border border-surface-container text-sm font-mono font-bold uppercase focus:border-primary focus:bg-surface-container-lowest focus:outline-none"
                    />
                </div>

                <div>
                    <label for="email" class="block font-label-md text-xs font-semibold text-on-surface mb-1">Email Pemesan</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        required 
                        placeholder="email@domain.com" 
                        class="w-full h-11 px-3 rounded-lg bg-surface-container-low border border-surface-container text-sm font-body-md focus:border-primary focus:bg-surface-container-lowest focus:outline-none"
                    />
                </div>

                <button 
                    type="submit" 
                    class="w-full h-12 rounded-lg bg-primary text-on-primary font-label-lg text-sm font-bold hover:bg-primary-container transition-all flex items-center justify-center gap-2 mt-2 shadow-sm"
                >
                    <span class="material-symbols-outlined">search</span>
                    <span>Lacak & Buka Struk Pesanan</span>
                </button>
            </form>
        </div>

    </div>
</x-app-layout>
