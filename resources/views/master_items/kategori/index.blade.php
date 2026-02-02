@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="form-group mb-2">
                    <a href="{{url('kategori/form/new')}}" class="btn btn-secondary">+ Kategori Baru</a>
                    <a href="{{url('master-items')}}" class="btn btn-info">Daftar Master Items</a>
                </div>

                {{-- Card Statistik --}}
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <h3 class="text-primary">{{ $total_kategori ?? 0 }}</h3>
                                <p class="text-muted mb-0">Total Kategori</p>
                            </div>
                            <div class="col-md-4 text-center">
                                <h3 class="text-success">{{ \App\Models\Kategori::has('masterItems')->count() }}</h3>
                                <p class="text-muted mb-0">Kategori Aktif</p>
                            </div>
                            <div class="col-md-4 text-center">
                                <h3 class="text-warning">{{ \App\Models\Kategori::doesntHave('masterItems')->count() }}</h3>
                                <p class="text-muted mb-0">Kategori Kosong</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Daftar Kategori Items</div>

                    <div class="card-body">
                        @include('master_items.kategori.filter')
                        @include('master_items.kategori.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @include('master_items.kategori.js')
@endsection