<?php

namespace App\Jobs;

use App\Models\InboundBatch;
use App\Models\InboundRow;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Throwable;

class ProcessInboundBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $batchId;

    public function __construct(int $batchId)
    {
        $this->batchId = $batchId;
    }

    public function handle(): void
    {
        $batch = InboundBatch::findOrFail($this->batchId);

        $fullPath = storage_path('app/'.$batch->file_path);

        $rows = Excel::toArray([], $fullPath)[0] ?? []; // الشيت الأول
        if (empty($rows)) {
            $batch->update([
                'status' => 'failed',
                'finished_at' => now(),
            ]);
            return;
        }

        // توقع أن أول صف هو العناوين
        $headers = array_map(fn($h) => $this->normalizeHeader($h), $rows[0]);
        unset($rows[0]);

        $map = $this->buildMap($headers);

        $total = count($rows);
        $batch->update(['total_rows' => $total]);

        $failedRows = [];
        $processed = 0;
        foreach ($rows as $i => $rawRow) {
            try {
                $data = $this->extractRow($rawRow, $map);

                // Validation بسيط
                $errors = [];
                if (!$data['code'] && !$data['item_name']) {
                    $errors[] = 'يجب وجود الكود أو اسم الصنف';
                }
                if ($data['quantity'] === null) {
                    $errors[] = 'الكمية مفقودة';
                }

                if ($errors) {
                    $failedRows[] = $this->asErrorRow($rawRow, $errors);
                } else {
                    InboundRow::create([
                        'inbound_batch_id' => $batch->id,
                        'department'       => $data['department'],
                        'doc_date'         => $data['doc_date'],
                        'code'             => $data['code'],
                        'item_name'        => $data['item_name'],
                        'unit'             => $data['unit'],
                        'quantity'         => $data['quantity'],
                        'supplier'         => $data['supplier'],
                        'errors'           => null,
                    ]);
                }

                $processed++;
                // تحديث تقدّم كل 50 صف (نقلّل ضغط DB)
                if ($processed % 50 === 0) {
                    $batch->update([
                        'processed_rows' => $processed,
                        'failed_rows' => count($failedRows),
                    ]);
                }
            } catch (Throwable $e) {
                $failedRows[] = $this->asErrorRow($rawRow, ['Exception: '.$e->getMessage()]);
            }
        }

        // حفظ آخر تقدّم
        $batch->update([
            'processed_rows' => $processed,
            'failed_rows' => count($failedRows),
        ]);

        // لو فيه أخطاء، إحفظ CSV
        if (!empty($failedRows)) {
            $csvPath = 'imports/errors/batch_'.$batch->id.'_errors.csv';
            $this->writeCsv($csvPath, $failedRows);
            $batch->error_file_path = $csvPath;
        }

        $batch->status = 'done';
        $batch->finished_at = now();
        $batch->save();
    }

    private function normalizeHeader($h): string
    {
        $h = trim((string)$h);
        $h = mb_strtolower($h);
        // إزالة مسافات زائدة
        $h = preg_replace('/\s+/', ' ', $h);
        return $h;
    }

    private function buildMap(array $headers): array
    {
        // مفاتيح عربية متوقعة
        $aliases = [
            'department' => ['القسم','القسم ','قسم'],
            'doc_date'   => ['التاريخ','تاريخ'],
            'code'       => ['الكود','رقم','رقم الصنف','كود'],
            'item_name'  => ['الصنف','الاسم','اسم الصنف'],
            'unit'       => ['الوحدة','وحدة','وحده'],
            'quantity'   => ['الكمية','كمية','qty','quantity'],
            'supplier'   => ['المورد','مورد','supplier'],
        ];

        $map = [];
        foreach ($aliases as $field => $keys) {
            $map[$field] = null;
            foreach ($headers as $idx => $h) {
                if (in_array($h, array_map(fn($k)=>mb_strtolower($k), $keys), true)) {
                    $map[$field] = $idx;
                    break;
                }
            }
        }
        return $map;
    }

    private function extractRow(array $raw, array $map): array
    {
        $get = function (?int $i) use ($raw) {
            return $i !== null && array_key_exists($i, $raw) ? trim((string)$raw[$i]) : null;
        };

        $department = $get($map['department']);
        $dateCell   = $get($map['doc_date']);
        $code       = $get($map['code']);
        $itemName   = $get($map['item_name']);
        $unit       = $get($map['unit']);
        $qtyCell    = $get($map['quantity']);
        $supplier   = $get($map['supplier']);

        // تحويل التاريخ (يدعم تواريخ إكسل الرقمية)
        $docDate = null;
        if ($dateCell !== null && $dateCell !== '') {
            if (is_numeric($dateCell)) {
                try {
                    $docDate = ExcelDate::excelToDateTimeObject((float)$dateCell)->format('Y-m-d');
                } catch (\Throwable $e) {
                    $docDate = null;
                }
            } else {
                // نحاول نفهم فورمات شائع
                $ts = strtotime($dateCell);
                $docDate = $ts ? date('Y-m-d', $ts) : null;
            }
        }

        // الكمية كرقم
        $quantity = null;
        if ($qtyCell !== null && $qtyCell !== '') {
            $quantity = (float)str_replace([',',' '], ['.',''], $qtyCell);
        }

        return [
            'department' => $department ?: null,
            'doc_date'   => $docDate,
            'code'       => $code ?: null,
            'item_name'  => $itemName ?: null,
            'unit'       => $unit ?: null,
            'quantity'   => $quantity,
            'supplier'   => $supplier ?: null,
        ];
    }

    private function asErrorRow(array $rawRow, array $errors): array
    {
        $rawRow = array_map(fn($v) => is_scalar($v) ? (string)$v : json_encode($v), $rawRow);
        $rawRow[] = implode(' | ', $errors); // عمود أخطاء آخر
        return $rawRow;
    }

    private function writeCsv(string $path, array $rows): void
    {
        Storage::makeDirectory(dirname($path));
        $fp = fopen(storage_path('app/'.$path), 'w');
        foreach ($rows as $r) {
            fputcsv($fp, $r);
        }
        fclose($fp);
    }
}
