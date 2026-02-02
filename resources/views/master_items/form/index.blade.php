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
                        {{-- Debug info --}}
                        @if(env('APP_DEBUG'))
                            <div class="alert alert-info d-none">
                                <small>Debug: Method = {{ $method }}, Item ID = {{ $item->id ?? 0 }}, Kategori Count = {{ count($kategoris ?? []) }}</small>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ url('master-items/form/' . $method . '/' . ($method == 'new' ? 0 : ($item->id ?? 0))) }}" method="POST" enctype="multipart/form-data" id="itemForm">
                            @csrf

                            <div class="row">
                                {{-- Kolom Kiri --}}
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Kode</label>
                                        <input type="text" class="form-control bg-light" value="{{ $method == 'edit' ? ($item->kode ?? '') : 'Auto Generate' }}" readonly>
                                        <small class="text-muted">Kode akan digenerate otomatis</small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label>Nama <span class="text-danger">*</span></label>
                                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                                               value="{{ old('nama', $item->nama ?? '') }}" 
                                               required
                                               placeholder="Masukkan nama item">
                                        @error('nama')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label>Harga Beli <span class="text-danger">*</span></label>
                                        <input type="number" name="harga_beli" class="form-control @error('harga_beli') is-invalid @enderror" 
                                               value="{{ old('harga_beli', $item->harga_beli ?? '') }}" 
                                               required
                                               min="0"
                                               step="100"
                                               placeholder="Masukkan harga beli">
                                        @error('harga_beli')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label>Laba (%) <span class="text-danger">*</span></label>
                                        <input type="number" name="laba" class="form-control @error('laba') is-invalid @enderror" 
                                               value="{{ old('laba', $item->laba ?? '') }}" 
                                               required
                                               min="0"
                                               max="100"
                                               placeholder="Masukkan persentase laba">
                                        @error('laba')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Kolom Kanan --}}
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Supplier <span class="text-danger">*</span></label>
                                        <select name="supplier" class="form-control @error('supplier') is-invalid @enderror" required>
                                            <option value="">Pilih Supplier</option>
                                            @php
                                                $suppliers = ['Tokopaedi', 'Bukulapuk', 'TokoBagas', 'E Commurz', 'Blublu'];
                                            @endphp
                                            @foreach($suppliers as $supplier)
                                                <option value="{{ $supplier }}" 
                                                    {{ old('supplier', $item->supplier ?? '') == $supplier ? 'selected' : '' }}>
                                                    {{ $supplier }}
                                                </option>
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
                                                <option value="{{ $jenis_item }}"
                                                    {{ old('jenis', $item->jenis ?? '') == $jenis_item ? 'selected' : '' }}>
                                                    {{ $jenis_item }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('jenis')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label>Kategori</label>
                                        <div class="border p-3" style="max-height: 200px; overflow-y: auto; background-color: #f8f9fa;">
                                            @if(isset($kategoris) && count($kategoris) > 0)
                                                @foreach($kategoris as $kategori)
                                                    <div class="form-check">
                                                        <input class="form-check-input" 
                                                               type="checkbox" 
                                                               name="kategoris[]" 
                                                               value="{{ $kategori->id }}" 
                                                               id="kategori-{{ $kategori->id }}"
                                                               {{ (isset($selected_kategoris) && in_array($kategori->id, $selected_kategoris)) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="kategori-{{ $kategori->id }}">
                                                            {{ $kategori->nama }}
                                                            @if(isset($kategori->kode))
                                                                <small class="text-muted">({{ $kategori->kode }})</small>
                                                            @endif
                                                        </label>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="text-center py-3">
                                                    <p class="text-muted mb-2">Belum ada kategori</p>
                                                    <a href="{{ url('kategori/form/new') }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                        <i class="fas fa-plus"></i> Tambah Kategori
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                        <small class="text-muted">Pilih kategori untuk item ini (opsional)</small>
                                    </div>
                                </div>
                            </div>

                            {{-- Foto --}}
                            <div class="form-group mb-4">
                                <label>Foto Item</label>
                                @if($method == 'edit' && isset($item->foto) && $item->foto)
                                    <div class="mb-3">
                                        <p class="mb-1">Foto Saat Ini:</p>
                                        <img src="{{ asset('uploads/items/' . $item->foto) }}" 
                                             alt="Foto Item" 
                                             style="max-width: 200px; height: auto;" 
                                             class="img-thumbnail mb-2"
                                             onerror="this.style.display='none'">
                                        <div>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDeleteFoto()">
                                                <i class="fas fa-trash"></i> Hapus Foto
                                            </button>
                                        </div>
                                    </div>
                                @endif

                                <input type="file" 
                                       name="foto" 
                                       class="form-control @error('foto') is-invalid @enderror" 
                                       accept="image/*"
                                       id="fotoInput">
                                @error('foto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    Format: JPG, JPEG, PNG, GIF. Maksimal: 2MB
                                    @if($method == 'edit')
                                        (Kosongkan jika tidak ingin mengubah foto)
                                    @endif
                                </small>
                            </div>

                            {{-- Tombol Aksi --}}
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> {{ $method == 'new' ? 'Simpan' : 'Update' }}
                                </button>
                                <a href="{{ url('master-items') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Batal
                                </a>

                                @if($method == 'edit' && isset($item->id) && $item->id > 0)
                                    <a href="{{ url('master-items/view/' . $item->kode) }}" class="btn btn-info">
                                        <i class="fas fa-eye"></i> Lihat Detail
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus Foto --}}
    @if($method == 'edit' && isset($item->foto) && $item->foto)
        <div class="modal fade" id="deleteFotoModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Hapus Foto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin menghapus foto ini?
                        <div class="mt-2 text-center">
                            <img src="{{ asset('uploads/items/' . $item->foto) }}" 
                                 alt="Foto Item" 
                                 style="max-width: 150px; height: auto;" 
                                 class="img-thumbnail">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger" onclick="deleteFoto()">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection

@section('scripts')
    <script>
        // Preview foto sebelum upload
        document.getElementById('fotoInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Tampilkan preview
                    const previewContainer = document.createElement('div');
                    previewContainer.className = 'mt-2';
                    previewContainer.innerHTML = `
                        <p class="mb-1">Preview:</p>
                        <img src="${e.target.result}" alt="Preview" style="max-width: 200px; height: auto;" class="img-thumbnail">
                    `;

                    // Hapus preview sebelumnya jika ada
                    const oldPreview = document.querySelector('.foto-preview');
                    if (oldPreview) oldPreview.remove();

                    previewContainer.classList.add('foto-preview');
                    document.getElementById('fotoInput').parentNode.appendChild(previewContainer);
                }
                reader.readAsDataURL(file);
            }
        });

        // Konfirmasi hapus foto
        function confirmDeleteFoto() {
            const modal = new bootstrap.Modal(document.getElementById('deleteFotoModal'));
            modal.show();
        }

        // Hapus foto
        function deleteFoto() {
            // Tambahkan input hidden untuk menandai foto akan dihapus
            const form = document.getElementById('itemForm');
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'hapus_foto';
            hiddenInput.value = '1';
            form.appendChild(hiddenInput);

            // Sembunyikan preview foto
            const fotoPreview = document.querySelector('.foto-preview');
            if (fotoPreview) fotoPreview.remove();

            // Reset input file
            document.getElementById('fotoInput').value = '';

            // Tutup modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteFotoModal'));
            modal.hide();

            // Tampilkan alert
            alert('Foto akan dihapus saat data disimpan.');
        }

        // Validasi form
        document.getElementById('itemForm').addEventListener('submit', function(e) {
            const hargaBeli = document.querySelector('input[name="harga_beli"]').value;
            const laba = document.querySelector('input[name="laba"]').value;

            if (parseFloat(hargaBeli) < 0) {
                e.preventDefault();
                alert('Harga beli tidak boleh negatif');
                return false;
            }

            if (parseFloat(laba) < 0 || parseFloat(laba) > 100) {
                e.preventDefault();
                alert('Laba harus antara 0% - 100%');
                return false;
            }
        });
    </script>

    <style>
        .form-check-label {
            cursor: pointer;
            user-select: none;
        }

        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            font-weight: 600;
        }

        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }
    </style>
@endsection