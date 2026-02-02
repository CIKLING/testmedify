<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Detail Kategori - {{ $kategori->kode }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }

        .header h1 {
            color: #2c3e50;
            margin: 0;
            font-size: 24px;
        }

        .header .subtitle {
            color: #7f8c8d;
            font-size: 14px;
        }

        .info-kategori {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            margin-bottom: 8px;
        }

        .info-label {
            width: 150px;
            font-weight: bold;
            color: #495057;
        }

        .info-value {
            flex: 1;
        }

        .badge {
            background-color: #3498db;
            color: white;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
        }

        .table-container {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table thead {
            background-color: #2c3e50;
            color: white;
        }

        table th {
            padding: 10px;
            text-align: left;
            font-size: 11px;
            border: 1px solid #dee2e6;
        }

        table td {
            padding: 8px;
            font-size: 10px;
            border: 1px solid #dee2e6;
        }

        table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
            background-color: white;
        }

        .page-break {
            page-break-after: always;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #7f8c8d;
            font-style: italic;
        }

        .summary {
            background-color: #e8f4fc;
            border: 1px solid #b6d4fe;
            border-radius: 5px;
            padding: 10px;
            margin-top: 15px;
            font-size: 11px;
        }

        .summary strong {
            color: #0d6efd;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN DETAIL KATEGORI</h1>
        <div class="subtitle">Sistem Manajemen Inventory</div>
    </div>

    <div class="info-kategori">
        <div class="info-row">
            <div class="info-label">Kode Kategori:</div>
            <div class="info-value">
                <span class="badge">{{ $kategori->kode }}</span>
            </div>
        </div>

        <div class="info-row">
            <div class="info-label">Nama Kategori:</div>
            <div class="info-value"><strong>{{ $kategori->nama }}</strong></div>
        </div>

        @if($kategori->deskripsi)
            <div class="info-row">
                <div class="info-label">Deskripsi:</div>
                <div class="info-value">{{ $kategori->deskripsi }}</div>
            </div>
        @endif

        <div class="info-row">
            <div class="info-label">Total Item:</div>
            <div class="info-value">
                <strong>{{ $items->count() }} item(s)</strong>
            </div>
        </div>
    </div>

    <div class="table-container">
        <h3>Daftar Item dalam Kategori</h3>

        @if($items->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Kode Item</th>
                            <th width="25%">Nama Item</th>
                            <th width="10%">Jenis</th>
                            <th width="15%">Harga Beli</th>
                            <th width="10%">Laba</th>
                            <th width="15%">Harga Jual</th>
                            <th width="10%">Supplier</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $item->kode }}</td>
                                <td>{{ $item->nama }}</td>
                                <td class="text-center">{{ $item->jenis }}</td>
                                <td class="text-right">Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                <td class="text-center">{{ $item->laba }}%</td>
                                <td class="text-right">
                                    Rp {{ number_format($item->harga_beli * (1 + $item->laba / 100), 0, ',', '.') }}
                                </td>
                                <td>{{ $item->supplier }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="summary">
                    <strong>Ringkasan:</strong>
                    Total {{ $items->count() }} item dengan nilai inventory estimasi:
                    Rp {{ number_format($items->sum(function ($item) {
                return $item->harga_beli * (1 + $item->laba / 100);
            }), 0, ',', '.') }}
                </div>
        @else
            <div class="no-data">
                <p>Tidak ada item yang terkait dengan kategori ini.</p>
            </div>
        @endif
    </div>

    <div class="footer">
        Dicetak pada: {{ $tanggal_cetak }} | Halaman <span class="pageNumber"></span> dari <span
            class="totalPages"></span>
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $text = "Halaman {PAGE_NUM} dari {PAGE_COUNT}";
            $size = 10;
            $font = $fontMetrics->getFont("DejaVu Sans");
            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
            $x = ($pdf->get_width() - $width) / 2;
            $y = $pdf->get_height() - 35;
            $pdf->page_text($x, $y, $text, $font, $size);
            
            $pdf->page_text(30, $pdf->get_height() - 35, "Dicetak oleh: Sistem Inventory", $font, 10, array(0,0,0));
        }
    </script>
</body>

</html>