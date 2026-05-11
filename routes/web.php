<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\PromotionController as AdminPromotionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AIChatController;
use App\Http\Controllers\Auth\GoogleSocialiteController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Client\OrderController as ClientOrderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Public product browsing
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

// Google OAuth routes
Route::middleware('guest')->group(function () {
    Route::get('/auth/google/redirect', [GoogleSocialiteController::class, 'redirectToGoogle'])->name('google.redirect');
    Route::get('/auth/google/callback', [GoogleSocialiteController::class, 'handleGoogleCallback'])->name('google.callback');
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    // Email verification routes
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('home')->with('success', 'Email vérifié avec succès !');
    })->middleware('signed')->name('verification.verify');
    Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('success', 'Lien de vérification renvoyé !');
    })->middleware('throttle:6,1')->name('verification.send');

    // PayPal routes
    Route::get('/paypal/success', [PayPalController::class, 'success'])->name('paypal.success');
    Route::get('/paypal/cancel', [PayPalController::class, 'cancel'])->name('paypal.cancel');
    Route::get('/paypal/process/{order}', [PayPalController::class, 'processPayment'])->name('paypal.process');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Chat history routes
    Route::get('/chat/history', [App\Http\Controllers\ChatHistoryController::class, 'index'])->name('chat.history');
    Route::post('/chat/message', [App\Http\Controllers\ChatHistoryController::class, 'store'])->name('chat.store');
    Route::delete('/chat/clear', [App\Http\Controllers\ChatHistoryController::class, 'clear'])->name('chat.clear');

    // AI Chat route
    Route::post('/ai/chat', [AIChatController::class, 'chat'])->name('ai.chat');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Client routes (cart, orders, wishlist, reviews, profile)
    Route::prefix('client')->name('client.')->group(function () {
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart/promo', [CartController::class, 'applyPromo'])->name('cart.promo');
        Route::delete('/cart/promo', [CartController::class, 'removePromo'])->name('cart.promo.remove');
        Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
        Route::patch('/cart/{item}', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/cart/{item}', [CartController::class, 'remove'])->name('cart.remove');
        Route::get('/cart/checkout', [CartController::class, 'checkoutPage'])->middleware('verified')->name('cart.checkout');
        Route::post('/cart/checkout', [CartController::class, 'checkout'])->middleware('verified')->name('cart.checkout.process');
        Route::get('/orders', [ClientOrderController::class, 'index'])->middleware('verified')->name('orders.index');
        Route::get('/orders/{order}', [ClientOrderController::class, 'show'])->middleware('verified')->name('orders.show');
        Route::get('/orders/{order}/receipt', [ClientOrderController::class, 'receipt'])->middleware('verified')->name('orders.receipt');
        Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
        Route::post('/wishlist/{product}', [WishlistController::class, 'add'])->name('wishlist.add');
        Route::delete('/wishlist/{product}', [WishlistController::class, 'remove'])->name('wishlist.remove');
        Route::post('/reviews/{product}', [ReviewController::class, 'store'])->name('reviews.store');
        Route::get('/profile', [App\Http\Controllers\Client\ProfileController::class, 'index'])->name('profile');
        Route::get('/profile/orders', [App\Http\Controllers\Client\ProfileController::class, 'orders'])->name('profile.orders');
        Route::get('/profile/reviews', [App\Http\Controllers\Client\ProfileController::class, 'reviews'])->name('profile.reviews');
        Route::get('/profile/wishlist', [App\Http\Controllers\Client\ProfileController::class, 'wishlist'])->name('profile.wishlist');
        Route::get('/profile/orders/{order}', [App\Http\Controllers\Client\ProfileController::class, 'showOrder'])->name('profile.order-details');
    });

    // Admin routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
        Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
        Route::delete('/products/{product}/images/{image}', [AdminProductController::class, 'destroyImage'])->name('products.images.destroy');
        Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [AdminCategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [AdminCategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::get('/activity-logs', [App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::get('/activity-logs/export', [App\Http\Controllers\Admin\ActivityLogController::class, 'export'])->name('activity-logs.export');
        // Promo codes
        Route::get('/promotions', [AdminPromotionController::class, 'index'])->name('promotions.index');
        Route::post('/promotions', [AdminPromotionController::class, 'store'])->name('promotions.store');
        Route::patch('/promotions/{promotion}/toggle', [AdminPromotionController::class, 'toggle'])->name('promotions.toggle');
        Route::delete('/promotions/{promotion}', [AdminPromotionController::class, 'destroy'])->name('promotions.destroy');
        // AI product description
        Route::post('/ai/describe', [App\Http\Controllers\Admin\AiProductController::class, 'describe'])->name('ai.describe');
    });
});