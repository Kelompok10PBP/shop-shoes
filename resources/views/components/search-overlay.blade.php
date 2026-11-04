<div 
    x-data="{
        open: false,
        query: '',
        results: [],
        loading: false,

        async search() {
            if (this.query.trim().length < 2) {
                this.results = [];
                return;
            }
            this.loading = true;
            try {
                const res = await fetch(`/search/suggest?q=${encodeURIComponent(this.query)}`);
                if (res.ok) {
                    const data = await res.json();
                    this.results = data.products || [];
                }
            } catch (e) {
                console.error(e);
            } finally {
                this.loading = false;
            }
        }
    }"
    @open-search.window="open = true; $nextTick(() => $refs.searchInput.focus())"
    @keydown.escape.window="open = false"
    x-show="open"
    class="fixed inset-0 z-50 overflow-y-auto bg-on-surface/50 backdrop-blur-sm p-4 sm:p-6 md:p-20 flex justify-center items-start"
    style="display: none;"
>
    <!-- Modal Container -->
    <div 
        @click.outside="open = false"
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="w-full max-w-2xl bg-surface-container-lowest rounded-2xl shadow-2xl border border-surface-container overflow-hidden"
    >
        <!-- Search Input Header -->
        <div class="p-4 border-b border-surface-container flex items-center gap-3">
            <span class="material-symbols-outlined text-2xl text-on-surface-variant">search</span>
            <input 
                x-ref="searchInput"
                x-model="query" 
                @input.debounce.300ms="search()" 
                type="text" 
                placeholder="Search models, categories, running, trail..." 
                class="flex-1 bg-transparent border-none text-on-surface font-body-lg text-body-lg focus:outline-none placeholder:text-outline"
            />
            <button @click="open = false" class="text-on-surface-variant hover:text-on-surface p-1 rounded-full">
                <span class="material-symbols-outlined text-xl">close</span>
            </button>
        </div>

        <!-- Search Results Body -->
        <div class="max-h-96 overflow-y-auto p-4 divide-y divide-surface-container">
            <div x-show="loading" class="py-8 text-center text-on-surface-variant flex items-center justify-center gap-2">
                <span class="material-symbols-outlined animate-spin">progress_activity</span>
                <span>Searching catalog...</span>
            </div>

            <template x-if="!loading && results.length > 0">
                <div class="space-y-2">
                    <p class="font-label-sm text-label-sm text-outline uppercase tracking-wider mb-2">Products</p>
                    <template x-for="p in results" :key="p.id">
                        <a :href="`/products/${p.slug}`" class="flex items-center gap-4 p-2 rounded-xl hover:bg-surface-container transition-colors group">
                            <img :src="p.image" :alt="p.name" class="w-14 h-14 object-contain rounded-lg bg-surface-container-low p-1" />
                            <div class="flex-1">
                                <h4 x-text="p.name" class="font-label-lg text-label-lg text-on-surface group-hover:text-primary transition-colors font-bold"></h4>
                                <p x-text="p.category" class="font-body-sm text-body-sm text-on-surface-variant"></p>
                            </div>
                            <div class="text-right">
                                <span x-text="`Rp ${Number(p.price).toLocaleString('id-ID')}`" class="font-label-lg text-label-lg text-on-surface font-bold"></span>
                            </div>
                        </a>
                    </template>
                </div>
            </template>

            <template x-if="!loading && query.length >= 2 && results.length === 0">
                <div class="py-12 text-center text-on-surface-variant">
                    <p class="font-body-md text-body-md">No products found matching "<span x-text="query" class="font-bold text-on-surface"></span>"</p>
                </div>
            </template>
        </div>

        <!-- Footer view all results -->
        <div x-show="query.length >= 2" class="p-3 bg-surface-container-low border-t border-surface-container text-right">
            <a :href="`/search?q=${encodeURIComponent(query)}`" class="font-label-md text-label-md text-primary font-bold hover:underline inline-flex items-center gap-1">
                <span>View all search results</span>
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </a>
        </div>
    </div>
</div>
