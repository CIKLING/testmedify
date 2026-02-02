@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            {{-- Debug info --}}
            @if(env('APP_DEBUG') && !$data)
                <div class="alert alert-warning">
                    <strong>Debug:</strong> Data kategori tidak ditemukan
                </div>
            @endif

            {{-- Pesan Error --}}
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if(!$data)
                <div class="alert alert-danger">
                    <h5>Kategori tidak ditemukan!</h5>
                    <a href="{{ url('kategori') }}" class="btn btn-secondary mt-2">Kembali ke Daftar Kategori</a>
                </div>
            @else
                <div class="form-group mb-2">
                    <a href="{{ url('kategori') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Kategori
                    </a>
                </div>
                
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-folder"></i> Detail Kategori: {{ $data->nama }}
                        </h5>
                    </div>

                    <div class="card-body">
                        {{-- Informasi Kategori --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Informasi Kategori</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th width="30%">Kode Kategori</th>
                                                <td width="5%">:</td>
                                                <td>
                                                    <span class="badge bg-primary fs-6">{{ $data->kode }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Nama Kategori</th>
                                                <td>:</td>
                                                <td class="fw-bold">{{ $data->nama }}</td>
                                            </tr>
                                            @if(isset($data->deskripsi) && $data->deskripsi)
                                            <tr>
                                                <th>Deskripsi</th>
                                                <td>:</td>
                                                <td>{{ $data->deskripsi }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <th>Total Items</th>
                                                <td>:</td>
                                                <td>
                                                    <span class="badge bg-info">{{ $items->count() }} items</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Daftar Items --}}
                        <div class="mt-4">
                            <h5>
                                <i class="fas fa-boxes"></i> 
                                Daftar Items dalam Kategori Ini
                                <span class="badge bg-secondary">{{ $items->count() }}</span>
                            </h5>
                            
                            @if($items->count() > 0)
                                <div class="table-responsive mt-3">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="10%">Kode Item</th>
                                                <th>Nama Item</th>
                                                <th width="10%">Jenis</th>
                                                <th width="12%">Harga Beli</th>
                                                <th width="8%">Laba</th>
                                                <th width="12%">Harga Jual</th>
                                                <th width="12%">Supplier</th>
                                                <th width="10%" class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($items as $index => $item)
                                                <tr>
                                                    <td class="text-center">{{ $index + 1 }}</td>
                                                    <td>
                                                        <span class="badge bg-dark">{{ $item->kode }}</span>
                                                    </td>
                                                    <td>{{ $item->nama }}</td>
                                                    <td>
                                                        <span class="badge bg-secondary">{{ $item->jenis }}</span>
                                                    </td>
                                                    <td class="text-end">
                                                        Rp {{ number_format($item->harga_beli, 0, ',', '.') }}
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-success">{{ $item->laba }}%</span>
                                                    </td>
                                                    <td class="text-end fw-bold text-primary">
                                                        Rp {{ number_format($item->harga_jual ?? ($item->harga_beli * (1 + $item->laba/100)), 0, ',', '.') }}
                                                    </td>
                                                    <td>{{ $item->supplier }}</td>
                                                    <td class="text-center">
                                                        <a href="{{ url('master-items/view/' . $item->kode) }}" 
                                                           class="btn btn-sm btn-info" 
                                                           title="Lihat Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info mt-3">
                                    <i class="fas fa-info-circle"></i> 
                                    Belum ada item yang memiliki kategori ini.
                                    <a href="{{ url('master-items/form/new') }}" class="btn btn-sm btn-outline-primary ms-2">
                                        Tambah Item Baru
                                    </a>
                                </div>
                            @endif
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="mt-4 pt-3 border-top">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="{{ url('kategori/form/edit/' . $data->id) }}" 
                                       class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Edit Kategori
                                    </a>
                                    
                                    <a href="{{ url('kategori/delete/' . $data->id) }}" 
                                       class="btn btn-danger ms-2"
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?\n\nNote: Item yang terkait tidak akan dihapus.');">
                                        <i class="fas fa-trash"></i> Hapus Kategori
                                    </a>
                                </div>
                                
                                <div>
                                    <a href="{{ url('kategori/export-pdf/' . $data->kode) }}" 
                                       class="btn btn-outline-danger" 
                                       target="_blank">
                                        <i class="fas fa-file-pdf"></i> Export PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
    
    .badge {
        font-size: 0.85em;
        padding: 0.35em 0.65em;
    }
    
    .card-header {
        border-bottom: 2px solid rgba(0,0,0,.125);
    }
</style>
@endsection