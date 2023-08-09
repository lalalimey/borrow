<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;


class ExcelImport implements ToCollection, WithHeadingRow
{
    protected $data = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $this->data[] = $row;
        }
    }

    public function getData()
    {
        return $this->data;
    }
}
