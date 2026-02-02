<?php

namespace App\Http\Controllers;

use App\Models\MasterItem;
use Illuminate\Http\Request;

class MasterItemExportController extends Controller
{
    /**
     * Export Master Items ke Excel (CSV format)
     */
    public function exportExcel()
    {
        $items = MasterItem::with('kategoris')->get();
        $filename = 'master_items_export_' . date('Ymd_His') . '.xls';

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($items) {
            // Buka output stream
            $file = fopen('php://output', 'w');

            // Tulis header dengan format Excel
            $header = [
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

            fputcsv($file, $header, "\t"); // Gunakan tab sebagai separator

            // Tulis data
            $no = 1;
            foreach ($items as $item) {
                // Format kategori
                $kategoriNames = $item->kategoris->pluck('nama')->toArray();
                $kategoriString = !empty($kategoriNames) ? implode(', ', $kategoriNames) : '-';

                // Hitung harga jual
                $hargaJual = $item->harga_beli + ($item->harga_beli * $item->laba / 100);

                $data = [
                    $no++,
                    $kategoriString,
                    $item->nama,
                    $item->supplier,
                    'Rp ' . number_format($item->harga_beli, 0, ',', '.'),
                    $item->laba . '%',
                    'Rp ' . number_format($hargaJual, 0, ',', '.'),
                    $item->jenis,
                    $item->kode
                ];

                fputcsv($file, $data, "\t");
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export dengan format HTML table (bisa dibuka Excel)
     */
    public function exportExcelHtml()
    {
        $items = MasterItem::with('kategoris')->get();
        $filename = 'master_items_export_' . date('Ymd_His') . '.xls';

        $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        $html .= '<head>';
        $html .= '<meta charset="UTF-8">';
        $html .= '<!--[if gte mso 9]>';
        $html .= '<xml>';
        $html .= '<x:ExcelWorkbook>';
        $html .= '<x:ExcelWorksheets>';
        $html .= '<x:ExcelWorksheet>';
        $html .= '<x:Name>Master Items</x:Name>';
        $html .= '<x:WorksheetOptions>';
        $html .= '<x:DisplayGridlines/>';
        $html .= '</x:WorksheetOptions>';
        $html .= '</x:ExcelWorksheet>';
        $html .= '</x:ExcelWorksheets>';
        $html .= '</x:ExcelWorkbook>';
        $html .= '</xml>';
        $html .= '<![endif]-->';
        $html .= '<style>';
        $html .= 'td { mso-number-format:\@; }'; // Format text
        $html .= '.number { mso-number-format:"#,##0"; }'; // Format number
        $html .= '.currency { mso-number-format:"\"Rp\" #,##0"; }'; // Format currency
        $html .= 'th { background-color: #2C3E50; color: white; font-weight: bold; text-align: center; padding: 8px; }';
        $html .= 'td { border: 1px solid #ddd; padding: 6px; }';
        $html .= '</style>';
        $html .= '</head>';
        $html .= '<body>';
        $html .= '<table>';

        // Header
        $html .= '<tr>';
        $html .= '<th>No</th>';
        $html .= '<th>Nama Kategori (terpisah koma)</th>';
        $html .= '<th>Nama Items</th>';
        $html .= '<th>Nama Supplier</th>';
        $html .= '<th>Harga</th>';
        $html .= '<th>Laba (%)</th>';
        $html .= '<th>Harga Jual</th>';
        $html .= '<th>Jenis</th>';
        $html .= '<th>Kode Item</th>';
        $html .= '</tr>';

        // Data
        $no = 1;
        foreach ($items as $item) {
            $kategoriNames = $item->kategoris->pluck('nama')->toArray();
            $kategoriString = !empty($kategoriNames) ? implode(', ', $kategoriNames) : '-';

            $hargaJual = $item->harga_beli + ($item->harga_beli * $item->laba / 100);

            $html .= '<tr>';
            $html .= '<td align="center">' . $no++ . '</td>';
            $html .= '<td>' . htmlspecialchars($kategoriString) . '</td>';
            $html .= '<td>' . htmlspecialchars($item->nama) . '</td>';
            $html .= '<td>' . htmlspecialchars($item->supplier) . '</td>';
            $html .= '<td class="currency" align="right">' . number_format($item->harga_beli, 0, ',', '.') . '</td>';
            $html .= '<td align="center">' . $item->laba . '%</td>';
            $html .= '<td class="currency" align="right">' . number_format($hargaJual, 0, ',', '.') . '</td>';
            $html .= '<td>' . htmlspecialchars($item->jenis) . '</td>';
            $html .= '<td>' . htmlspecialchars($item->kode) . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';
        $html .= '</body>';
        $html .= '</html>';

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Export dengan format yang lebih baik (rekomendasi)
     */
    public function exportExcelAdvanced()
    {
        $items = MasterItem::with('kategoris')->get();
        $filename = 'master_items_export_' . date('Ymd_His') . '.xls';

        // Mulai buat konten
        $content = "Master Items Export\n";
        $content .= "Tanggal: " . date('d/m/Y H:i:s') . "\n\n";

        // Header tabel
        $content .= "No\t";
        $content .= "Nama Kategori\t";
        $content .= "Nama Items\t";
        $content .= "Supplier\t";
        $content .= "Harga Beli\t";
        $content .= "Laba (%)\t";
        $content .= "Harga Jual\t";
        $content .= "Jenis\t";
        $content .= "Kode Item\n";

        // Data
        $no = 1;
        foreach ($items as $item) {
            $kategoriNames = $item->kategoris->pluck('nama')->toArray();
            $kategoriString = !empty($kategoriNames) ? implode(', ', $kategoriNames) : '-';

            $hargaJual = $item->harga_beli + ($item->harga_beli * $item->laba / 100);

            $content .= $no++ . "\t";
            $content .= $kategoriString . "\t";
            $content .= $item->nama . "\t";
            $content .= $item->supplier . "\t";
            $content .= 'Rp ' . number_format($item->harga_beli, 0, ',', '.') . "\t";
            $content .= $item->laba . "%\t";
            $content .= 'Rp ' . number_format($hargaJual, 0, ',', '.') . "\t";
            $content .= $item->jenis . "\t";
            $content .= $item->kode . "\n";
        }

        // Footer
        $content .= "\n\n";
        $content .= "Total Items: " . $items->count() . "\n";
        $content .= "Export by: Sistem Inventory\n";

        // Konversi ke UTF-16LE untuk Excel
        $content = mb_convert_encoding($content, 'UTF-16LE', 'UTF-8');

        return response($content, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-16LE',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'public',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Versi paling sederhana - PASTI BEKERJA
     */
    public function exportSimpleExcel()
    {
        $items = MasterItem::with('kategoris')->get();
        $filename = 'master_items_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->stream(function () use ($items) {
            $output = fopen('php://output', 'w');

            // Header
            fputcsv($output, [
                'No',
                'Kategori',
                'Nama Item',
                'Supplier',
                'Harga Beli',
                'Laba %',
                'Harga Jual',
                'Jenis',
                'Kode'
            ]);

            // Data
            $no = 1;
            foreach ($items as $item) {
                $kategoriNames = $item->kategoris->pluck('nama')->toArray();
                $kategoriString = !empty($kategoriNames) ? implode(', ', $kategoriNames) : '';

                $hargaJual = $item->harga_beli + ($item->harga_beli * $item->laba / 100);

                fputcsv($output, [
                    $no++,
                    $kategoriString,
                    $item->nama,
                    $item->supplier,
                    $item->harga_beli,
                    $item->laba,
                    $hargaJual,
                    $item->jenis,
                    $item->kode
                ]);
            }

            fclose($output);
        }, 200, $headers);
    }
}