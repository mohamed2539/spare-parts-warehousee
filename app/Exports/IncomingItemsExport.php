<?php

namespace App\Exports;

use App\Models\IncomingItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class IncomingItemsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return IncomingItem::all();
    }

    public function headings(): array
    {
        return ['المسلسل','القسم','التاريخ','الكود','الصنف','الوحدة','الكمية','المورد'];
    }
}
