@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="form-group mb-2">
                <a href="{{url('master-items')}}" class="btn btn-secondary">Kembali</a>
            </div>
            <div class="card">
                <div class="card-header">{{ $method == 'new' ? 'Tambah' : 'Edit' }} Master Item</div>

                <div class="card-body">
                    <form action="{{url('master-items/form')}}/{{$method}}/{{$method == 'new' ? 0 : $item->id}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-group mb-3">
                            <label>Nama <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{$method == 'new' ? '' : $item->nama}}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label>Harga Beli <span class="text-danger">*</span></label>
                            <input type="number" name="harga_beli" class="form-control @error('harga_beli') is-invalid @enderror" value="{{$method == 'new' ? '' : $item->harga_beli}}" required>
                            @error('harga_beli')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label>Laba (%) <span class="text-danger">*</span></label>
                            <input type="number" name="laba" class="form-control @error('laba') is-invalid @enderror" value="{{$method == 'new' ? '' : $item->laba}}" required>
                            @error('laba')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label>Supplier <span class="text-danger">*</span></label>
                            <select name="supplier" class="form-control @error('supplier') is-invalid @enderror" required>
                                <option value="">Pilih Supplier</option>
                                @php
                                    $suppliers = ['Tokopaedi', 'Bukulapuk', 'TokoBagas', 'E Commurz', 'Blublu'];
                                @endphp
                                @foreach($suppliers as $supplier)
                                    <option value="{{$supplier}}" {{$method == 'edit' && $item->supplier == $supplier ? 'selected' : ''}}>{{$supplier}}</option>
                                @endforeach
                            </select>
                            @error('supplier')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label>Jenis <span class="text-danger">*</span></label>
                            <select name="jenis" class="form-control @error('jenis') is-invalid @enderror" required>
                                <option value="">Pilih Jenis</option>
                                @php
                                    $jenis_list = ['Obat', 'Alkes', 'Matkes', 'Umum', 'ATK'];
                                @endphp
                                @foreach($jenis_list as $jenis_item)
                                    <option value="{{$jenis_item}}" {{$method == 'edit' && $item->jenis == $jenis_item ? 'selected' : ''}}>{{$jenis_item}}</option>
                                @endforeach
                            </select>
                            @error('jenis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label>Kategori</label>
                            <div class="border p-3" style="max-height: 200px; overflow-y: auto;">
                                @if($kategoris->count() > 0)
                                    @foreach($kategoris as $kategori)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="kategoris[]" value="{{$kategori->id}}" id="kategori-{{$kategori->id}}" 
                                        {{ in_array($kategori->id, $selected_kategoris) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="kategori-{{$kategori->id}}">
                                            {{$kategori->nama}}
                                        </label>
                                    </div>
                                    @endforeach
                                @else
                                    <p class="text-muted">Belum ada kategori. <a href="{{url('kategori/form/new')}}" target="_blank">Tambah kategori</a></p>
                                @endif
                            </div>
                            <small class="text-muted">Pilih kategori untuk item ini (opsional)</small>
                        </div>

                        <div class="form-group mb-3">
                            <label>Foto</label>
                            @if($method == 'edit' && $item->foto)
                                <div class="mb-2">
                                    <img src="{{asset('uploads/items/' . $item->foto)}}" style="max-width: 200px; height: auto;" class="img-thumbnail">
                                </div>
                            @endif
                            <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/*">
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Format: JPG, JPEG, PNG, GIF. Max: 2MB</small>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{url('master-items')}}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection