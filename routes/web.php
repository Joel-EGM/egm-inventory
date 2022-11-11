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
    Route::get('/branch', \App\Http\Livewire\WireBranch::class, 'render')->name('branch');
    Route::get('manage/items', \App\Http\Livewire\WireItem::class, 'render')->name('items');
    Route::get('/order', \App\Http\Livewire\WireOrder::class, 'render')->name('order');
    Route::get('/stock', \App\Http\Livewire\WireStock::class, 'render')->name('stock');
    Route::get('/supplier', \App\Http\Livewire\WireSupplier::class, 'render')->name('supplier');
});
