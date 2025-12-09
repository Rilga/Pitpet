<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\AdminController;

use App\Http\Controllers\Order\OrderController;

use Illuminate\Support\Facades\Artisan;

Route::get('/cache-clear-temp', function () {
    try {
        Artisan::call('optimize:clear'); // Ini mencakup config, route, view, dll.
        Artisan::call('cache:clear');

        return 'All Laravel Caches Cleared Successfully! **HAPUS ROUTE INI SEKARANG.**';
    } catch (\Exception $e) {
        return 'Cache Clear Failed: ' . $e->getMessage();
    }
});

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/order/create', [OrderController::class, 'create'])->name('order.create');
Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');
Route::get('/geocode', [OrderController::class, 'geocode']);
// Route baru untuk API Slot
Route::get('/slots-status', [OrderController::class, 'getSlotsStatus'])->name('order.slots');


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// user routes
Route::middleware(['auth', 'userMiddleware'])->group(function (){

    Route::get('user/dashboard', [UserController::class, 'index'])->name('user.dashboard');
    Route::put('/order/{order}/status', [UserController::class, 'updateStatus'])->name('groomer.order.status');


});

// admin routes
Route::middleware(['auth', 'adminMiddleware'])->group(function (){

    Route::get('admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/order/{order}/edit', [AdminController::class, 'edit'])->name('admin.order.edit');
    Route::put('/order/{order}', [AdminController::class, 'update'])->name('admin.order.update');

    Route::get('/schedule', [AdminController::class, 'schedule'])->name('admin.schedule');

    Route::get('/maps', [AdminController::class, 'mapView'])->name('admin.maps');
});
