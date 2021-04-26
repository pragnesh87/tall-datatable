<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DatatableExport implements FromCollection, WithHeadings
{
    use Exportable;

    public $collection;
    public $heading = [];

    public function __construct($collection, $heading = [])
    {
        $this->collection = $collection;
        $this->heading = $heading;
    }

    public function collection()
    {
        return $this->collection;
    }

    public function headings(): array
    {
        return $this->heading;
    }
}
