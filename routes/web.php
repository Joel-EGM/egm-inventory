<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;

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

    //Manage
    Route::get('manage/branches', \App\Http\Livewire\WireBranch::class, 'render')->name('branches');
    Route::get('manage/suppliers', \App\Http\Livewire\WireSupplier::class, 'render')->name('suppliers');
    Route::get('manage/items', \App\Http\Livewire\WireItem::class, 'render')->name('items');
    Route::get('manage/price', \App\Http\Livewire\WireItemPrice::class, 'render')->name('prices');
    Route::get('manage/users', \App\Http\Livewire\WireUser::class, 'render')->name('users');

    //Stocks
    Route::get('stocks/current_stocks', \App\Http\Livewire\WireStock::class, 'render')->name('stocks');

    //Orders
    Route::get('orders/create_order', \App\Http\Livewire\WireOrder::class, 'render')->name('orders');

    //EXPORT TO PDF
    Route::get('generate-pdf/1', [PDFController::class, 'generatePDF'])->name('generate-pdf1');
    Route::get('generate-pdf/2', [PDFController::class, 'generatePDF'])->name('generate-pdf2');

    // Route::get('generate/{id}', 'PDFController@generatePDF');
});
