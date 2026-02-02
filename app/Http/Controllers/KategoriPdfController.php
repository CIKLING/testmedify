<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class KategoriPdfController extends Controller
{
    public function exportPdf($kode)
    {
        $kategori = Kategori::with('masterItems')->where('kode', $kode)->first();

        if (!$kategori) {
            return redirect()->back()->with('error', 'Kategori tidak ditemukan');
        }

        $data = [
            'kategori' => $kategori,
            'items' => $kategori->masterItems,
            'tanggal_cetak' => now()->format('d/m/Y H:i:s')
        ];

        $pdf = Pdf::loadView('master_items.kategori.pdfkategori', $data);

        // Nama file: Kategori_{kode}_{tanggal}.pdf
        $filename = 'Kategori_' . $kategori->kode . '_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }

    public function exportPdfAll()
    {
        $kategoris = Kategori::withCount('masterItems')->get();

        $data = [
            'kategoris' => $kategoris,
            'tanggal_cetak' => now()->format('d/m/Y H:i:s'),
            'total_kategori' => $kategoris->count(),
            'total_items' => $kategoris->sum('master_items_count')
        ];

        $pdf = Pdf::loadView('master_items.kategori.pdfkategori', $data);

        $filename = 'Semua_Kategori_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }
}