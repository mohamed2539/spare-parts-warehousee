<?php

namespace App\Imports;

use App\Models\IncomingItem;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Cell\Cell;

class IncomingImport implements ToModel, WithHeadingRow
{



    public function model(array $row)
{
    // عمود الصنف
    $item = $row['alsnf'] ?? null;

    // تحويل التاريخ
    $date = null;
    if (!empty($row['altarykh'])) {
        try {
            // إذا الرقم عشري من Excel
            if (is_numeric($row['altarykh'])) {
                $date = \Carbon\Carbon::instance(
                    \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['altarykh'])
                )->format('Y-m-d');
            } else {
                $date = \Carbon\Carbon::parse($row['altarykh'])->format('Y-m-d');
            }
        } catch (\Exception $e) {
            $date = null;
        }
    }

    // إذا كان يحتوي على صيغة Excel
    if ($item instanceof \PhpOffice\PhpSpreadsheet\Cell\Cell) {
        $item = $item->getCalculatedValue();
    }

    return new IncomingItem([
        'department' => $row['alksm'] ?? null,
        'date'       => $date,
        'code'       => $row['alkod'] ?? null,
        'item'       => $item,
        'unit'       => $row['alohdh'] ?? null,
        'quantity'   => is_numeric($row['alkmyh'] ?? null) ? $row['alkmyh'] : 0,
        'supplier'   => $row['almord'] ?? null,
    ]);
}
    // public function model(array $row)
    // {
    //     $item = $row['alsnf'] ?? null;
    //     $date = \Carbon\Carbon::parse($row['altarykh'])->format('Y-m-d');
    //     // إذا كان يحتوي على صيغة Excel
    //     if ($item instanceof \PhpOffice\PhpSpreadsheet\Cell\Cell) {
    //         $item = $item->getCalculatedValue();
    //     }
    
    //     return new IncomingItem([
    //         'department' => $row['alksm'] ?? null,
    //         'date'       => $date,
    //         'code'       => $row['alkod'] ?? null,
    //         'item'       => $item,
    //         'unit'       => $row['alohdh'] ?? null,
    //         'quantity'   => is_numeric($row['alkmyh'] ?? null) ? $row['alkmyh'] : 0,
    //         'supplier'   => $row['almord'] ?? null,
    //     ]);
    // }



}
