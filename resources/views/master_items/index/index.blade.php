@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                {{-- Header dengan Tombol Aksi --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="mb-0">
                            <i class="fas fa-boxes text-primary me-2"></i>
                            Master Items
                        </h2>
                        <p class="text-muted mb-0">Kelola data master items dan kategori</p>
                    </div>

                    <div class="d-flex gap-2 mb-3">
                        <a href="{{ url('master-items/export-excel') }}" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </a>
                        <a href="{{ url('master-items/export-simple') }}" class="btn btn-outline-success">
                            <i class="fas fa-file-csv"></i> Export CSV
                        </a>
                    </div>
                </div>

                {{-- Alert Messages --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Card Statistik --}}
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card border-primary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1">Total Items</h6>
                                        <h3 class="mb-0">{{ \App\Models\MasterItem::count() }}</h3>
                                    </div>
                                    <div class="bg-primary rounded-circle p-3">
                                        <i class="fas fa-box text-white fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card border-success">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1">Total Kategori</h6>
                                        <h3 class="mb-0">{{ \App\Models\Kategori::count() }}</h3>
                                    </div>
                                    <div class="bg-success rounded-circle p-3">
                                        <i class="fas fa-tags text-white fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card border-warning">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1">Avg. Harga</h6>
                                        <h3 class="mb-0">
                                            Rp
                                            {{ number_format(\App\Models\MasterItem::avg('harga_beli') ?? 0, 0, ',', '.') }}
                                        </h3>
                                    </div>
                                    <div class="bg-warning rounded-circle p-3">
                                        <i class="fas fa-money-bill text-white fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card border-info">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1">Avg. Laba</h6>
                                        <h3 class="mb-0">{{ number_format(\App\Models\MasterItem::avg('laba') ?? 0, 1) }}%
                                        </h3>
                                    </div>
                                    <div class="bg-info rounded-circle p-3">
                                        <i class="fas fa-chart-line text-white fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tombol Utama --}}
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ url('master-items/form/new') }}" class="btn btn-primary">
                                <i class="fas fa-plus-circle me-2"></i> Tambah Master Item
                            </a>

                            <a href="{{ url('kategori/form/new') }}" class="btn btn-primary">
                                <i class="fas fa-tags me-2"></i> Tambah Kategori
                            </a>

                            <a href="{{ url('kategori') }}" class="btn btn-outline-primary">
                                <i class="fas fa-list me-2"></i> Lihat Kategori
                            </a>

                            <button type="button" class="btn btn-outline-secondary" onclick="location.reload()">
                                <i class="fas fa-sync me-2"></i> Refresh
                            </button>

                            {{-- Dropdown untuk action lainnya --}}
                            <div class="dropdown">

                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ url('master-items/update-random-data') }}">
                                            <i class="fas fa-random me-2"></i> Generate Data Random
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ url('master-items/export-excel') }}">
                                            <i class="fas fa-file-excel me-2 text-success"></i> Export Excel
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ url('master-items/export-preview') }}">
                                            <i class="fas fa-eye me-2 text-info"></i> Preview Export
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card Utama dengan Filter dan Table --}}
                <div class="card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-table me-2"></i>
                            Daftar Master Items
                        </h5>
                        <span class="badge bg-primary">
                            {{ \App\Models\MasterItem::count() }} items
                        </span>
                    </div>

                    <div class="card-body">
                        {{-- Filter Section --}}
                        <div class="mb-4">
                            @include('master_items.index.filter')
                        </div>

                        {{-- Info Export --}}

                        {{-- Table Section --}}
                        <div class="table-responsive">
                            @include('master_items.index.table')
                        </div>
                    </div>

                    {{-- Card Footer --}}
                    <div class="card-footer bg-light">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <i class="fas fa-database me-1"></i>
                                    Data terupdate: {{ now()->format('d/m/Y H:i') }}
                                </small>
                            </div>
                            <div class="col-md-6 text-end">
                                <small class="text-muted">
                                    <i class="fas fa-file-excel me-1"></i>
                                    <a href="{{ url('master-items/export-excel') }}" class="text-decoration-none">
                                        Download Excel
                                    </a>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @include('master_items.index.js')
@endsection

@section('styles')
    <style>
        .card {
            border: 1px solid rgba(0, 0, 0, .125);
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 1rem;
        }

        .card-header {
            border-bottom: 2px solid rgba(0, 0, 0, .125);
            font-weight: 600;
        }

        .card-footer {
            border-top: 1px solid rgba(0, 0, 0, .125);
            font-size: 0.85rem;
        }

        .badge {
            font-size: 0.85em;
            padding: 0.35em 0.65em;
        }

        .btn {
            border-radius: 0.375rem;
            font-weight: 500;
        }

        .dropdown-menu {
            border-radius: 0.375rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .alert {
            border-radius: 0.375rem;
            border: none;
        }

        .rounded-circle {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endsection