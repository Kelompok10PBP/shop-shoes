<?php

use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Account\AddressController;
use App\Http\Controllers\Account\OrderController as AccountOrderController;
use App\Http\Controllers\Account\ProfileController;
use App\Http\Controllers\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CollectionController as AdminCollectionController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MessageController as AdminMessageController;
use App\Http\Controllers\Admin\NewsletterController as AdminNewsletterController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\PromotionController as AdminPromotionController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Storefront Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home/trending', [HomeController::class, 'trending'])->name('home.trending');

Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/best-sellers', [ShopController::class, 'bestSellers'])->name('best-sellers');
Route::get('/new-arrivals', [ShopController::class, 'newArrivals'])->name('new-arrivals');

Route::get('/collections', [CollectionController::class, 'index'])->name('collections.index');
Route::get('/collections/{slug}', [CollectionController::class, 'show'])->name('collections.show');

Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::post('/products/{id}/reviews', [ReviewController::class, 'store'])->middleware('auth')->name('reviews.store');

Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/search/suggest', [SearchController::class, 'suggest'])->name('search.suggest');

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::get('/mini', [CartController::class, 'mini'])->name('mini');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::post('/buy-now', [CartController::class, 'buyNow'])->name('buy-now');
    Route::patch('/update/{id}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
    Route::post('/coupon/apply', [CartController::class, 'applyCoupon'])->name('coupon.apply');
    Route::delete('/coupon/remove', [CartController::class, 'removeCoupon'])->name('coupon.remove');
});

Route::prefix('wishlist')->name('wishlist.')->group(function () {
    Route::get('/', [WishlistController::class, 'index'])->name('index');
    Route::post('/toggle', [WishlistController::class, 'toggle'])->name('toggle');
    Route::post('/move-to-cart/{id}', [WishlistController::class, 'moveToCart'])->name('move-to-cart');
});

Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/', [CheckoutController::class, 'store'])->name('store');
    Route::post('/quick', [CheckoutController::class, 'quickCheckout'])->name('quick');
    Route::get('/success/{orderNumber}', [CheckoutController::class, 'success'])->name('success');
    Route::get('/receipt/{orderNumber}', [CheckoutController::class, 'receipt'])->name('receipt');
});

Route::get('/order-tracking', [OrderTrackingController::class, 'index'])->name('order-tracking.index');
Route::post('/order-tracking/lookup', [OrderTrackingController::class, 'lookup'])->name('order-tracking.lookup');

Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/our-story', [PageController::class, 'about'])->name('pages.about');
Route::get('/size-guide', [PageController::class, 'sizeGuide'])->name('pages.size-guide');
Route::get('/shipping-returns', [PageController::class, 'shipping'])->name('pages.shipping');
Route::get('/faq', [PageController::class, 'faq'])->name('pages.faq');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Customer Account Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('account')->name('account.')->group(function () {
    Route::get('/', [AccountController::class, 'dashboard'])->name('dashboard');

    Route::get('/orders', [AccountOrderController::class, 'index'])->name('orders');
    Route::get('/orders/{orderNumber}', [AccountOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{orderNumber}/cancel', [AccountOrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{orderNumber}/reorder', [AccountOrderController::class, 'reorder'])->name('orders.reorder');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/addresses', [AddressController::class, 'index'])->name('addresses.index');
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::put('/addresses/{id}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{id}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::post('/addresses/{id}/default', [AddressController::class, 'setDefault'])->name('addresses.default');
});

/*
|--------------------------------------------------------------------------
| Admin Panel Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::post('/products/bulk-delete', [AdminProductController::class, 'bulkDestroy'])->name('products.bulk-delete');
    Route::patch('/products/{id}/toggle-status', [AdminProductController::class, 'toggleStatus'])->name('products.toggle-status');
    Route::resource('products', AdminProductController::class);

    // Taxonomy & Collections
    Route::resource('categories', AdminCategoryController::class)->except(['create', 'show', 'edit']);
    Route::resource('brands', AdminBrandController::class)->except(['create', 'show', 'edit']);
    Route::resource('collections', AdminCollectionController::class)->except(['create', 'show', 'edit']);

    // Banners & Promos
    Route::get('/banner', [AdminBannerController::class, 'edit'])->name('banner.edit');
    Route::put('/banner', [AdminBannerController::class, 'update'])->name('banner.update');

    Route::get('/promotion', [AdminPromotionController::class, 'edit'])->name('promotion.edit');
    Route::put('/promotion', [AdminPromotionController::class, 'update'])->name('promotion.update');

    // Marketing & Coupons
    Route::resource('coupons', AdminCouponController::class)->except(['create', 'show', 'edit']);

    // Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::get('/orders/{id}/invoice', [AdminOrderController::class, 'invoice'])->name('orders.invoice');

    // Customers
    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{id}', [AdminCustomerController::class, 'show'])->name('customers.show');

    // Reviews moderation
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::patch('/reviews/{id}/toggle-approve', [AdminReviewController::class, 'toggleApprove'])->name('reviews.toggle-approve');
    Route::delete('/reviews/{id}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    // Newsletter Subscribers
    Route::get('/newsletter', [AdminNewsletterController::class, 'index'])->name('newsletter.index');
    Route::get('/newsletter/export', [AdminNewsletterController::class, 'exportCsv'])->name('newsletter.export');

    // Contact Messages
    Route::get('/messages', [AdminMessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{id}', [AdminMessageController::class, 'show'])->name('messages.show');
    Route::patch('/messages/{id}/toggle-read', [AdminMessageController::class, 'toggleRead'])->name('messages.toggle-read');
    Route::delete('/messages/{id}', [AdminMessageController::class, 'destroy'])->name('messages.destroy');

    // Settings
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
});
