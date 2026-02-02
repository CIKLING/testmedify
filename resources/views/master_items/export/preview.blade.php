@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-file-excel"></i> Preview Export Excel - Master Items
                        </h5>
                    </div>

                    <div class="card-body">
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> Informasi Export</h6>
                            <p class="mb-0">File Excel akan berisi kolom-kolom berikut:</p>
                            <ol class="mb-0 mt-2">
                                <li><strong>No</strong> - Nomor urut</li>
                                <li><strong>Nama Kategori</strong> - Nama kategori dipisahkan koma</li>
                                <li><strong>Nama Items</strong> - Nama item</li>
                                <li><strong>Nama Supplier</strong> - Nama supplier</li>
                                <li><strong>Harga</strong> - Harga beli (Rp)</li>
                                <li><strong>Laba</strong> - Persentase laba (%)</li>
                                <li><strong>Harga Jual</strong> - Harga jual (Rp)</li>
                            </ol>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <a href="{{ url('master-items') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali ke Master Items
                                </a>
                            </div>

                            <div>
                                <a href="{{ url('master-items/export-excel') }}" class="btn btn-success">
                                    <i class="fas fa-file-excel"></i> Download Excel
                                </a>

                                <button onclick="window.print()" class="btn btn-outline-primary ms-2">
                                    <i class="fas fa-print"></i> Print Preview
                                </button>
                            </div>
                        </div>

                        {{-- Preview Table --}}
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="20%">Nama Kategori</th>
                                        <th width="20%">Nama Items</th>
                                        <th width="15%">Supplier</th>
                                        <th width="10%">Harga</th>
                                        <th width="8%">Laba</th>
                                        <th width="12%">Harga Jual</th>
                                        <th width="10%">Jenis</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $item)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>
                                                @if($item->kategoris->count() > 0)
                                                    @foreach($item->kategoris as $kategori)
                                                        <span class="badge bg-primary me-1">{{ $kategori->nama }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $item->nama }}</strong>
                                                <br>
                                                <small class="text-muted">Kode: {{ $item->kode }}</small>
                                            </td>
                                            <td>{{ $item->supplier }}</td>
                                            <td class="text-end">Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-success">{{ $item->laba }}%</span>
                                            </td>
                                            <td class="text-end fw-bold text-primary">
                                                Rp
                                                {{ number_format($item->harga_beli + ($item->harga_beli * $item->laba / 100), 0, ',', '.') }}
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $item->jenis }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-light">
                                        <td colspan="8" class="text-center">
                                            <small>
                                                Total <strong>{{ $items->total() }}</strong> items
                                                | Export akan mencakup semua data, bukan hanya halaman ini
                                            </small>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="d-flex justify-content-center mt-3">
                            {{ $items->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {

            .btn,
            .alert,
            .d-print-none {
                display: none !important;
            }

            .table th {
                background-color: #f8f9fa !important;
                color: #000 !important;
                border: 2px solid #000 !important;
            }

            .table td {
                border: 1px solid #ddd !important;
            }
        }

        .badge {
            font-size: 0.8em;
        }

        .table th {
            background-color: #2c3e50;
            color: white;
            font-weight: 600;
        }
    </style>
@endsection