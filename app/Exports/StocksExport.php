<?php

namespace App\Exports;

// use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Stock;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StocksExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $getSummary = DB::select("CALL getData(1)");
        return view('partials.stocks', [
            'stocks' => $getSummary
        ]);
    }
}
