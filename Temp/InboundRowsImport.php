<?php

namespace App\Imports;

use App\Models\InboundRow;
use App\Models\InboundBatch;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InboundRowsImport implements ToModel, WithHeadingRow
{
    protected $batch;

    public function __construct(InboundBatch $batch)
    {
        $this->batch = $batch;
    }

    public function model(array $row)
    {
        \Log::info('Row keys: ', array_keys($row)); // هيسجل أسماء الأعمدة اللي فعلاً اتقرت
        return new InboundRow([
            'batch_id'  => $this->batch->id,
            'section'   => $row['القسم'] ?? null,
            'date'      => $row['التاريخ'] ?? null,
            'code'      => $row['الكود'] ?? null,
            'item_name' => $row['الصنف'] ?? null,
            'unit'      => $row['الوحده'] ?? null,
            'quantity'  => $row['الكميه'] ?? null,
            'supplier'  => $row['المورد'] ?? null,
        ]);
    }
}
