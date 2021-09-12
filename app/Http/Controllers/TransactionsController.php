<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrdersExport;
use App\Exports\AppointmentsExport;
use Carbon\Carbon;

class TransactionsController extends Controller
{
    public function ordersExport() 
    {
        return Excel::download(new OrdersExport, Carbon::now()->isoFormat('OY-MMM-DD hh-mm-a') . '-nvp-clinic-orders.xlsx');
    }

    public function appointmentsExport() 
    {
        return Excel::download(new AppointmentsExport, Carbon::now()->isoFormat('OY-MMM-DD hh-mm-a') . '-nvp-clinic-appointments.xlsx');
    }

}
