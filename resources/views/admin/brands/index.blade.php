@extends('layouts.admin')

@section('title', 'Manajemen Merek - Admin Panel')
@section('page_title', 'Manajemen Merek')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex flex-column flex-md-row justify-content-between align-items-md-center py-3">
        <h5 class="mb-3 mb-md-0">Daftar Merek</h5>
        <div class="d-flex flex-column flex-md-row gap-2">
            <form action="{{ url('admin/brands') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Cari merek..." value="{{ $search ?? '' }}">
                <button type="submit" class="btn btn-sm btn-outline-secondary">Cari</button>
            </form>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                + Tambah Merek
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Logo</th>
                        <th>Nama</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($brands as $brand)
                    <tr>
                        <td class="ps-4">
                            @if($brand->logo_path)
                                <img src="{{ asset($brand->logo_path) }}" alt="{{ $brand->nama_merek }}" style="max-height:40px;max-width:60px;object-fit:contain;">
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="fw-medium">{{ $brand->nama_merek }}</td>
                        <td>{{ $brand->slug }}</td>
                        <td>
                            <span class="badge {{ $brand->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($brand->status) }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <button type="button" class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $brand->id }}">Edit</button>
                            <form action="{{ url('admin/brands/'.$brand->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus merek ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal{{ $brand->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <form action="{{ url('admin/brands/'.$brand->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Merek</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-start">
                                        <div class="mb-3">
                                            <label class="form-label">Nama Merek <span class="text-danger">*</span></label>
                                            <input type="text" name="nama_merek" class="form-control" value="{{ $brand->nama_merek }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Deskripsi (ID)</label>
                                            <textarea name="deskripsi" class="form-control" rows="3">{{ $brand->deskripsi }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Deskripsi (EN)</label>
                                            <textarea name="deskripsi_eng" class="form-control" rows="3">{{ $brand->deskripsi_eng }}</textarea>
                                        </div>

                                        <!-- Logo Section -->
                                        <div class="mb-3">
                                            <label class="form-label">Logo</label>
                                            @if($brand->logo_path)
                                                <div class="mb-2">
                                                    <img src="{{ asset($brand->logo_path) }}" alt="{{ $brand->nama_merek }}" style="max-height:60px;max-width:120px;object-fit:contain;" class="border rounded p-1">
                                                    <small class="text-muted d-block mt-1">Saat ini: {{ $brand->logo_path }}</small>
                                                </div>
                                            @endif

                                            <!-- Tab: Choose or Upload -->
                                            <ul class="nav nav-tabs nav-tabs-sm" role="tablist">
                                                <li class="nav-item">
                                                    <button class="nav-link active small py-1 px-2" data-bs-toggle="tab" data-bs-target="#editAssetTab{{ $brand->id }}" type="button">Pilih dari Asset</button>
                                                </li>
                                                <li class="nav-item">
                                                    <button class="nav-link small py-1 px-2" data-bs-toggle="tab" data-bs-target="#editUploadTab{{ $brand->id }}" type="button">+ Upload Baru</button>
                                                </li>
                                            </ul>
                                            <div class="tab-content border border-top-0 rounded-bottom p-3">
                                                <div class="tab-pane fade show active" id="editAssetTab{{ $brand->id }}">
                                                    <input type="text" class="form-control form-control-sm mb-2 asset-search-input" placeholder="Cari asset..." data-target="editAssetGrid{{ $brand->id }}">
                                                    <div class="asset-grid" id="editAssetGrid{{ $brand->id }}" style="max-height:200px;overflow-y:auto;">
                                                        <div class="row g-2">
                                                            @foreach($assetImages as $asset)
                                                            <div class="col-3 col-md-2 asset-item" data-name="{{ strtolower($asset['name']) }}">
                                                                <label class="d-block text-center cursor-pointer">
                                                                    <input type="radio" name="logo_from_asset" value="{{ $asset['path'] }}" class="d-none asset-radio" {{ $brand->logo_path == $asset['path'] ? 'checked' : '' }}>
                                                                    <img src="{{ $asset['url'] }}" class="img-thumbnail asset-thumb {{ $brand->logo_path == $asset['path'] ? 'border-primary border-2' : '' }}" style="width:60px;height:60px;object-fit:contain;cursor:pointer;" title="{{ $asset['name'] }}">
                                                                    <div class="text-truncate small" style="font-size:0.6rem;">{{ $asset['name'] }}</div>
                                                                </label>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="editUploadTab{{ $brand->id }}">
                                                    <input type="file" name="logo" class="form-control form-control-sm" accept="image/*">
                                                    <small class="text-muted">Format: JPG, PNG, WebP, SVG. Maks 2MB. File akan tersimpan di Asset Manager.</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Status <span class="text-danger">*</span></label>
                                            <select name="status" class="form-select" required>
                                                <option value="active" {{ $brand->status == 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="inactive" {{ $brand->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
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
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Belum ada data merek.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ url('admin/brands') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Merek Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Merek <span class="text-danger">*</span></label>
                        <input type="text" name="nama_merek" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi (ID)</label>
                        <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi (EN)</label>
                        <textarea name="deskripsi_eng" class="form-control" rows="3"></textarea>
                    </div>

                    <!-- Logo Section -->
                    <div class="mb-3">
                        <label class="form-label">Logo</label>
                        <ul class="nav nav-tabs nav-tabs-sm" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active small py-1 px-2" data-bs-toggle="tab" data-bs-target="#addAssetTab" type="button">Pilih dari Asset</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link small py-1 px-2" data-bs-toggle="tab" data-bs-target="#addUploadTab" type="button">+ Upload Baru</button>
                            </li>
                        </ul>
                        <div class="tab-content border border-top-0 rounded-bottom p-3">
                            <div class="tab-pane fade show active" id="addAssetTab">
                                <input type="text" class="form-control form-control-sm mb-2 asset-search-input" placeholder="Cari asset..." data-target="addAssetGrid">
                                <div class="asset-grid" id="addAssetGrid" style="max-height:200px;overflow-y:auto;">
                                    <div class="row g-2">
                                        @foreach($assetImages as $asset)
                                        <div class="col-3 col-md-2 asset-item" data-name="{{ strtolower($asset['name']) }}">
                                            <label class="d-block text-center cursor-pointer">
                                                <input type="radio" name="logo_from_asset" value="{{ $asset['path'] }}" class="d-none asset-radio">
                                                <img src="{{ $asset['url'] }}" class="img-thumbnail asset-thumb" style="width:60px;height:60px;object-fit:contain;cursor:pointer;" title="{{ $asset['name'] }}">
                                                <div class="text-truncate small" style="font-size:0.6rem;">{{ $asset['name'] }}</div>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="addUploadTab">
                                <input type="file" name="logo" class="form-control form-control-sm" accept="image/*">
                                <small class="text-muted">Format: JPG, PNG, WebP, SVG. Maks 2MB. File akan tersimpan di Asset Manager.</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
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

@push('styles')
<style>
.asset-thumb { transition: all 0.15s ease; }
.asset-thumb:hover { border-color: #0d6efd !important; transform: scale(1.05); }
.asset-radio:checked + .asset-thumb { border-color: #0d6efd !important; border-width: 2px !important; box-shadow: 0 0 0 2px rgba(13,110,253,0.25); }
.cursor-pointer { cursor: pointer; }
</style>
@endpush

@push('scripts')
<script>
// Asset search filter
document.querySelectorAll('.asset-search-input').forEach(function(input) {
    input.addEventListener('input', function() {
        var query = this.value.toLowerCase();
        var gridId = this.getAttribute('data-target');
        var items = document.querySelectorAll('#' + gridId + ' .asset-item');
        items.forEach(function(item) {
            var name = item.getAttribute('data-name');
            item.style.display = name.indexOf(query) !== -1 ? '' : 'none';
        });
    });
});

// Visual feedback when selecting asset radio
document.querySelectorAll('.asset-radio').forEach(function(radio) {
    radio.addEventListener('change', function() {
        // Remove highlight from siblings in the same grid
        var grid = this.closest('.asset-grid');
        grid.querySelectorAll('.asset-thumb').forEach(function(thumb) {
            thumb.classList.remove('border-primary', 'border-2');
        });
        // Highlight selected
        var thumb = this.nextElementSibling;
        if (thumb) {
            thumb.classList.add('border-primary', 'border-2');
        }
    });
});
</script>
@endpush
@endsection
