@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                {{-- Tombol Kembali --}}
                <div class="form-group mb-3">
                    <a href="{{ url('kategori') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Kategori
                    </a>

                    {{-- Tombol khusus jika datang dari master item --}}
                    @if(request()->get('from_master_item') || session('from_master_item'))
                        <a href="{{ url('master-items/form/new') }}" class="btn btn-info ms-2">
                            <i class="fas fa-box"></i> Kembali ke Form Master Item
                        </a>
                    @endif
                </div>

                {{-- Card Form --}}
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-folder-plus"></i>
                            {{ $method == 'new' ? 'Tambah Kategori Baru' : 'Edit Kategori' }}
                        </h5>
                    </div>

                    <div class="card-body">
                        {{-- Pesan Error --}}
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <h6 class="alert-heading fw-bold">Perbaiki kesalahan berikut:</h6>
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        {{-- Form Input --}}
                        <form action="{{ url('kategori/form/' . $method . '/' . ($kategori->id ?? 0)) }}" method="POST"
                            id="kategoriForm">
                            @csrf

                            {{-- Hidden field untuk menandai dari master item --}}
                            @if(request()->get('from_master_item'))
                                <input type="hidden" name="from_master_item" value="1">
                            @endif

                            {{-- Field Kode --}}
                            <div class="form-group mb-4">
                                <label class="form-label fw-bold">Kode Kategori</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                    <input type="text" class="form-control bg-light fw-bold"
                                        value="{{ $method == 'edit' ? ($kategori->kode ?? '') : 'AUTO-GENERATE' }}" readonly
                                        style="font-family: 'Courier New', monospace;">
                                </div>
                                <div class="form-text">
                                    <i class="fas fa-info-circle"></i> Kode akan digenerate otomatis oleh sistem
                                </div>
                            </div>

                            {{-- Field Nama --}}
                            <div class="form-group mb-4">
                                <label class="form-label fw-bold">
                                    Nama Kategori
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                        value="{{ old('nama', $kategori->nama ?? '') }}" required maxlength="100"
                                        placeholder="Contoh: Elektronik, Pakaian, Makanan" autofocus>
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">
                                    <i class="fas fa-lightbulb"></i> Gunakan nama yang deskriptif dan mudah diingat
                                </div>
                            </div>

                            {{-- Field Deskripsi (Opsional) --}}
                            <div class="form-group mb-4">
                                <label class="form-label fw-bold">Deskripsi</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-align-left"></i></span>
                                    <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
                                        rows="3" placeholder="Deskripsi kategori (opsional)..."
                                        maxlength="255">{{ old('deskripsi', $kategori->deskripsi ?? '') }}</textarea>
                                    @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">
                                    <i class="fas fa-question-circle"></i> Maksimal 255 karakter. Berguna untuk penjelasan
                                    tambahan.
                                </div>
                            </div>

                            {{-- Tombol Aksi --}}
                            <div class="form-group pt-3 border-top">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                                            <i class="fas fa-save me-2"></i>
                                            {{ $method == 'new' ? 'Simpan Kategori' : 'Update Kategori' }}
                                        </button>

                                        <button type="reset" class="btn btn-outline-secondary ms-2" id="resetBtn">
                                            <i class="fas fa-redo me-2"></i> Reset
                                        </button>
                                    </div>

                                    <div>
                                        @if($method == 'edit' && isset($kategori->id))
                                            <a href="{{ url('kategori/delete/' . $kategori->id) }}"
                                                class="btn btn-outline-danger" onclick="return confirm('Hapus kategori ini?')">
                                                <i class="fas fa-trash me-2"></i> Hapus
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Card Footer --}}
                    <div class="card-footer bg-light">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <i class="fas fa-history me-1"></i>
                                    {{ $method == 'new' ? 'Kategori baru' : 'Terakhir diupdate' }}:
                                    {{ now()->format('d/m/Y H:i') }}
                                </small>
                            </div>
                            <div class="col-md-6 text-end">
                                <small class="text-muted">
                                    <i class="fas fa-boxes me-1"></i>
                                    Total Kategori: {{ \App\Models\Kategori::count() ?? 0 }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Informasi Tambahan --}}
                <!-- @if($method == 'new')
                        <div class="card mt-4">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Tips Membuat Kategori</h6>
                            </div>
                            <div class="card-body">
                                <ul class="mb-0">
                                    <li>Buat kategori berdasarkan jenis produk (Contoh: Elektronik, Pakaian, Makanan)</li>
                                    <li>Gunakan nama yang singkat dan jelas</li>
                                    <li>Hindari duplikasi nama kategori</li>
                                    <li>Kategori dapat digunakan untuk mengelompokkan master items</li>
                                    <li>Setelah disimpan, kategori langsung tersedia untuk dipilih di master items</li>
                                </ul>
                            </div>
                        </div>
                    @endif -->
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .card-header {
            border-radius: 0.375rem 0.375rem 0 0 !important;
        }

        .form-label {
            color: #495057;
            font-weight: 500;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-right: none;
        }

        .form-control:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .form-text {
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        #submitBtn {
            min-width: 160px;
        }

        .card-footer {
            border-top: 1px solid rgba(0, 0, 0, .125);
            font-size: 0.85rem;
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('kategoriForm');
            const submitBtn = document.getElementById('submitBtn');
            const resetBtn = document.getElementById('resetBtn');

            // Handle form submit
            form.addEventListener('submit', function (e) {
                const namaInput = form.querySelector('input[name="nama"]');

                // Validasi nama tidak boleh hanya spasi
                if (namaInput.value.trim() === '') {
                    e.preventDefault();
                    alert('Nama kategori tidak boleh kosong!');
                    namaInput.focus();
                    return false;
                }

                // Tampilkan loading
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';

                // Jika dari master item, gunakan AJAX
                const isFromMasterItem = form.querySelector('input[name="from_master_item"]');
                if (isFromMasterItem) {
                    e.preventDefault();

                    fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                // Kirim ke parent window jika ada
                                if (window.opener && !window.opener.closed) {
                                    window.opener.postMessage({
                                        type: 'kategori_added',
                                        kategori: data.kategori
                                    }, '*');
                                }

                                // Redirect atau tutup
                                if (data.redirect) {
                                    window.location.href = data.redirect;
                                } else {
                                    window.close();
                                }
                            } else {
                                alert(data.message || 'Terjadi kesalahan');
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Simpan Kategori';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan jaringan');
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Simpan Kategori';
                        });
                }
            });

            // Handle reset button
            resetBtn.addEventListener('click', function () {
                if (confirm('Reset semua input ke nilai awal?')) {
                    form.reset();
                    form.querySelector('input[name="nama"]').focus();
                }
            });

            // Auto-close jika dari master item dan berhasil
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('from_master_item')) {
                // Fokus ke input nama
                form.querySelector('input[name="nama"]').focus();

                // Tambahkan event untuk close window
                window.addEventListener('beforeunload', function () {
                    if (window.opener && !window.opener.closed) {
                        window.opener.postMessage({
                            type: 'kategori_form_closed'
                        }, '*');
                    }
                });
            }
        });

        // Handle pesan dari parent window
        window.addEventListener('message', function (event) {
            if (event.data.type === 'refresh_kategori') {
                location.reload();
            }
        });
    </script>
@endsection