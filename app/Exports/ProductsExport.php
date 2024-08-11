<?php

namespace App\Exports;

use App\Http\Resources\ProductResourceCollection;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProductsExport implements FromCollection
{
    protected $jsonData;

    public function __construct($jsonData)
    {
        $this->jsonData = $jsonData;
    }

    public function collection()
    {
        return collect(json_decode($this->jsonData, true));
    }


    public function headings(): array
    {
//        dd(array_keys(json_decode($this->jsonData, true)[0]));
        return array_keys(json_decode($this->jsonData, true)[0]);
    }
}
