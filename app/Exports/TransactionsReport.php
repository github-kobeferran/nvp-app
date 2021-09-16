<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Models\Transaction;
use Carbon\Carbon;

class TransactionsReport implements WithMultipleSheets
{
    use Exportable;
 /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [
                    new TransactionsReportPerSheet('day', Carbon::now()->isoFormat('DD') . ' of ' .  Carbon::now()->isoFormat('MMMM')),
                    new TransactionsReportPerSheet('month', 'All of ' .  Carbon::now()->isoFormat('MMMM')),
                    new TransactionsReportPerSheet('year', 'All of ' .  Carbon::now()->isoFormat('OY')),
                ];                        
                
        return $sheets;
    }
}
