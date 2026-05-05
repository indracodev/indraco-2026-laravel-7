@extends('layouts.admin')

@section('title', 'Analytics Overview')

@section('content')
<div class="container-fluid py-4" style="background: var(--bs-body-bg); color: var(--bs-body-color);">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-0" style="letter-spacing: -0.5px; font-size: 1.75rem;">Traffic <span class="text-primary">Overview</span></h1>
            <p class="text-muted small mb-0">System performance and visitor analytics</p>
        </div>
        <div class="d-flex gap-2">
            <form action="{{ route('admin.traffic.index') }}" method="GET" id="filterForm">
                <select name="days" class="modern-select" onchange="this.form.submit()">
                    <option value="1" {{ $days == 1 ? 'selected' : '' }}>Last 24 Hours</option>
                    <option value="7" {{ $days == 7 ? 'selected' : '' }}>Last 7 Days</option>
                    <option value="30" {{ $days == 30 ? 'selected' : '' }}>Last 30 Days</option>
                </select>
            </form>
            <button class="btn btn-soft-danger modern-btn" data-bs-toggle="modal" data-bs-target="#purgeModal">
                Purge Data
            </button>
        </div>
    </div>

    {{-- Pulse Cards --}}
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3 mb-4">
        {{-- Total Hits --}}
        <div class="col">
            <div class="modern-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="modern-icon-box bg-primary-subtle text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                    </div>
                </div>
                <div class="text-muted small fw-medium mb-1">Total Hits</div>
                <div class="h3 fw-bold mb-0">{{ number_format($stats['total_hits']) }}</div>
            </div>
        </div>

        {{-- Unique Visitors --}}
        <div class="col">
            <div class="modern-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="modern-icon-box bg-indigo-subtle text-indigo" style="background-color: rgba(99, 102, 241, 0.1); color: #6366f1;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                </div>
                <div class="text-muted small fw-medium mb-1">Unique Visitors</div>
                <div class="h3 fw-bold mb-0">{{ number_format($stats['unique_visitors']) }}</div>
            </div>
        </div>
        <div class="col">
            <div class="modern-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="modern-icon-box bg-info-subtle text-info">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                    </div>
                </div>
                <div class="text-muted small fw-medium mb-1">Catalog Downloads</div>
                <div class="h3 fw-bold mb-0">{{ number_format($stats['catalog_downloads']) }}</div>
            </div>
        </div>

        {{-- Total Bandwidth --}}
        <div class="col">
            <div class="modern-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="modern-icon-box bg-warning-subtle text-warning">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v10"/><path d="m16 8-4 4-4-4"/><path d="M6 20h12"/></svg>
                    </div>
                </div>
                <div class="text-muted small fw-medium mb-1">Total Bandwidth</div>
                <div class="h3 fw-bold mb-0">{{ number_format($stats['total_bandwidth'] / 1024 / 1024, 1) }} <span class="fs-6 fw-normal text-muted">MB</span></div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        {{-- Traffic Flow Chart --}}
        <div class="col-lg-8">
            <div class="modern-card h-100">
                <h6 class="fw-bold border-bottom pb-2 mb-3">Traffic Flow (24h)</h6>
                <canvas id="flowChart" style="max-height: 250px;"></canvas>
            </div>
        </div>
        {{-- Status Codes --}}
        <div class="col-lg-4">
            <div class="modern-card h-100 text-center">
                <h6 class="fw-bold border-bottom pb-2 mb-3">HTTP Status</h6>
                <div class="d-flex justify-content-center align-items-center h-100">
                    <canvas id="statusChart" style="max-height: 180px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Top Pages --}}
        <div class="col-lg-5">
            <div class="modern-card p-0 overflow-hidden h-100">
                <div class="p-3 border-bottom">
                    <h6 class="fw-bold mb-0">Popular Content</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover modern-table mb-0">
                        <thead>
                            <tr>
                                <th>PATH</th>
                                <th class="text-center">HITS</th>
                                <th class="text-center">VISITORS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['top_pages'] as $page)
                            <tr>
                                <td><span class="modern-badge">{{ $page->path }}</span></td>
                                <td class="text-center fw-bold">{{ $page->hits }}</td>
                                <td class="text-center">{{ $page->visitors }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Live Feed --}}
        <div class="col-lg-7">
            <div class="modern-card p-0 overflow-hidden">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">Live Stream</h6>
                    <div class="modern-status-badge">LIVE</div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover modern-table mb-0">
                        <thead>
                            <tr>
                                <th>TIME</th>
                                <th>ORIGIN</th>
                                <th>TECH</th>
                                <th>PATH</th>
                                <th>REFERER</th>
                                <th class="text-center">RESP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['recent_visits'] as $visit)
                            <tr>
                                <td class="small text-muted">{{ $visit->created_at->format('H:i:s') }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($visit->country_code)
                                            <img src="https://flagcdn.com/24x18/{{ strtolower($visit->country_code) }}.png" class="me-2" style="border-radius: 2px;" alt="flag">
                                        @endif
                                        <span class="small fw-bold">{{ $visit->ip_address }}</span>
                                    </div>
                                </td>
                                <td class="small">{{ $visit->os }} / {{ $visit->browser }}</td>
                                <td><span class="small opacity-75">{{ $visit->path }}</span></td>
                                <td>
                                    <span class="small text-muted" title="{{ $visit->referer }}">
                                        {{ $visit->referer ? Str::limit(str_replace(['http://', 'https://'], '', $visit->referer), 30) : '-' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="modern-resp-badge {{ $visit->status_code >= 400 ? 'bg-danger' : ($visit->status_code >= 300 ? 'bg-warning' : 'bg-success') }}">
                                        {{ $visit->status_code }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3 border-top">
                    {{ $stats['recent_visits']->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Purge Modal --}}
<div class="modal fade" id="purgeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modern-modal">
            <form action="{{ route('admin.traffic.purge') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Purge Analytics Data</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted small mb-2">Select retention period:</p>
                    <select name="days" class="modern-select w-100">
                        <option value="30">Keep Last 30 Days</option>
                        <option value="7">Keep Last 7 Days</option>
                        <option value="1">Keep Last 24 Hours</option>
                        <option value="0">Delete Everything</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="modern-btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="modern-btn btn-danger">Confirm Purge</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    :root {
        --modern-bg: #f8fafc;
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
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.2);
    }
    .modern-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.08);
    }

    .modern-blue { background: #f0f9ff !important; color: #0369a1 !important; }
    .modern-indigo { background: #f5f3ff !important; color: #4338ca !important; }
    .modern-yellow { background: #fffbeb !important; color: #b45309 !important; }
    .modern-green { background: #f0fdf4 !important; color: #15803d !important; }

    [data-bs-theme="dark"] .modern-blue { background: rgba(3,105,161,0.2) !important; color: #7dd3fc !important; border: 1px solid rgba(3,105,161,0.3) !important; }
    [data-bs-theme="dark"] .modern-indigo { background: rgba(67,56,202,0.2) !important; color: #a5b4fc !important; border: 1px solid rgba(67,56,202,0.3) !important; }
    [data-bs-theme="dark"] .modern-yellow { background: rgba(180,83,9,0.2) !important; color: #fcd34d !important; border: 1px solid rgba(180,83,9,0.3) !important; }
    [data-bs-theme="dark"] .modern-green { background: rgba(21,128,61,0.2) !important; color: #86efac !important; border: 1px solid rgba(21,128,61,0.3) !important; }

    .modern-label {
        font-size: 0.75rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
        color: #64748b;
    }
    [data-bs-theme="dark"] .modern-label { color: #94a3b8; }

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

    .modern-btn {
        font-weight: 600;
        padding: 0.4rem 1rem;
        font-size: 0.85rem;
        border-radius: var(--btn-radius);
        transition: all 0.2s;
    }
    .btn-soft-danger {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fee2e2;
    }
    .btn-soft-danger:hover { background: #fee2e2; color: #b91c1c; }

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

    .modern-status-badge {
        background: #dcfce7;
        color: #166534;
        padding: 0.3rem 0.75rem;
        font-weight: 700;
        border-radius: var(--btn-radius);
        font-size: 0.85rem;
    }
    [data-bs-theme="dark"] .modern-status-badge {
        background: rgba(22, 101, 52, 0.2);
        color: #86efac;
    }

    .modern-resp-badge {
        display: inline-block;
        min-width: 36px;
        padding: 2px 8px;
        font-weight: 700;
        font-size: 0.7rem;
        line-height: 1.4;
        color: #fff;
        border-radius: 50rem; /* Pill shape */
        text-align: center;
        letter-spacing: 0.025em;
    }

    .brutal-flag { border: 1px solid #000; border-radius: 2px; }

    .modern-modal {
        border: 1px solid var(--modern-border);
        border-radius: var(--card-radius);
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
    }

    .pagination .page-item .page-link {
        border: 1px solid var(--modern-border);
        color: #64748b;
        font-weight: 600;
        margin: 0 2px;
        border-radius: 6px;
        font-size: 0.85rem;
        padding: 0.4rem 0.8rem;
    }
    .pagination .page-item.active .page-link {
        background: #38bdf8;
        border-color: #38bdf8;
        color: #fff;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    Chart.defaults.color = '#565656';
    Chart.defaults.font.weight = 'bold';
    Chart.defaults.font.family = 'monospace';

    // Traffic Flow Chart
    const ctxFlow = document.getElementById('flowChart').getContext('2d');
    new Chart(ctxFlow, {
        type: 'line',
        data: {
            labels: {!! json_encode($stats['traffic_flow_labels']) !!},
            datasets: [{
                label: 'Hits',
                data: {!! json_encode($stats['traffic_flow_values']) !!},
                borderColor: '#000',
                backgroundColor: '#38bdf8',
                borderWidth: 4,
                fill: false,
                tension: 0,
                pointRadius: 6,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#000',
                pointBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            plugins: { 
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        title: function(context) {
                            const label = context[0].label;
                            const now = new Date();
                            const yesterday = new Date();
                            yesterday.setDate(now.getDate() - 1);
                            
                            const hour = parseInt(label.split(':')[0]);
                            const currentHour = now.getHours();
                            
                            // Logika: Jika jam label > jam sekarang, berarti itu data jam kemarin
                            const dateStr = (hour > currentHour) ? 
                                yesterday.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' }) : 
                                now.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
                            
                            return dateStr + " - Jam " + label;
                        }
                    }
                }
            },
            scales: {
                y: { 
                    grid: { color: 'rgba(0,0,0,0.1)' }, 
                    border: { width: 3, color: '#000' },
                    beginAtZero: true
                },
                x: { 
                    grid: { display: false }, 
                    border: { width: 3, color: '#000' },
                    ticks: {
                        callback: function(val, index) {
                            const label = this.getLabelForValue(val);
                            if (label === '00:00') {
                                const now = new Date();
                                return label + " (" + now.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' }) + ")";
                            }
                            return label;
                        },
                        maxRotation: 0,
                        autoSkip: true,
                        maxTicksLimit: 12
                    }
                }
            }
        }
    });

    // Status Chart
    const ctxStatus = document.getElementById('statusChart').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($stats['status_codes']->pluck('status_code')) !!},
            datasets: [{
                data: {!! json_encode($stats['status_codes']->pluck('count')) !!},
                backgroundColor: ['#10b981', '#fbbf24', '#f87171', '#818cf8'],
                borderColor: '#000',
                borderWidth: 3
            }]
        },
        options: {
            cutout: '60%',
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 15, padding: 20 } }
            }
        }
    });
</script>
@endpush
@endsection
