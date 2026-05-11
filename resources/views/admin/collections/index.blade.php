@extends('layouts.admin')

@section('title', 'Manajemen Koleksi Produk - Admin Panel')
@section('page_title', 'Manajemen Koleksi')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex flex-column flex-md-row justify-content-between align-items-md-center py-3">
        <h5 class="mb-3 mb-md-0">Daftar Koleksi</h5>
        <div class="d-flex flex-column flex-md-row gap-2">
            <form action="{{ url('admin/collections') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Cari koleksi..." value="{{ $search ?? '' }}">
                <button type="submit" class="btn btn-sm btn-outline-secondary">Cari</button>
            </form>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                + Tambah Koleksi
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width:22%">Nama Koleksi</th>
                        <th style="width:13%">Merek</th>
                        <th style="width:10%">Status</th>
                        <th>Types</th>
                        <th class="text-end pe-4" style="width:15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($collections as $collection)
                    <tr class="align-top">
                        <td class="ps-4 fw-medium pt-3">{{ $collection->name }}</td>
                        <td class="pt-3">{{ $collection->brand->name ?? '-' }}</td>
                        <td class="pt-3">
                            <form action="{{ url('admin/collections/'.$collection->id.'/toggle-status') }}" method="POST" class="m-0 p-0" style="display:inline-block;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="border-0 bg-transparent p-0" title="Klik untuk mengubah status">
                                    <span class="badge {{ $collection->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ ucfirst($collection->status) }}
                                    </span>
                                </button>
                            </form>
                        </td>
                        <td class="pt-2">
                            {{-- Type badges --}}
                            <div class="d-flex flex-wrap gap-1 align-items-center">
                                @forelse($collection->types as $type)
                                    <button type="button"
                                        class="btn btn-sm btn-outline-primary py-0 px-2"
                                        style="font-size:0.78rem;"
                                        data-bs-toggle="modal"
                                        data-bs-target="#typeModal{{ $type->id }}"
                                        title="Lihat variant dari {{ $type->name }}">
                                        {{ $type->name }}
                                        <span class="badge bg-primary ms-1" style="font-size:0.68rem;">{{ $type->variants->count() }}</span>
                                    </button>
                                @empty
                                    <span class="text-muted small fst-italic">Belum ada type</span>
                                @endforelse
                                {{-- Tombol tambah type --}}
                                <button type="button"
                                    class="btn btn-sm btn-outline-success py-0 px-2"
                                    style="font-size:0.78rem;"
                                    data-bs-toggle="modal"
                                    data-bs-target="#addTypeModal{{ $collection->id }}"
                                    title="Tambah type ke {{ $collection->name }}">
                                    + Type
                                </button>
                            </div>
                        </td>
                        <td class="text-end pe-4 pt-3">
                            <button type="button" class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $collection->id }}">Edit</button>
                            <form action="{{ url('admin/collections/'.$collection->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus koleksi ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Belum ada data koleksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- MODALS (di luar tabel) --}}
{{-- ============================================================ --}}
@foreach($collections as $collection)

{{-- Edit Collection Modal --}}
<div class="modal fade" id="editModal{{ $collection->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ url('admin/collections/'.$collection->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Koleksi: {{ $collection->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start">
                    <div class="mb-3">
                        <label class="form-label">Nama Koleksi</label>
                        <input type="text" name="name" class="form-control" value="{{ $collection->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Merek</label>
                        <select name="brand_id" class="form-select">
                            <option value="">-- Tidak Ada --</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ $collection->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="active" {{ $collection->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $collection->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
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

{{-- Add Type Modal (per collection) --}}
<div class="modal fade" id="addTypeModal{{ $collection->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ url('admin/types') }}" method="POST">
            @csrf
            <input type="hidden" name="collection_id" value="{{ $collection->id }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Type ke "{{ $collection->name }}"</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Nama Type <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="cth: Original, Decaf, Capsule..." required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Type</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Per-type modals: show variants + add variant --}}
@foreach($collection->types as $type)
<div class="modal fade" id="typeModal{{ $type->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-0">Type: {{ $type->name }}</h5>
                    <small class="text-muted">Koleksi: {{ $collection->name }}</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                {{-- Daftar Variant --}}
                <h6 class="fw-semibold mb-2">Daftar Variant <span class="badge bg-primary">{{ $type->variants->count() }}</span></h6>
                @if($type->variants->isEmpty())
                    <p class="text-muted fst-italic small">Belum ada variant untuk type ini.</p>
                @else
                <div class="table-responsive mb-3">
                    <table class="table table-sm table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Variant</th>
                                <th>Taste</th>
                                <th>Roast</th>
                                <th>Acidity</th>
                                <th>Body</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($type->variants as $variant)
                            <tr>
                                <td class="fw-medium">{{ $variant->name }}</td>
                                <td>{{ $variant->taste ?? '-' }}</td>
                                <td>{{ $variant->roast ?? '-' }}</td>
                                <td>{{ $variant->acidity ?? '-' }}</td>
                                <td>{{ $variant->body ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $variant->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ ucfirst($variant->status ?? 'active') }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-xs btn-outline-secondary btn-sm py-0 px-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editVariantModal{{ $variant->id }}"
                                        title="Edit Variant">Edit</button>
                                    <form action="{{ url('admin/variants/'.$variant->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Hapus variant {{ $variant->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-xs btn-outline-danger btn-sm py-0 px-1">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                <hr>

                {{-- Tambah Variant Baru --}}
                <h6 class="fw-semibold mb-3">+ Tambah Variant Baru</h6>
                <form action="{{ url('admin/variants') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type_id" value="{{ $type->id }}">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Nama Variant <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-sm" placeholder="cth: Classic Blend" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Taste</label>
                            <input type="text" name="taste" class="form-control form-control-sm" placeholder="cth: Fruity, Nutty">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Roast</label>
                            <input type="text" name="roast" class="form-control form-control-sm" placeholder="cth: Medium, Dark">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label form-label-sm">Acidity (0-10)</label>
                            <input type="number" name="acidity" step="0.1" min="0" max="10" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label form-label-sm">Body (0-10)</label>
                            <input type="number" name="body" step="0.1" min="0" max="10" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label form-label-sm">Ingredient</label>
                            <input type="text" name="ingredient" class="form-control form-control-sm" placeholder="cth: 100% Arabica">
                        </div>
                        <div class="col-12">
                            <label class="form-label form-label-sm">Deskripsi</label>
                            <textarea name="description" class="form-control form-control-sm" rows="2" placeholder="Deskripsi singkat variant..."></textarea>
                        </div>
                    </div>
                    <div class="mt-3 text-end">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-success btn-sm">Simpan Variant</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Edit Variant Modals --}}
@foreach($type->variants as $variant)
<div class="modal fade" id="editVariantModal{{ $variant->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ url('admin/variants/'.$variant->id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="type_id" value="{{ $type->id }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Variant: {{ $variant->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Nama Variant <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-sm" value="{{ $variant->name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="active" {{ ($variant->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ ($variant->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Taste</label>
                            <input type="text" name="taste" class="form-control form-control-sm" value="{{ $variant->taste }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Roast</label>
                            <input type="text" name="roast" class="form-control form-control-sm" value="{{ $variant->roast }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label form-label-sm">Acidity (0-10)</label>
                            <input type="number" name="acidity" step="0.1" min="0" max="10" class="form-control form-control-sm" value="{{ $variant->acidity }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label form-label-sm">Body (0-10)</label>
                            <input type="number" name="body" step="0.1" min="0" max="10" class="form-control form-control-sm" value="{{ $variant->body }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label form-label-sm">Ingredient</label>
                            <input type="text" name="ingredient" class="form-control form-control-sm" value="{{ $variant->ingredient }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label form-label-sm">Deskripsi</label>
                            <textarea name="description" class="form-control form-control-sm" rows="2">{{ $variant->description }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach

@endforeach {{-- end types loop --}}

@endforeach {{-- end collections loop --}}

{{-- Add Collection Modal --}}
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ url('admin/collections') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Koleksi Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Koleksi</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Merek</label>
                        <select name="brand_id" class="form-select">
                            <option value="">-- Tidak Ada --</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="active">Active</option>
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
@endsection
