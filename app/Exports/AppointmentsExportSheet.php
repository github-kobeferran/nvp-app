<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;
use App\Models\Appointment;
use Carbon\Carbon;

class AppointmentsExportSheet implements FromCollection, WithMapping, WithHeadings, WithStyles, WithStrictNullComparison, WithTitle 
{
    protected $getByType = '';
    protected $title = '';

    public function __construct($getByType, $title)
    {
        $this->getByType = $getByType;        
        $this->title = $title;        
    }

    public function styles(Worksheet $sheet)
    {

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],           
                  
        ];
    }

     /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        switch($this->getByType){
            case 'day':               
                return Appointment::where('date', Carbon::now())->get();
            break;
            case 'month':
                return Appointment::whereBetween('date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->get();
            break;
            case 'year':
                return Appointment::whereBetween('date', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])->get();
            break;                
        }
    }

    public function headings(): array
    {
        return [
            'TRANSACTION ID',
            'CLIENT EMAIL AND NAME',
            'PET',
            'SERVICE',
            'TOTAL FEE',
            'DATE',        
            'STATUS',           
        ];
    }

    public function map($appointments): array
    {    

        return [     
            $appointments->transaction->trans_id,
            $appointments->transaction->client->user->email . ' - ' . strtoupper($appointments->transaction->client->user->first_name) . ' ' . strtoupper($appointments->transaction->client->user->last_name),            
            strtoupper($appointments->pet->name),
            $appointments->services()->implode('desc', ', '),
            $appointments->services()->sum('price'),            
            Carbon::parse($appointments->date)->isoFormat('MMM-DD-OY'),
            $appointments->status == 0 ? 'Pending' : $appointments->status == 1 ? 'Done' : 'Abandoned',
        ];
    }

    public function title(): string
    {
        return $this->title;
    }


}
