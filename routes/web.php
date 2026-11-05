<?php

use App\Livewire;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ---------- หน้าบ้าน (สาธารณะ) ----------
Route::get('/', Livewire\Home::class)->name('home');
Route::view('/story', 'pages.story')->name('story');                       // เรื่องเล่า 3 สายน้ำ 3 ศาสนา
Route::get('/places', Livewire\Places\Index::class)->name('places.index');  // แหล่งท่องเที่ยว + แผนที่ + ใกล้ฉัน
Route::get('/places/{place:slug}', Livewire\Places\Show::class)->name('places.show');
Route::get('/communities', Livewire\Communities\Index::class)->name('communities.index');
Route::get('/communities/{community:slug}', Livewire\Communities\Show::class)->name('communities.show');
Route::get('/shop', Livewire\Shop\Index::class)->name('shop.index');
Route::get('/shop/{product:slug}', Livewire\Shop\Show::class)->name('shop.show');
Route::get('/cart', Livewire\Cart\Page::class)->name('cart');

// ---------- สมาชิก (ใช้สำหรับสั่งซื้อสินค้า) ----------
Route::middleware('guest')->group(function () {
    Route::get('/login', Livewire\Auth\Login::class)->name('login');
    Route::get('/register', Livewire\Auth\Register::class)->name('register');
});

Route::middleware('auth')->group(function () {
    Route::get('/checkout', Livewire\Checkout::class)->name('checkout');
    Route::get('/account', Livewire\Account\Dashboard::class)->name('account');
    Route::get('/account/orders/{order:code}', Livewire\Account\OrderShow::class)->name('account.order');
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('home');
    })->name('logout');
});

// ---------- หลังบ้าน ----------
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', Livewire\Admin\Dashboard::class)->name('dashboard');
    Route::get('/orders', Livewire\Admin\Orders::class)->name('orders');
    Route::get('/products', Livewire\Admin\Products::class)->name('products');
    Route::get('/places', Livewire\Admin\Places::class)->name('places');
    Route::get('/communities', Livewire\Admin\Communities::class)->name('communities');
});
