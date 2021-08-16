<?php

namespace App\Exports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class ClientsExport implements FromCollection, WithMapping, WithHeadings, WithStyles, WithStrictNullComparison
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
        return Client::all();
    }

    public function map($clients): array
    {

        return [     
            ucfirst($clients->user->name),
            $clients->user->email,
            $clients->sex == 0 ? 'Male' : 'Female',
            \Carbon\Carbon::parse($clients->dob)->isoFormat('MMMM DD, OY'),
            $clients->address,
            $clients->contact,
            is_null($clients->user->email_verified_at) ? 'No' : 'Yes',
        ];
    }

    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Sex',
            'Date of Birth',            
            'Address',            
            'Contact',            
            'Email Verified',            
        ];
    }
}
