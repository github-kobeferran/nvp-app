<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;


class ItemsExport implements FromCollection, WithMapping, WithHeadings, WithStyles, WithStrictNullComparison
{
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

        return Item::all();                              
    }

    public function map($items): array
    {

        return [     
            strtoupper($items->category->desc),
            $items->desc,
            $items->quantity,
            $items->deal_price,
        ];
    }

    public function headings(): array
    {
        return [
            'Category',
            'Description',
            'Quantity',
            'Dealers Price',            
        ];
    }
}
