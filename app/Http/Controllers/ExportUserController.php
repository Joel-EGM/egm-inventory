<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ExportUserController extends Controller
{
    public function __invoke(User $user)
    {

    }

    public function generateUser()
    {
        $findData = DB::select("CALL getUsers()");

        $data = [
            'users' => $findData
        ];
        $pdf = PDF::loadView('userpdf', $data);

        return $pdf->stream('user_account_'.today()->toDateString().'.pdf');
    }
}
