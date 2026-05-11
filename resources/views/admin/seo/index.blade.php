@extends('layouts.admin')

@section('title', 'Global SEO Management - Admin Panel')
@section('page_title', 'Global SEO Management')

@push('styles')
<style>
    /* Premium SEO Table Styles */
    .seo-header-title {
        font-weight: 700;
        font-size: 1.75rem;
        color: #111;
        margin-bottom: 0.25rem;
    }
    .seo-header-subtitle {
        color: #666;
        font-size: 0.95rem;
        margin-bottom: 2rem;
    }
    .seo-table {
        border-radius: 8px;
        overflow: hidden;
        border: 1.5px solid #000;
        background: #fff;
    }
    .seo-table thead th {
        background-color: #000;
        color: #fff;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding: 1rem;
        border: none;
        vertical-align: middle;
    }
    .seo-table tbody td {
        padding: 1.25rem 1rem;
        border-bottom: 1px solid #eee;
        vertical-align: middle;
        font-size: 0.9rem;
    }
    .seo-page-name {
        color: #0d6efd;
        font-weight: 700;
        text-decoration: none;
        font-size: 1rem;
    }
    .seo-page-name:hover {
        text-decoration: underline;
    }
    .seo-identifier {
        color: #666;
        font-family: 'Courier New', Courier, monospace;
        font-size: 0.8rem;
    }
    .seo-meta-title {
        font-weight: 700;
        color: #222;
    }
    .seo-meta-default {
        color: #888;
        font-style: italic;
        font-weight: 400;
    }
    .badge-seo-status {
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.5rem 0.8rem;
        border-radius: 4px;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        border: 1px solid rgba(0,0,0,0.1);
    }
    .badge-configured {
        background-color: #d1e7dd;
        color: #0f5132;
        border-color: #badbcc;
    }
    .badge-default {
        background-color: #6c757d;
        color: #fff;
    }
    .btn-edit-seo {
        background-color: #3b5bdb;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 0.5rem 1rem;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }
    .btn-edit-seo:hover {
        background-color: #2b45b5;
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(59, 91, 219, 0.25);
    }

    /* Tabs Styling */
    .seo-tabs .nav-link {
        color: #000;
        font-weight: 700;
        border: 1.5px solid #000;
        margin-right: 5px;
        border-bottom: none;
        background: #f8f9fa;
        padding: 0.75rem 1.5rem;
    }
    .seo-tabs .nav-link.active {
        background: #000 !important;
        color: #fff !important;
        border-color: #000;
    }

    /* Edit Form Styles */
    .edit-seo-card {
        border: 1.5px solid #000;
        border-radius: 12px;
        overflow: hidden;
    }
    .edit-seo-header {
        background: #000;
        color: #fff;
        padding: 1.25rem 1.5rem;
    }
    .serp-preview {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 1.25rem;
        background: #fff;
        margin-bottom: 1.5rem;
    }
    .serp-title { color: #1a0dab; font-size: 1.15rem; margin-bottom: 2px; }
    .serp-url { color: #006621; font-size: 0.85rem; margin-bottom: 4px; }
    .serp-desc { color: #545454; font-size: 0.85rem; line-height: 1.5; }
    .char-counter { font-size: 0.75rem; font-weight: 600; }
</style>
@endpush

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@php
    $editPageKey = request('edit');
    $editData = $editPageKey ? ($seoData[$editPageKey] ?? null) : null;
    $editLabel = '';
    if ($editPageKey) {
        foreach($tableData as $row) {
            if ($row->key == $editPageKey) {
                $editLabel = $row->label;
                break;
            }
        }
    }
@endphp

@if($editPageKey && $editData)
    {{-- ====== EDIT VIEW ====== --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('admin.seo.index') }}">SEO Management</a></li>
                    <li class="breadcrumb-item active">Edit SEO</li>
                </ol>
            </nav>
            <h2 class="seo-header-title">Edit SEO: {{ $editLabel }}</h2>
        </div>
        <a href="{{ route('admin.seo.index') }}" class="btn btn-outline-dark">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="card edit-seo-card border-0 shadow-lg">
        <div class="edit-seo-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Konfigurasi SEO Halaman</h5>
            <span class="badge bg-light text-dark font-monospace">{{ $editPageKey }}</span>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.seo.update', $editPageKey) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-lg-7">
                        {{-- Meta Title --}}
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-end mb-2">
                                <label class="form-label fw-bold mb-0">Meta Title</label>
                                <span class="char-counter text-muted" id="cc-title">0/60</span>
                            </div>
                            <input type="text" name="title" id="input-title" class="form-control form-control-lg" 
                                   value="{{ $editData['title'] }}" placeholder="Masukkan meta title yang menarik..."
                                   maxlength="100">
                            <div class="form-text mt-2">Muncul sebagai judul biru di hasil pencarian Google. Idealnya 50-60 karakter.</div>
                        </div>

                        {{-- Meta Description --}}
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-end mb-2">
                                <label class="form-label fw-bold mb-0">Meta Description</label>
                                <span class="char-counter text-muted" id="cc-desc">0/160</span>
                            </div>
                            <textarea name="description" id="input-desc" class="form-control" rows="4" 
                                      placeholder="Deskripsikan konten halaman ini secara singkat dan padat...">{{ $editData['description'] }}</textarea>
                            <div class="form-text mt-2">Ringkasan yang muncul di bawah judul pada hasil pencarian. Idealnya 120-160 karakter.</div>
                        </div>

                        {{-- Meta Keywords --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Meta Keywords (Opsional)</label>
                            <input type="text" name="keywords" class="form-control" value="{{ $editData['keywords'] }}" 
                                   placeholder="indraco, kopi indonesia, fmcg surabaya, ...">
                            <div class="form-text mt-2">Pisahkan dengan koma. Meskipun kurang berpengaruh pada Google, mesin pencari lain tetap menggunakannya.</div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        {{-- Google Preview --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold mb-2"><i class="bi bi-google me-1"></i> Google Search Preview</label>
                            <div class="serp-preview shadow-sm">
                                <div class="serp-url">{{ request()->getHost() }} › {{ str_replace('_', '-', $editPageKey) }}</div>
                                <div class="serp-title" id="preview-title">{{ $editData['title'] ?: ($editLabel . ' - INDRACO') }}</div>
                                <div class="serp-desc" id="preview-desc">{{ $editData['description'] ?: 'Tulis meta description untuk melihat preview tampilan di Google...' }}</div>
                            </div>
                        </div>

                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3"><i class="bi bi-share me-2"></i>Open Graph Settings</h6>
                                
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">OG Title</label>
                                    <input type="text" name="og_title" class="form-control form-control-sm" value="{{ $editData['og_title'] }}" placeholder="Judul share media sosial...">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">OG Image URL</label>
                                    <input type="text" name="og_image" class="form-control form-control-sm" value="{{ $editData['og_image'] }}" placeholder="https://...">
                                </div>
                                <div class="mb-0">
                                    <label class="form-label small fw-bold">Canonical URL</label>
                                    <input type="text" name="canonical" class="form-control form-control-sm" value="{{ $editData['canonical'] }}" placeholder="URL asli jika ada duplikat...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        <i class="bi bi-clock-history me-1"></i> Terakhir diperbarui: {{ now()->format('d M Y H:i') }}
                    </div>
                    <div class="d-flex gap-2">
                        <button type="reset" class="btn btn-light border">Reset</button>
                        <button type="submit" class="btn btn-dark px-4 py-2 fw-bold">
                            <i class="bi bi-save me-2"></i>Simpan Perubahan SEO
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@else
    {{-- ====== LIST VIEW ====== --}}
    <div class="mb-4">
        <h2 class="seo-header-title"><i class="bi bi-google me-2 text-primary"></i>Global SEO Management</h2>
        <p class="seo-header-subtitle">Optimize search engine visibility for your main website pages.</p>
    </div>

    <!-- Type Filter Tabs -->
    <ul class="nav nav-tabs seo-tabs border-0 mb-0" id="seoTypeTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pages-tab" data-bs-toggle="tab" data-bs-target="#pages-content" type="button" role="tab">Main Pages</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="products-tab" data-bs-toggle="tab" data-bs-target="#products-content" type="button" role="tab">Products</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="news-tab" data-bs-toggle="tab" data-bs-target="#news-content" type="button" role="tab">News</button>
        </li>
    </ul>

    <div class="tab-content" id="seoTypeTabsContent">
        <!-- Pages Tab -->
        <div class="tab-pane fade show active" id="pages-content" role="tabpanel">
            <div class="seo-table shadow-sm">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width: 20%">Page Name</th>
                            <th style="width: 25%">Path</th>
                            <th style="width: 25%">Meta Title</th>
                            <th style="width: 15%" class="text-center">Status</th>
                            <th style="width: 15%" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tableData as $row)
                            @if($row->type === 'page')
                            <tr>
                                <td><a href="{{ route('admin.seo.index', ['edit' => $row->key]) }}" class="seo-page-name">{{ $row->label }}</a></td>
                                <td><span class="seo-identifier">{{ $row->path }}</span></td>
                                <td>{!! $row->meta_title ? '<span class="seo-meta-title">'.$row->meta_title.'</span>' : '<span class="seo-meta-default">Using default title</span>' !!}</td>
                                <td class="text-center">
                                    <span class="badge-seo-status {{ $row->status === 'Configured' ? 'badge-configured' : 'badge-default' }}">
                                        {!! $row->status === 'Configured' ? '<i class="bi bi-check-circle-fill"></i>' : '' !!} {{ $row->status }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.seo.index', ['edit' => $row->key]) }}" class="btn-edit-seo"><i class="bi bi-pencil-square"></i> Edit SEO</a>
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Products Tab -->
        <div class="tab-pane fade" id="products-content" role="tabpanel">
            <div class="seo-table shadow-sm">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width: 20%">Product Name</th>
                            <th style="width: 25%">Path</th>
                            <th style="width: 25%">Meta Title</th>
                            <th style="width: 15%" class="text-center">Status</th>
                            <th style="width: 15%" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tableData as $row)
                            @if($row->type === 'product')
                            <tr>
                                <td><a href="{{ route('admin.seo.index', ['edit' => $row->key]) }}" class="seo-page-name">{{ $row->label }}</a></td>
                                <td><span class="seo-identifier">{{ $row->path }}</span></td>
                                <td>{!! $row->meta_title ? '<span class="seo-meta-title">'.$row->meta_title.'</span>' : '<span class="seo-meta-default">Using default title</span>' !!}</td>
                                <td class="text-center">
                                    <span class="badge-seo-status {{ $row->status === 'Configured' ? 'badge-configured' : 'badge-default' }}">
                                        {!! $row->status === 'Configured' ? '<i class="bi bi-check-circle-fill"></i>' : '' !!} {{ $row->status }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.seo.index', ['edit' => $row->key]) }}" class="btn-edit-seo"><i class="bi bi-pencil-square"></i> Edit SEO</a>
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- News Tab -->
        <div class="tab-pane fade" id="news-content" role="tabpanel">
            <div class="seo-table shadow-sm">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width: 20%">News Title</th>
                            <th style="width: 25%">Path</th>
                            <th style="width: 25%">Meta Title</th>
                            <th style="width: 15%" class="text-center">Status</th>
                            <th style="width: 15%" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tableData as $row)
                            @if($row->type === 'news')
                            <tr>
                                <td><a href="{{ route('admin.seo.index', ['edit' => $row->key]) }}" class="seo-page-name">{{ $row->label }}</a></td>
                                <td><span class="seo-identifier">{{ $row->path }}</span></td>
                                <td>{!! $row->meta_title ? '<span class="seo-meta-title">'.$row->meta_title.'</span>' : '<span class="seo-meta-default">Using default title</span>' !!}</td>
                                <td class="text-center">
                                    <span class="badge-seo-status {{ $row->status === 'Configured' ? 'badge-configured' : 'badge-default' }}">
                                        {!! $row->status === 'Configured' ? '<i class="bi bi-check-circle-fill"></i>' : '' !!} {{ $row->status }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.seo.index', ['edit' => $row->key]) }}" class="btn-edit-seo"><i class="bi bi-pencil-square"></i> Edit SEO</a>
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4 p-4 rounded bg-white border shadow-sm">
        <h6 class="fw-bold mb-3">Informasi Penting:</h6>
        <ul class="mb-0 small text-muted">
            <li class="mb-2"><strong>Meta Title:</strong> Sangat krusial untuk SEO. Pastikan mengandung kata kunci utama di awal judul.</li>
            <li class="mb-2"><strong>Meta Description:</strong> Tidak mempengaruhi ranking secara langsung, tetapi mempengaruhi CTR (Click-Through Rate).</li>
            <li><strong>Sub-pages:</strong> Semua produk dan berita otomatis muncul di sini. Jika Meta Title kosong, sistem akan menggunakan judul default dari konten tersebut.</li>
        </ul>
    </div>
@endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputTitle = document.getElementById('input-title');
    const inputDesc = document.getElementById('input-desc');
    const previewTitle = document.getElementById('preview-title');
    const previewDesc = document.getElementById('preview-desc');
    const ccTitle = document.getElementById('cc-title');
    const ccDesc = document.getElementById('cc-desc');

    if (inputTitle) {
        function updateTitle() {
            const val = inputTitle.value;
            const len = val.length;
            previewTitle.textContent = val || '{{ $editLabel }} - INDRACO';
            ccTitle.textContent = len + '/60';
            
            if (len > 60) ccTitle.className = 'char-counter text-danger';
            else if (len >= 50) ccTitle.className = 'char-counter text-success';
            else ccTitle.className = 'char-counter text-muted';
        }
        inputTitle.addEventListener('input', updateTitle);
        updateTitle();
    }

    if (inputDesc) {
        function updateDesc() {
            const val = inputDesc.value;
            const len = val.length;
            previewDesc.textContent = val || 'Tulis meta description untuk melihat preview tampilan di Google...';
            ccDesc.textContent = len + '/160';
            
            if (len > 160) ccDesc.className = 'char-counter text-danger';
            else if (len >= 120) ccDesc.className = 'char-counter text-success';
            else ccDesc.className = 'char-counter text-muted';
        }
        inputDesc.addEventListener('input', updateDesc);
        updateDesc();
    }
});
</script>
@endpush
