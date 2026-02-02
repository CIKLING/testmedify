<?php

namespace App\Exports;

use App\Models\MasterItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class MasterItemsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    protected $items;

    public function __construct()
    {
        $this->items = MasterItem::with('kategoris')->get();
    }

    public function collection()
    {
        return $this->items->map(function ($item, $index) {
            $kategoriNames = $item->kategoris->pluck('nama')->toArray();
            $kategoriString = !empty($kategoriNames) ? implode(', ', $kategoriNames) : '-';

            $hargaJual = $item->harga_beli + ($item->harga_beli * $item->laba / 100);

            return [
                'No' => $index + 1,
                'Nama Kategori' => $kategoriString,
                'Nama Items' => $item->nama,
                'Nama Supplier' => $item->supplier,
                'Harga' => $item->harga_beli,
                'Laba (%)' => $item->laba,
                'Harga Jual' => $hargaJual,
                'Jenis' => $item->jenis,
                'Kode Item' => $item->kode,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Kategori (terpisah koma)',
            'Nama Items',
            'Nama Supplier',
            'Harga',
            'Laba (%)',
            'Harga Jual',
            'Jenis',
            'Kode Item'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Apply styles
                $cellRange = 'A1:I1'; // Header
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);

                // Set background color for header
                $event->sheet->getDelegate()->getStyle($cellRange)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('2C3E50');

                // Set font color for header
                $event->sheet->getDelegate()->getStyle($cellRange)
                    ->getFont()
                    ->getColor()
                    ->setARGB('FFFFFF');

                // Center align header
                $event->sheet->getDelegate()->getStyle($cellRange)
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // Format number columns
                $lastRow = $this->items->count() + 1;

                // Format Harga column (E)
                $hargaRange = 'E2:E' . $lastRow;
                $event->sheet->getDelegate()->getStyle($hargaRange)
                    ->getNumberFormat()
                    ->setFormatCode('#,##0');

                // Format Harga Jual column (G)
                $hargaJualRange = 'G2:G' . $lastRow;
                $event->sheet->getDelegate()->getStyle($hargaJualRange)
                    ->getNumberFormat()
                    ->setFormatCode('#,##0');

                // Format Laba column (F) as percentage
                $labaRange = 'F2:F' . $lastRow;
                $event->sheet->getDelegate()->getStyle($labaRange)
                    ->getNumberFormat()
                    ->setFormatCode('0%');

                // Add borders
                $allCellsRange = 'A1:I' . $lastRow;
                $event->sheet->getDelegate()->getStyle($allCellsRange)
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                // Auto size columns
                foreach (range('A', 'I') as $column) {
                    $event->sheet->getDelegate()->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}