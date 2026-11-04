import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Register $money magic helper for Indonesian Rupiah formatting
Alpine.magic('money', () => {
    return (val) => 'Rp ' + Number(val || 0).toLocaleString('id-ID');
});

// Global Toast Notification Store
Alpine.store('toast', {
    visible: false,
    message: '',
    type: 'success', // 'success', 'error', 'info'
    timeout: null,

    show(message, type = 'success', duration = 3500) {
        this.message = message;
        this.type = type;
        this.visible = true;

        if (this.timeout) clearTimeout(this.timeout);
        this.timeout = setTimeout(() => {
            this.visible = false;
        }, duration);
    },

    close() {
        this.visible = false;
    }
});

// Global Live Cart Store
Alpine.store('cart', {
    isOpen: false,
    loading: false,
    items: [],
    subtotal: 0,
    totalItems: 0,
    freeShippingThreshold: 500000,
    freeShippingUnlocked: false,
    freeShippingRemaining: 500000,
    freeShippingProgress: 0,

    init() {
        this.fetch();
    },

    open() {
        this.isOpen = true;
        document.body.style.overflow = 'hidden';
    },

    close() {
        this.isOpen = false;
        document.body.style.overflow = '';
    },

    toggle() {
        if (this.isOpen) {
            this.close();
        } else {
            this.open();
        }
    },

    async fetch() {
        try {
            const res = await fetch('/cart/mini', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            if (res.ok) {
                const data = await res.json();
                this.updateState(data);
            }
        } catch (e) {
            console.error('Failed to load cart', e);
        }
    },

    async add(productId, variantId = null, quantity = 1) {
        this.loading = true;
        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    product_id: productId,
                    product_variant_id: variantId,
                    quantity: quantity
                })
            });

            const data = await res.json();
            if (res.ok && data.success) {
                this.updateState(data.cart);
                this.open();
                Alpine.store('toast').show(data.message || 'Added to bag!');
            } else {
                Alpine.store('toast').show(data.message || 'Could not add to cart.', 'error');
            }
        } catch (e) {
            Alpine.store('toast').show('Network error while updating bag.', 'error');
        } finally {
            this.loading = false;
        }
    },

    async updateQuantity(itemId, quantity) {
        if (quantity < 1) {
            return this.remove(itemId);
        }

        this.loading = true;
        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch(`/cart/update/${itemId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ quantity: quantity })
            });

            const data = await res.json();
            if (res.ok && data.success) {
                this.updateState(data.cart);
            } else {
                Alpine.store('toast').show(data.message || 'Update failed.', 'error');
            }
        } catch (e) {
            Alpine.store('toast').show('Error updating quantity.', 'error');
        } finally {
            this.loading = false;
        }
    },

    async remove(itemId) {
        this.loading = true;
        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch(`/cart/remove/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await res.json();
            if (res.ok && data.success) {
                this.updateState(data.cart);
                Alpine.store('toast').show('Item removed from bag.', 'info');
            }
        } catch (e) {
            Alpine.store('toast').show('Error removing item.', 'error');
        } finally {
            this.loading = false;
        }
    },

    updateState(cartData) {
        if (!cartData) return;
        this.items = cartData.items || [];
        this.subtotal = cartData.subtotal || 0;
        this.totalItems = cartData.total_items || 0;
        this.freeShippingThreshold = cartData.free_shipping_threshold || 500000;
        this.freeShippingUnlocked = cartData.free_shipping_unlocked || false;
        this.freeShippingRemaining = cartData.free_shipping_remaining || 0;
        this.freeShippingProgress = cartData.free_shipping_progress || 0;
    }
});

// Global Wishlist Store
Alpine.store('wishlist', {
    items: [],

    async toggle(productId, el = null) {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        try {
            const res = await fetch('/wishlist/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ product_id: productId })
            });

            const data = await res.json();
            if (res.ok && data.success) {
                if (data.is_in_wishlist) {
                    Alpine.store('toast').show('Added to Wishlist!');
                    if (el) el.classList.add('text-error');
                } else {
                    Alpine.store('toast').show('Removed from Wishlist.', 'info');
                    if (el) el.classList.remove('text-error');
                }
            }
        } catch (e) {
            Alpine.store('toast').show('Could not update wishlist.', 'error');
        }
    }
});

Alpine.start();
