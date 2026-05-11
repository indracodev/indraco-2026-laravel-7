@extends('layouts.admin')
@section('title', 'Manajemen Varian - Admin Panel')
@section('page_title', 'Manajemen Varian')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.snow.css" rel="stylesheet">
<style>
.ql-container { min-height: 80px; font-size: 0.9rem; }
.color-swatch { width:28px; height:28px; border-radius:4px; border:2px solid #dee2e6; cursor:pointer; display:inline-block; }
.asset-thumb { width:60px; height:45px; object-fit:cover; border-radius:4px; border:2px solid transparent; cursor:pointer; }
.asset-thumb.selected { border-color:#0d6efd; }
.asset-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(80px,1fr)); gap:8px; max-height:300px; overflow-y:auto; }
</style>
@endpush

@section('content')
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex flex-column flex-md-row justify-content-between align-items-md-center py-3">
        <h5 class="mb-3 mb-md-0">Daftar Varian</h5>
        <div class="d-flex gap-2">
            <form action="{{ url('admin/variants') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Cari varian..." value="{{ $search ?? '' }}">
                <button type="submit" class="btn btn-sm btn-outline-secondary">Cari</button>
            </form>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">+ Tambah</button>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th class="ps-3" style="width:50px">Ord</th>
                    <th>Nama Varian</th>
                    <th>Type</th>
                    <th>Taste</th>
                    <th>A/B</th>
                    <th>Status</th>
                    <th class="text-end pe-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($variants as $variant)
                <tr>
                    <td class="ps-3 text-muted">{{ $variant->sort_order }}</td>
                    <td class="fw-medium">
                        <div class="d-flex align-items-center gap-2">
                            @if($variant->icon_path)
                            <img src="{{ asset($variant->icon_path) }}" style="width:32px;height:32px;object-fit:cover;border-radius:50%;border:2px solid {{ $variant->bg_color ?? '#dee2e6' }}">
                            @else
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:32px;height:32px;background:{{ $variant->bg_color ?? '#e9ecef' }};color:{{ $variant->text_color ?? '#333' }};font-size:11px;font-weight:600;">{{ strtoupper(substr($variant->name,0,2)) }}</div>
                            @endif
                            {{ $variant->name }}
                        </div>
                    </td>
                    <td><span class="text-muted small">{{ $variant->type->name ?? '-' }}</span></td>
                    <td>{{ Str::limit(strip_tags($variant->taste ?? ''), 30) ?: '-' }}</td>
                    <td>
                        <span class="badge bg-info text-dark">A:{{ $variant->acidity ?? '-' }}</span>
                        <span class="badge bg-secondary">B:{{ $variant->body ?? '-' }}</span>
                    </td>
                    <td><span class="badge {{ $variant->status == 'active' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($variant->status ?? 'active') }}</span></td>
                    <td class="text-end pe-3">
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editModal{{ $variant->id }}">Edit</button>
                        <form action="{{ url('admin/variants/'.$variant->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada data varian.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ==================== EDIT MODALS ==================== --}}
@foreach($variants as $variant)
<div class="modal fade" id="editModal{{ $variant->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <form action="{{ url('admin/variants/'.$variant->id) }}" method="POST" enctype="multipart/form-data" id="editForm{{ $variant->id }}">
            @csrf @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Varian: {{ $variant->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        {{-- Col 1 --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-medium">Nama Varian <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ $variant->name }}"
                                    oninput="genSlug(this,'slug_e{{ $variant->id }}')" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Slug</label>
                                <input type="text" name="slug" id="slug_e{{ $variant->id }}" class="form-control bg-light" value="{{ $variant->slug }}" readonly>
                                <small class="text-muted">Otomatis dari nama</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Acidity <span class="text-muted small">(0–10)</span></label>
                                <input type="number" step="0.1" min="0" max="10" name="acidity" class="form-control" value="{{ $variant->acidity }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Body <span class="text-muted small">(0–10)</span></label>
                                <input type="number" step="0.1" min="0" max="10" name="body" class="form-control" value="{{ $variant->body }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Roast</label>
                                <input type="text" name="roast" class="form-control" value="{{ $variant->roast }}">
                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-medium">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="active" {{ ($variant->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ ($variant->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-medium">Sort Order</label>
                                    <input type="number" name="sort_order" class="form-control" value="{{ $variant->sort_order ?? 0 }}">
                                </div>
                            </div>
                            {{-- Color pickers --}}
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-medium">Background Color</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="color" class="form-control form-control-color"
                                            value="{{ $variant->bg_color ?? '#ffffff' }}"
                                            oninput="document.getElementById('bgc_e{{ $variant->id }}').value=this.value;document.getElementById('bgprev_e{{ $variant->id }}').style.background=this.value">
                                        <input type="text" id="bgc_e{{ $variant->id }}" name="bg_color" class="form-control form-control-sm"
                                            value="{{ $variant->bg_color ?? '#ffffff' }}"
                                            oninput="this.previousElementSibling.previousElementSibling.value=this.value">
                                        <span id="bgprev_e{{ $variant->id }}" class="color-swatch" style="background:{{ $variant->bg_color ?? '#ffffff' }}"></span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-medium">Text Color</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="color" class="form-control form-control-color"
                                            value="{{ $variant->text_color ?? '#333333' }}"
                                            oninput="document.getElementById('txc_e{{ $variant->id }}').value=this.value;document.getElementById('txprev_e{{ $variant->id }}').style.background=this.value">
                                        <input type="text" id="txc_e{{ $variant->id }}" name="text_color" class="form-control form-control-sm"
                                            value="{{ $variant->text_color ?? '#333333' }}"
                                            oninput="this.previousElementSibling.previousElementSibling.value=this.value">
                                        <span id="txprev_e{{ $variant->id }}" class="color-swatch" style="background:{{ $variant->text_color ?? '#333333' }}"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Col 2 --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-medium">Deskripsi</label>
                                <div id="desc_e{{ $variant->id }}_editor" style="min-height:80px">{!! $variant->description !!}</div>
                                <input type="hidden" name="description" id="desc_e{{ $variant->id }}_input">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Taste / Rasa</label>
                                <div id="taste_e{{ $variant->id }}_editor" style="min-height:60px">{!! $variant->taste !!}</div>
                                <input type="hidden" name="taste" id="taste_e{{ $variant->id }}_input">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Ingredient / Komposisi</label>
                                <div id="ingr_e{{ $variant->id }}_editor" style="min-height:60px">{!! $variant->ingredient !!}</div>
                                <input type="hidden" name="ingredient" id="ingr_e{{ $variant->id }}_input">
                            </div>

                            {{-- Map Image --}}
                            <div class="mb-3">
                                <label class="form-label fw-medium">Map Image</label>
                                @if($variant->map_image)
                                <div class="mb-2"><img src="{{ asset($variant->map_image) }}" style="max-height:80px;border-radius:6px;"></div>
                                @endif
                                <input type="file" name="map_image" class="form-control form-control-sm" accept="image/*">
                                <small class="text-muted">Upload gambar baru untuk mengganti</small>
                            </div>

                            {{-- Map Opacity --}}
                            <div class="mb-3">
                                <label class="form-label fw-medium">Map Opacity: <span id="opv_e{{ $variant->id }}">{{ $variant->map_opacity ?? 1 }}</span></label>
                                <input type="range" min="0" max="1" step="0.05" class="form-range"
                                    value="{{ $variant->map_opacity ?? 1 }}" name="map_opacity"
                                    oninput="document.getElementById('opv_e{{ $variant->id }}').textContent=parseFloat(this.value).toFixed(2)">
                            </div>

                            {{-- Icon Path --}}
                            <div class="mb-3">
                                <label class="form-label fw-medium">Icon</label>
                                <div class="d-flex gap-2 mb-2 align-items-center">
                                    @if($variant->icon_path)
                                    <img src="{{ asset($variant->icon_path) }}" style="width:40px;height:40px;object-fit:cover;border-radius:6px;" id="iconPreview_e{{ $variant->id }}">
                                    @else
                                    <div id="iconPreview_e{{ $variant->id }}" class="text-muted small">Belum ada icon</div>
                                    @endif
                                </div>
                                <input type="hidden" name="icon_path_asset" id="iconAsset_e{{ $variant->id }}" value="{{ $variant->icon_path }}">
                                <div class="d-flex gap-2">
                                    <input type="file" name="icon_file" class="form-control form-control-sm" accept="image/*"
                                        onchange="previewIcon(this,'iconPreview_e{{ $variant->id }}')">
                                    <button type="button" class="btn btn-sm btn-outline-secondary text-nowrap"
                                        onclick="openAssetPicker('iconAsset_e{{ $variant->id }}','iconPreview_e{{ $variant->id }}')">
                                        Pilih Asset
                                    </button>
                                </div>
                            </div>
                        </div>
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

{{-- ==================== ADD MODAL ==================== --}}
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <form action="{{ url('admin/variants') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Varian Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Varian <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" oninput="genSlug(this,'slug_add')" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Slug</label>
                                <input type="text" name="slug" id="slug_add" class="form-control bg-light" readonly>
                            </div>
                            <div class="row g-2">
                                <div class="col-6 mb-3">
                                    <label class="form-label">Acidity</label>
                                    <input type="number" step="0.1" min="0" max="10" name="acidity" class="form-control" value="0">
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Body</label>
                                    <input type="number" step="0.1" min="0" max="10" name="body" class="form-control" value="0">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Roast</label>
                                <input type="text" name="roast" class="form-control">
                            </div>
                            <div class="row g-2">
                                <div class="col-6 mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Sort Order</label>
                                    <input type="number" name="sort_order" class="form-control" value="0">
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label">BG Color</label>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="color" class="form-control form-control-color" value="#ffffff"
                                            oninput="document.getElementById('bgc_add').value=this.value">
                                        <input type="text" id="bgc_add" name="bg_color" class="form-control form-control-sm" value="#ffffff">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Text Color</label>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="color" class="form-control form-control-color" value="#333333"
                                            oninput="document.getElementById('txc_add').value=this.value">
                                        <input type="text" id="txc_add" name="text_color" class="form-control form-control-sm" value="#333333">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="description" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Taste</label>
                                <textarea name="taste" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ingredient</label>
                                <textarea name="ingredient" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Map Image</label>
                                <input type="file" name="map_image" class="form-control form-control-sm" accept="image/*">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Map Opacity: <span id="opv_add">1.00</span></label>
                                <input type="range" min="0" max="1" step="0.05" class="form-range" value="1" name="map_opacity"
                                    oninput="document.getElementById('opv_add').textContent=parseFloat(this.value).toFixed(2)">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Icon</label>
                                <input type="file" name="icon_file" class="form-control form-control-sm" accept="image/*">
                            </div>
                        </div>
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

{{-- Asset Picker Modal --}}
<div class="modal fade" id="assetPickerModal" tabindex="-1" aria-hidden="true" style="z-index:1060">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Asset</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="asset-grid" id="assetGrid">
                    @foreach($assetFiles as $asset)
                    <div class="text-center" onclick="selectAsset('{{ $asset['path'] }}','{{ $asset['url'] }}')">
                        <img src="{{ $asset['url'] }}" class="asset-thumb" title="{{ $asset['name'] }}">
                        <div class="text-truncate" style="font-size:0.65rem;max-width:80px">{{ $asset['name'] }}</div>
                    </div>
                    @endforeach
                    @if(empty($assetFiles))
                    <p class="text-muted">Belum ada asset. Upload melalui <a href="{{ url('admin/assets') }}">halaman asset</a>.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.js"></script>
<script>
// Slug generator
function genSlug(input, targetId) {
    document.getElementById(targetId).value = input.value
        .toLowerCase().trim()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-');
}

// Icon preview
function previewIcon(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            let el = document.getElementById(previewId);
            if (el.tagName === 'IMG') {
                el.src = e.target.result;
            } else {
                let img = document.createElement('img');
                img.src = e.target.result;
                img.style = 'width:40px;height:40px;object-fit:cover;border-radius:6px;';
                img.id = previewId;
                el.replaceWith(img);
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Asset picker
let _assetTargetId = null, _assetPreviewId = null;
function openAssetPicker(targetId, previewId) {
    _assetTargetId = targetId;
    _assetPreviewId = previewId;
    const modal = new bootstrap.Modal(document.getElementById('assetPickerModal'));
    modal.show();
}
function selectAsset(path, url) {
    if (_assetTargetId) document.getElementById(_assetTargetId).value = path;
    if (_assetPreviewId) {
        let el = document.getElementById(_assetPreviewId);
        if (el && el.tagName === 'IMG') {
            el.src = url;
        } else if (el) {
            let img = document.createElement('img');
            img.src = url;
            img.style = 'width:40px;height:40px;object-fit:cover;border-radius:6px;';
            img.id = _assetPreviewId;
            el.replaceWith(img);
        }
    }
    bootstrap.Modal.getInstance(document.getElementById('assetPickerModal')).hide();
}

// Init Quill editors when modal shown
document.addEventListener('DOMContentLoaded', function() {
    @foreach($variants as $variant)
    (function() {
        const modal = document.getElementById('editModal{{ $variant->id }}');
        let initialized = false;
        modal.addEventListener('shown.bs.modal', function() {
            if (initialized) return;
            initialized = true;
            const fields = [
                { editorId: 'desc_e{{ $variant->id }}_editor', inputId: 'desc_e{{ $variant->id }}_input' },
                { editorId: 'taste_e{{ $variant->id }}_editor', inputId: 'taste_e{{ $variant->id }}_input' },
                { editorId: 'ingr_e{{ $variant->id }}_editor', inputId: 'ingr_e{{ $variant->id }}_input' },
            ];
            fields.forEach(f => {
                const q = new Quill('#' + f.editorId, { theme: 'snow', modules: { toolbar: [['bold','italic','underline'],['bullet','list'],['clean']] } });
                const inp = document.getElementById(f.inputId);
                q.on('text-change', () => inp.value = q.root.innerHTML);
                // Set initial value
                inp.value = q.root.innerHTML;
            });
            // Sync on submit
            modal.querySelector('form').addEventListener('submit', function() {
                fields.forEach(f => {
                    const q = Quill.find(document.getElementById(f.editorId));
                    if (q) document.getElementById(f.inputId).value = q.root.innerHTML;
                });
            });
        });
    })();
    @endforeach
});
</script>
@endpush
@endsection
