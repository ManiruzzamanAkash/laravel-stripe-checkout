<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ProductController::class, 'index'])->name('index');
Route::get('/carts', [CartController::class, 'index'])->name('carts');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('payment-success', [PaymentController::class, 'success']);
Route::get('payment-cancel', [PaymentController::class, 'cancel']);

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout-success', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('checkout/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');

require __DIR__.'/auth.php';
