<?php

namespace App\Exports;

// use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Stock;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class StocksExport implements FromView
{
    public function view(): View
    {
        $getSummary = DB::select("CALL getData(1)");
        return view('partials.stocks', [
            'stocks' => $getSummary
        ]);
    }
}
