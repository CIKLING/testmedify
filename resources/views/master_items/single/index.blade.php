@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="form-group mb-2">
                    <a href="{{url('master-items')}}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Item
                    </a>
                </div>
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-box"></i> Detail Master Item</h5>
                    </div>

                    <div class="card-body">
                        {{-- Foto Item --}}
                        @if($data->foto)
                            <div class="mb-4 text-center">
                                <img src="{{ asset('uploads/items/' . $data->foto) }}" alt="Foto {{ $data->nama }}"
                                    style="max-width: 300px; height: auto;" class="img-thumbnail shadow-sm"
                                    onerror="this.style.display='none'">
                            </div>
                        @endif

                        {{-- Informasi Item --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Informasi Item</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th width="35%">Kode Item</th>
                                                <td width="5%">:</td>
                                                <td>
                                                    <span class="badge bg-dark">{{ $data->kode }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Nama Item</th>
                                                <td>:</td>
                                                <td class="fw-bold">{{ $data->nama }}</td>
                                            </tr>
                                            <tr>
                                                <th>Jenis</th>
                                                <td>:</td>
                                                <td>
                                                    <span class="badge bg-secondary">{{ $data->jenis }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Supplier</th>
                                                <td>:</td>
                                                <td>{{ $data->supplier }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Informasi Harga</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th width="35%">Harga Beli</th>
                                                <td width="5%">:</td>
                                                <td class="text-end">
                                                    <span class="fw-bold">Rp
                                                        {{ number_format($data->harga_beli, 0, ',', '.') }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Laba</th>
                                                <td>:</td>
                                                <td class="text-end">
                                                    <span class="badge bg-success">{{ $data->laba }}%</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Harga Jual</th>
                                                <td>:</td>
                                                <td class="text-end fw-bold text-primary">
                                                    Rp
                                                    {{ number_format($data->harga_beli + ($data->harga_beli * $data->laba / 100), 0, ',', '.') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Keuntungan</th>
                                                <td>:</td>
                                                <td class="text-end text-success fw-bold">
                                                    Rp
                                                    {{ number_format($data->harga_beli * $data->laba / 100, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Kategori --}}
                        <div class="card mb-4">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h6 class="mb-0"><i class="fas fa-tags"></i> Kategori</h6>
                                <span class="badge bg-primary">{{ $data->kategoris->count() }} Kategori</span>
                            </div>
                            <div class="card-body">
                                @if($data->kategoris && $data->kategoris->count() > 0)
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($data->kategoris as $kategori)
                                            <a href="{{ url('kategori/view/' . $kategori->kode) }}"
                                                class="btn btn-outline-primary btn-sm d-flex align-items-center"
                                                title="Lihat kategori {{ $kategori->nama }}">
                                                <i class="fas fa-tag me-1"></i>
                                                {{ $kategori->nama }}
                                                <small class="text-muted ms-1">({{ $kategori->kode }})</small>
                                            </a>
                                        @endforeach
                                    </div>

                                    <div class="mt-3">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle"></i>
                                            Klik pada kategori untuk melihat detailnya
                                        </small>
                                    </div>
                                @else
                                    <div class="text-center py-3">
                                        <i class="fas fa-tags fa-2x text-muted mb-3"></i>
                                        <p class="text-muted mb-2">Item ini belum memiliki kategori</p>
                                        <a href="{{ url('master-items/form/edit/' . $data->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-plus"></i> Tambah Kategori
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="d-flex justify-content-between border-top pt-3">
                            <div>
                                <a href="{{ url('master-items/form/edit/' . $data->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Edit Item
                                </a>

                                <a href="{{ url('master-items/delete/' . $data->id) }}" class="btn btn-danger ms-2"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus item ini?\n\nSemua data terkait akan dihapus.');">
                                    <i class="fas fa-trash"></i> Hapus Item
                                </a>
                            </div>

                            <div>
                                <a href="{{ url('master-items') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-list"></i> Lihat Semua Item
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            border: 1px solid rgba(0, 0, 0, .125);
            border-radius: 0.5rem;
        }

        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, .125);
            font-weight: 600;
        }

        .badge {
            font-size: 0.85em;
            padding: 0.35em 0.65em;
        }

        .btn-outline-primary:hover {
            transform: translateY(-2px);
            transition: transform 0.2s;
        }

        .table-borderless th {
            color: #6c757d;
            font-weight: 500;
        }

        .table-borderless td {
            color: #212529;
        }
    </style>
@endsection