<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Models\Appointment;
use Carbon\Carbon;

class AppointmentsExport implements WithMultipleSheets
{
    use Exportable;
 /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [
                    new AppointmentsExportSheet('day', Carbon::now()->isoFormat('DD') . ' of ' .  Carbon::now()->isoFormat('MMMM')),
                    new AppointmentsExportSheet('month', 'All of ' .  Carbon::now()->isoFormat('MMMM')),
                    new AppointmentsExportSheet('year', 'All of ' .  Carbon::now()->isoFormat('OY')),
                ];                        
                
        return $sheets;
    }
}
