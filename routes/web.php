<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ExportDetailsController;
use App\Http\Controllers\ExportUserController;
use App\Http\Controllers\ChartController;
use App\Models\OrderDetail;

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
    Route::any(
        '/dashboard',
        \App\Http\Livewire\WireDashboard::class,
        'render'
    )->name('dashboard');


    //MANAGE
    Route::get('manage/branches', \App\Http\Livewire\WireBranch::class, 'render')->name('branches');
    Route::get('manage/suppliers', \App\Http\Livewire\WireSupplier::class, 'render')->name('suppliers');
    Route::get('manage/items', \App\Http\Livewire\WireItem::class, 'render')->name('items');
    Route::get('manage/price', \App\Http\Livewire\WireItemPrice::class, 'render')->name('prices');
    Route::get('manage/users', \App\Http\Livewire\WireUser::class, 'render')->name('users');

    //STOCKS
    Route::get('stocks/current-stocks', \App\Http\Livewire\WireStock::class, 'render')->name('stocks');
    Route::get('stocks/track-usage', \App\Http\Livewire\WireChart::class, 'render')->name('charts');

    //ORDERS
    Route::get('orders/create-order', \App\Http\Livewire\WireOrder::class, 'render')->name('orders');
    Route::get('orders/order-history', \App\Http\Livewire\WireHistory::class, 'render')->name('history');

    //EXPORT TO PDF
    Route::get('stocks/generate-pdf/{stock}', [ExportController::class, 'generatePDF'])->name('generate-pdf');
    Route::get('user/generate-user/{user}', [ExportUserController::class, 'generateUser'])->name('generate-user');
    Route::get('history/generate-report/{mos}', function ($mos) {

        $explode = explode('-', $mos);
        $yr = $explode[0];
        $mons = $explode[1];
        // dd($mons);
        $findData = DB::select("CALL getMonthlyReport($yr,$mons)");

        $data = [
            'monthlyreport' => $findData
        ];

        $pdf = PDF::loadView('monthlyReport', $data);

        return $pdf->stream('monthly_report_'.today()->toDateString().'.pdf');
    })->name('generateMonthly');




    //EXPORT TO EXCEL
    Route::get('stocks/export/', [ExportController::class, 'export'])->name('generate-export');
    Route::get('orders/generate-PO/{detail}', [ExportDetailsController::class, 'generatePO'])->name('generatePO');



});
