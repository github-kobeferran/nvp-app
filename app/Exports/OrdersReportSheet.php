<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;
use App\Models\Order;
use Carbon\Carbon;

class OrdersReportSheet implements FromCollection, WithMapping, WithHeadings, WithStyles, WithStrictNullComparison, WithTitle 
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
                return Order::whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])->get();
            break;
            case 'month':
                return Order::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->get();
            break;
            case 'year':
                return Order::whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])->get();
            break;                
        }
    }

    public function headings(): array
    {
        return [
            'DATE',
            'TRANSACTION ID',
            'CLIENT EMAIL AND NAME',
            'ITEM',
            'QUANTITY',
            'TOTAL AMOUNT',
            'STATUS',            
            'DONE BY',            
        ];
    }

    public function map($orders): array
    {

        return [     
            \Carbon\Carbon::parse($orders->created_at)->isoFormat('MMM DD, OY'),
            $orders->transaction->trans_id,
            $orders->transaction->client->user->email . ' - ' . strtoupper($orders->transaction->client->user->first_name) . ' ' . strtoupper($orders->transaction->client->user->last_name),
            strtoupper($orders->item->desc),
            $orders->quantity,
            $orders->item->reg_price * $orders->quantity,
            $orders->status > 0 ? 'Done' : 'Pending', 
            !is_null($orders->done_by) ? \App\Models\User::find($orders->done_by)->first_name . ' ' . \App\Models\User::find($orders->done_by)->last_name : '', 
        ];
    }

    public function title(): string
    {
        return $this->title;
    }


}
