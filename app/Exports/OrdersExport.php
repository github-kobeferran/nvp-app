<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Models\Order;
use Carbon\Carbon;

class OrdersExport implements WithMultipleSheets
{
    use Exportable;
 /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [
                    new OrdersReportSheet('day', Carbon::now()->isoFormat('DD') . ' of ' .  Carbon::now()->isoFormat('MMMM')),
                    new OrdersReportSheet('month', 'All of ' .  Carbon::now()->isoFormat('MMMM')),
                    new OrdersReportSheet('year', 'All of ' .  Carbon::now()->isoFormat('OY')),
                ];                        
                
        return $sheets;
    }
}
