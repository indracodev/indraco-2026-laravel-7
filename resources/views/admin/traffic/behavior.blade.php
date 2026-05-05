@extends('layouts.admin')

@section('title', 'Behavior & UX')

@section('content')
<div class="container-fluid py-4" style="background: var(--bs-body-bg); color: var(--bs-body-color);">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-0" style="letter-spacing: -0.5px; font-size: 1.75rem;">Behavior <span class="text-primary">& Interaction</span></h1>
            <p class="text-muted small mb-0">User engagement & pathways</p>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <form action="{{ route('admin.traffic.behavior') }}" method="GET">
                <select name="days" class="modern-select" onchange="this.form.submit()">
                    <option value="1" {{ $days == 1 ? 'selected' : '' }}>Last 24 Hours</option>
                    <option value="7" {{ $days == 7 ? 'selected' : '' }}>Last 7 Days</option>
                    <option value="30" {{ $days == 30 ? 'selected' : '' }}>Last 30 Days</option>
                </select>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-4">
        {{-- Avg Scroll Depth --}}
        <div class="col-md-6">
            <div class="modern-card d-flex align-items-center py-3" style="border-left: 4px solid #fbbf24;">
                <div class="modern-icon-box me-3" style="background: rgba(251, 191, 36, 0.1); color: #b45309;">
                    <i class="bi bi-activity h4 mb-0"></i>
                </div>
                <div>
                    <h6 class="modern-label mb-0">Avg Scroll Depth</h6>
                    <h3 class="fw-bold mb-0">{{ $stats['avg_scroll'] }}%</h3>
                </div>
            </div>
        </div>
        {{-- Bounce Rate --}}
        <div class="col-md-6">
            <div class="modern-card d-flex align-items-center py-3" style="border-left: 4px solid #6366f1;">
                <div class="modern-icon-box me-3" style="background: rgba(99, 102, 241, 0.1); color: #4338ca;">
                    <i class="bi bi-door-open h4 mb-0"></i>
                </div>
                <div>
                    <h6 class="modern-label mb-0">Bounce Rate</h6>
                    <h3 class="fw-bold mb-0">{{ $stats['bounce_rate'] }}%</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- Interaction Heatmap --}}
        <div class="col-md-7">
            <div class="modern-card p-0 overflow-hidden h-100">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-uppercase">Interaction Heatmap (Click Events)</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover modern-table mb-0">
                        <thead>
                            <tr>
                                <th>ELEMENT</th>
                                <th>PAGE PATH</th>
                                <th class="text-center">CLICKS</th>
                                <th style="width: 20%;">INTENSITY</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $maxClicks = $stats['top_clicks']->max('count') ?: 1; @endphp
                            @foreach ($stats['top_clicks'] as $click)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $click->element_text ?: 'Unnamed Element' }}</div>
                                    <span class="modern-badge-small">{{ $click->element_id ? '#' . $click->element_id : 'no-id' }}</span>
                                </td>
                                <td><code class="text-primary fw-bold">{{ $click->page_path }}</code></td>
                                <td class="text-center fw-bold">{{ number_format($click->count) }}</td>
                                <td>
                                    <div class="modern-progress-container">
                                        <div class="modern-progress-bar" style="width: {{ ($click->count / $maxClicks) * 100 }}%"></div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Catalog Downloads --}}
        <div class="col-md-5">
            <div class="modern-card p-0 overflow-hidden h-100">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-info-subtle text-info">
                    <h6 class="fw-bold mb-0 text-uppercase"><i class="bi bi-download me-2"></i>Catalog Downloads</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover modern-table mb-0">
                        <thead>
                            <tr>
                                <th>CATALOG NAME</th>
                                <th class="text-center">COUNT</th>
                                <th style="width: 30%;">POPULARITY</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $maxDownloads = $stats['catalog_downloads']->max('count') ?: 1; @endphp
                            @foreach ($stats['catalog_downloads'] as $download)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $download->name }}</div>
                                </td>
                                <td class="text-center fw-bold">{{ number_format($download->count) }}</td>
                                <td>
                                    <div class="modern-progress-container">
                                        <div class="modern-progress-bar bg-info" style="width: {{ ($download->count / $maxDownloads) * 100 }}%"></div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @if($stats['catalog_downloads']->isEmpty())
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted small italic">No downloads tracked yet</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    :root {
        --modern-border: rgba(0,0,0,0.06);
        --modern-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
        --modern-accent: #38bdf8;
        --card-radius: 12px;
        --btn-radius: 8px;
    }

    .fw-black { font-weight: 700; }
    .text-accent { color: var(--modern-accent); }
    .bg-accent { background-color: var(--modern-accent) !important; }

    .modern-card {
        background: #ffffff;
        border: 1px solid var(--modern-border);
        box-shadow: var(--modern-shadow);
        border-radius: var(--card-radius);
        padding: 1.25rem;
        color: #1e293b;
        transition: all 0.2s ease;
    }
    [data-bs-theme="dark"] .modern-card {
        background: #1e293b;
        color: #f1f2f2;
        border-color: rgba(255,255,255,0.05);
    }

    .modern-icon-box {
        padding: 0.6rem;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modern-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        font-weight: 700;
        color: #64748b;
        margin-bottom: 2px;
    }

    .modern-progress-container {
        height: 6px;
        background: #f1f5f9;
        border-radius: 10px;
        overflow: hidden;
    }
    [data-bs-theme="dark"] .modern-progress-container { background: #0f172a; }
    
    .modern-progress-bar {
        height: 100%;
        background: var(--modern-accent);
        border-radius: 10px;
    }

    .modern-select {
        border: 1px solid var(--modern-border);
        background: #fff;
        font-weight: 500;
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
        border-radius: var(--btn-radius);
        cursor: pointer;
    }
    [data-bs-theme="dark"] .modern-select { background: #0f172a; color: #fff; }

    .modern-table thead th {
        background: #f8fafc;
        color: #64748b;
        border-bottom: 1px solid var(--modern-border);
        text-transform: uppercase;
        font-weight: 700;
        font-size: 0.65rem;
        padding: 0.75rem 1rem;
    }
    [data-bs-theme="dark"] .modern-table thead th { background: #0f172a; color: #94a3b8; }

    .modern-table td {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid var(--modern-border);
        vertical-align: middle;
        font-size: 0.9rem;
    }

    .modern-badge-small {
        font-size: 0.65rem;
        background: #f1f5f9;
        color: #64748b;
        padding: 0.1rem 0.4rem;
        border-radius: 4px;
        font-family: monospace;
    }
    [data-bs-theme="dark"] .modern-badge-small { background: #334155; color: #cbd5e1; }
</style>
@endpush
@endsection
