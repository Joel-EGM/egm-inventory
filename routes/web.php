<?php

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

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('manage/branches', \App\Http\Livewire\WireBranch::class, 'render')->name('branches');
    Route::get('manage/items', \App\Http\Livewire\WireItem::class, 'render')->name('items');
    Route::get('manage/stocks', \App\Http\Livewire\WireStock::class, 'render')->name('stocks');
    Route::get('manage/suppliers', \App\Http\Livewire\WireSupplier::class, 'render')->name('suppliers');
    Route::get('/manage/users', \App\Http\Livewire\WireSupplier::class, 'render')->name('users');
    Route::get('/order', \App\Http\Livewire\WireOrder::class, 'render')->name('orders');
});
