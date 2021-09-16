<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;
use App\Models\Transaction;
use Carbon\Carbon;

class TransactionsReportPerSheet implements FromCollection, WithMapping, WithHeadings, WithStyles, WithStrictNullComparison, WithTitle 
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
                return Transaction::whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])->get();
            break;
            case 'month':
                return Transaction::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->get();
            break;
            case 'year':
                return Transaction::whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])->get();
            break;                
        }
    }

    public function headings(): array
    {
        return [
            'DATE',
            'TRANSACTION ID',
            'CLIENT EMAIL AND NAME',
            'TYPE',
            'PAYMENT',
            'APPROVED BY',
        ];
    }

    public function map($transactions): array
    {

        return [     
            \Carbon\Carbon::parse($transactions->created_at)->isoFormat('MMM DD, OY'),
            $transactions->trans_id,
            $transactions->client->user->email . ' - ' . strtoupper($transactions->client->user->first_name) . ' ' . strtoupper($transactions->client->user->last_name),
            $transactions->type,
            $transactions->has_payment == 1 ? $transactions->payment->amount : '',
            !is_null($transactions->approved_by) ? \App\Models\User::find($transactions->approved_by)->first_name . ' ' . \App\Models\User::find($transactions->approved_by)->last_name : '', 
        ];
    }

    public function title(): string
    {
        return $this->title;
    }


}
