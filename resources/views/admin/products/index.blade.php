@extends('layouts.admin')

@section('title', 'Manajemen Produk - Admin Panel')
@section('page_title', 'Manajemen Produk')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex flex-column flex-md-row justify-content-between align-items-md-center py-3">
        <h5 class="mb-3 mb-md-0">Daftar Produk</h5>
        <div class="d-flex flex-column flex-md-row gap-2">
            <form action="{{ url('admin/products') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Cari produk..." value="{{ $search ?? '' }}">
                <button type="submit" class="btn btn-sm btn-outline-secondary">Cari</button>
            </form>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                + Tambah Produk
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Nama Produk</th>
                        <th>Merek</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td class="ps-4 fw-medium">{{ $product->name }}</td>
                        <td>{{ $product->brand->name ?? '-' }}</td>
                        <td>{{ $product->category->name ?? '-' }}</td>
                        <td>Rp {{ number_format($product->regular_price, 0, ',', '.') }}</td>
                        <td>
                            <form action="{{ url('admin/products/'.$product->id.'/toggle-status') }}" method="POST" class="m-0 p-0" style="display:inline-block;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="border-0 bg-transparent p-0" title="Klik untuk mengubah status">
                                    <span class="badge {{ $product->status == 'active' ? 'bg-success' : ($product->status == 'draft' ? 'bg-warning' : 'bg-secondary') }}">
                                        {{ ucfirst($product->status) }}
                                    </span>
                                </button>
                            </form>
                        </td>
                        <td class="text-end pe-4">
                            <button type="button" class="btn btn-sm btn-outline-info me-1" data-bs-toggle="modal" data-bs-target="#previewModal{{ $product->id }}">Preview</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $product->id }}">Edit</button>
                            <form action="{{ url('admin/products/'.$product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada data produk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ url('admin/products') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Produk Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Merek</label>
                        <select name="brand_id" class="form-select">
                            <option value="">-- Pilih Merek --</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="category_id" class="form-select">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga Regular</label>
                        <input type="number" name="harga_reguler" class="form-control" value="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="active">Active</option>
                            <option value="draft">Draft</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

@foreach($products as $product)
<!-- Preview Modal -->
<div class="modal fade" id="previewModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start">
                <div class="text-center mb-4">
                    @php
                        $img_path = !empty($product->gambar_utama) && file_exists(public_path($product->gambar_utama)) ? $product->gambar_utama : 'images/no-image.png';
                    @endphp
                    <img src="{{ asset($img_path) }}" alt="{{ $product->name }}" class="img-fluid rounded shadow-sm" style="max-height: 250px; object-fit: contain;">
                </div>
                <table class="table table-sm table-borderless">
                    <tbody>
                        <tr>
                            <th width="35%">Nama Produk</th>
                            <td>: {{ $product->name }}</td>
                        </tr>
                        <tr>
                            <th>SKU</th>
                            <td>: {{ $product->sku ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Merek</th>
                            <td>: {{ $product->brand->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td>: {{ $product->category->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tipe Packing</th>
                            <td>: {{ $product->tipe_packing ?? '-' }} {{ !empty($product->inner_kemasan) ? '('.$product->inner_kemasan.')' : '' }}</td>
                        </tr>
                        <tr>
                            <th>Harga Regular</th>
                            <td>: Rp {{ number_format($product->harga_reguler ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>: 
                                <span class="badge {{ $product->status == 'active' ? 'bg-success' : ($product->status == 'draft' ? 'bg-warning' : 'bg-secondary') }}">
                                    {{ ucfirst($product->status) }}
                                </span>
                            </td>
                        </tr>
                        @if(!empty($product->link_web))
                        <tr>
                            <th>Link Web</th>
                            <td>: <a href="{{ $product->link_web }}" target="_blank" class="text-break">{{ $product->link_web }}</a></td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ url('admin/products/'.$product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start">
                    <div class="mb-3">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Merek</label>
                        <select name="brand_id" class="form-select">
                            <option value="">-- Pilih Merek --</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="category_id" class="form-select">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga Regular</label>
                        <input type="number" name="harga_reguler" class="form-control" value="{{ $product->harga_reguler }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="active" {{ $product->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="draft" {{ $product->status == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="inactive" {{ $product->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gambar Utama</label>
                        @if(!empty($product->gambar_utama) && file_exists(public_path($product->gambar_utama)))
                            <div class="mb-2">
                                <img src="{{ asset($product->gambar_utama) }}" alt="Preview" class="img-thumbnail" style="max-height: 100px;">
                            </div>
                        @else
                            <div class="mb-2">
                                <img src="{{ asset('images/no-image.png') }}" alt="Preview" class="img-thumbnail" style="max-height: 100px;">
                            </div>
                        @endif
                        <input type="file" name="gambar_utama_file" class="form-control mb-2" accept="image/*">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-link-45deg"></i> Atau Path</span>
                            <input type="text" name="gambar_utama" class="form-control" value="{{ $product->gambar_utama }}" placeholder="Contoh: images/uploads/file.jpg">
                        </div>
                        <small class="text-muted">Upload file baru atau paste path gambar dari Asset Manager.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach
@endsection
