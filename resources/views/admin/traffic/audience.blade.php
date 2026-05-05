@extends('layouts.admin')

@section('title', 'Audience & Tech')

@section('content')
<div class="container-fluid py-4" style="background: var(--bs-body-bg); color: var(--bs-body-color);">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-0" style="letter-spacing: -0.5px; font-size: 1.75rem;">Audience <span class="text-primary">& Tech</span></h1>
            <p class="text-muted small mb-0">Visitor composition and device capabilities</p>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <form action="{{ route('admin.traffic.audience') }}" method="GET">
                <select name="days" class="modern-select" onchange="this.form.submit()">
                    <option value="1" {{ $days == 1 ? 'selected' : '' }}>Last 24 Hours</option>
                    <option value="7" {{ $days == 7 ? 'selected' : '' }}>Last 7 Days</option>
                    <option value="30" {{ $days == 30 ? 'selected' : '' }}>Last 30 Days</option>
                </select>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-4">
        {{-- Device Distribution --}}
        <div class="col-lg-4">
            <div class="modern-card h-100">
                <h6 class="fw-bold border-bottom pb-2 mb-3">Device Types</h6>
                <div style="height: 300px; position: relative;">
                    <canvas id="deviceChart"></canvas>
                </div>
            </div>
        </div>
        {{-- Browser Distribution --}}
        <div class="col-lg-4">
            <div class="modern-card h-100">
                <h6 class="fw-bold border-bottom pb-2 mb-3">Browsers</h6>
                <div style="height: 300px; position: relative;">
                    <canvas id="browserChart"></canvas>
                </div>
            </div>
        </div>
        {{-- OS Distribution --}}
        <div class="col-lg-4">
            <div class="modern-card h-100">
                <h6 class="fw-bold border-bottom pb-2 mb-3">Operating Systems</h6>
                <div style="height: 300px; position: relative;">
                    <canvas id="osChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Bandwidth per Device --}}
        <div class="col-lg-12">
            <div class="modern-card">
                <h6 class="fw-bold border-bottom pb-2 mb-3">Bandwidth Consumption</h6>
                <div class="table-responsive">
                    <table class="table table-hover modern-table mb-0">
                        <thead>
                            <tr>
                                <th>DEVICE TYPE</th>
                                <th class="text-center">DATA TRANSFER</th>
                                <th class="text-center">INTENSITY</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalSize = $stats['bandwidth_by_device']->sum('size'); @endphp
                            @foreach ($stats['bandwidth_by_device'] as $item)
                            <tr>
                                <td>
                                    <span class="modern-badge me-2">{{ $item->name }}</span>
                                    <span class="small text-muted fw-bold">VISITS LOGGED</span>
                                </td>
                                <td class="text-center fw-bold">{{ number_format($item->size / 1048576, 2) }} MB</td>
                                <td style="width: 40%;">
                                    <div class="progress modern-progress">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $totalSize > 0 ? ($item->size / $totalSize) * 100 : 0 }}%"></div>
                                    </div>
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
    .modern-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.08);
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

    .modern-progress {
        height: 8px;
        background: #f1f5f9;
        border-radius: 4px;
        overflow: hidden;
    }
    [data-bs-theme="dark"] .modern-progress { background: #0f172a; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    Chart.defaults.color = '#64748b';
    Chart.defaults.font.family = "'Inter', 'Segoe UI', sans-serif";

    const chartOptions = {
        plugins: {
            legend: { 
                position: 'bottom', 
                labels: { 
                    boxWidth: 10, 
                    usePointStyle: true,
                    padding: 20, 
                    font: { size: 11, weight: '500' } 
                } 
            }
        },
        cutout: '75%',
        responsive: true,
        maintainAspectRatio: false
    };

    // Device Chart
    new Chart(document.getElementById('deviceChart'), {
        type: 'pie',
        data: {
            labels: {!! json_encode($stats['devices']->pluck('name')) !!},
            datasets: [{
                data: {!! json_encode($stats['devices']->pluck('count')) !!},
                backgroundColor: ['#38bdf8', '#818cf8', '#fbbf24'],
                borderWidth: 0
            }]
        },
        options: chartOptions
    });

    // Browser Chart
    new Chart(document.getElementById('browserChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($stats['browsers']->pluck('name')) !!},
            datasets: [{
                data: {!! json_encode($stats['browsers']->pluck('count')) !!},
                backgroundColor: ['#10b981', '#fbbf24', '#f87171', '#38bdf8', '#818cf8'],
                borderWidth: 0
            }]
        },
        options: chartOptions
    });

    // OS Chart
    new Chart(document.getElementById('osChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($stats['os']->pluck('name')) !!},
            datasets: [{
                label: 'Visitors',
                data: {!! json_encode($stats['os']->pluck('count')) !!},
                backgroundColor: 'rgba(99, 102, 241, 0.8)',
                borderRadius: 4,
                borderWidth: 0
            }]
        },
        options: {
            ...chartOptions,
            scales: {
                y: { 
                    grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false },
                    ticks: { font: { size: 10 } }
                },
                x: { 
                    grid: { display: false },
                    ticks: { font: { size: 10 } }
                }
            }
        }
    });
</script>
@endpush
@endsection
