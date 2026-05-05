@extends('layouts.admin')

@section('title', 'Geography')

@section('content')
<div class="container-fluid py-4" style="background: var(--bs-body-bg); color: var(--bs-body-color);">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-0" style="letter-spacing: -0.5px; font-size: 1.75rem;">Global <span class="text-primary">Reach</span></h1>
            <p class="text-muted small mb-0">Geographic visitor distribution</p>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <form action="{{ route('admin.traffic.geo') }}" method="GET">
                <select name="days" class="modern-select" onchange="this.form.submit()">
                    <option value="1" {{ $days == 1 ? 'selected' : '' }}>Last 24 Hours</option>
                    <option value="7" {{ $days == 7 ? 'selected' : '' }}>Last 7 Days</option>
                    <option value="30" {{ $days == 30 ? 'selected' : '' }}>Last 30 Days</option>
                </select>
            </form>
        </div>
    </div>

    <div class="row g-4">
        {{-- Map --}}
        <div class="col-lg-8">
            <div class="modern-card p-0 overflow-hidden" style="min-height: 500px;">
                <div class="p-3 border-bottom">
                    <h6 class="fw-bold mb-0">Interactive Map</h6>
                </div>
                <div id="world-map" style="height: 450px; background: #f8fafc;"></div>
            </div>
        </div>

        {{-- Top Countries --}}
        <div class="col-lg-4">
            <div class="modern-card h-100 p-0 overflow-hidden">
                <div class="p-3 border-bottom">
                    <h6 class="fw-bold mb-0 text-uppercase">Top Countries</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover modern-table mb-0">
                        <thead>
                            <tr>
                                <th>COUNTRY</th>
                                <th class="text-center">VISITORS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['countries'] as $country)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://flagcdn.com/24x18/{{ strtolower($country->country_code) }}.png" class="me-2" style="border-radius: 2px;" alt="flag">
                                        <span class="fw-bold">{{ $country->country_code }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="modern-badge">{{ number_format($country->count) }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap/dist/css/jsvectormap.min.css" />
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

    .modern-badge {
        background: #f1f5f9;
        color: #475569;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.75rem;
    }
    [data-bs-theme="dark"] .modern-badge { background: #334155; color: #e2e8f0; }

    #world-map .jvm-zoom-btn {
        background: #fff;
        color: #000;
        border: 1px solid var(--modern-border);
        border-radius: 4px;
        box-shadow: var(--modern-shadow);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jsvectormap"></script>
<script src="https://cdn.jsdelivr.net/npm/jsvectormap/dist/maps/world.js"></script>
<script>
    const mapData = {!! json_encode($stats['countries']->pluck('count', 'country_code')) !!};
    
    new jsVectorMap({
        selector: "#world-map",
        map: "world",
        visualizeData: {
            scale: ['#e0f2fe', '#0369a1'],
            values: mapData
        },
        onRegionTooltipShow(event, tooltip, code) {
            const val = mapData[code] || 0;
            tooltip.text(`<strong>${tooltip.text()}</strong>: ${val} Visitors`, true);
        }
    });
</script>
@endpush
@endsection
