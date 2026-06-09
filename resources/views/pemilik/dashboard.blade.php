@extends('layouts.app')

@section('title', 'Dashboard Pemilik')

@section('content')
<div class="container-fluid px-4 py-4">

    {{-- Page Header --}}
    <div class="page-header d-flex align-items-center justify-content-between">
        <div>
            <h4><i class="bi bi-speedometer2 me-2"></i>Dashboard Pemilik</h4>
            <p>Selamat datang kembali, <strong>{{ auth()->user()->name }}</strong></p>
        </div>
        <a href="{{ route('pemilik.sales-report') }}" class="btn btn-primary btn-sm d-none d-md-flex">
            <i class="bi bi-bar-chart-line"></i> Lihat Laporan Lengkap
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="card stat-card h-100" style="background: linear-gradient(135deg, #4A90A4 0%, #2C5F72 100%);">
                <div class="card-body p-4">
                    <div class="icon-wrap"><i class="bi bi-cash-stack"></i></div>
                    <div class="stat-label">Total Pendapatan</div>
                    <div class="stat-value" style="font-size:1.1rem">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card stat-card h-100" style="background: linear-gradient(135deg, #2C5F72 0%, #1A3A47 100%);">
                <div class="card-body p-4">
                    <div class="icon-wrap"><i class="bi bi-bag-check-fill"></i></div>
                    <div class="stat-label">Total Pesanan</div>
                    <div class="stat-value">{{ $totalOrders ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card stat-card h-100" style="background: linear-gradient(135deg, #87CEEB 0%, #4A90A4 100%);">
                <div class="card-body p-4">
                    <div class="icon-wrap"><i class="bi bi-people-fill"></i></div>
                    <div class="stat-label">Total Pelanggan</div>
                    <div class="stat-value">{{ $totalCustomers ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card stat-card h-100" style="background: linear-gradient(135deg, #5BA3C1 0%, #2C5F72 100%);">
                <div class="card-body p-4">
                    <div class="icon-wrap"><i class="bi bi-fish"></i></div>
                    <div class="stat-label">Total Produk</div>
                    <div class="stat-value">{{ $totalProduk ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        {{-- Status Pesanan --}}
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header">
                    <i class="bi bi-pie-chart-fill me-2"></i>Status Pesanan
                </div>
                <div class="card-body">
                    @php
                        $statusItems = [
                            ['label'=>'Pending',      'val'=>$orderStatus['pending'] ?? 0,      'cls'=>'bg-warning',  'icon'=>'bi-hourglass-split'],
                            ['label'=>'Dikonfirmasi', 'val'=>$orderStatus['dikonfirmasi'] ?? 0, 'cls'=>'bg-info',     'icon'=>'bi-check-circle'],
                            ['label'=>'Selesai',      'val'=>$orderStatus['selesai'] ?? 0,      'cls'=>'bg-success',  'icon'=>'bi-check-all'],
                        ];
                    @endphp
                    @foreach($statusItems as $item)
                    <div class="d-flex align-items-center justify-content-between p-3 rounded-3 mb-2"
                         style="background:var(--primary-pale)">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi {{ $item['icon'] }} text-primary"></i>
                            <span class="fw-600" style="font-size:.875rem;font-weight:600">{{ $item['label'] }}</span>
                        </div>
                        <span class="badge {{ $item['cls'] }} rounded-pill px-3">{{ $item['val'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Pendapatan 30 Hari --}}
        <div class="col-md-8">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span><i class="bi bi-calendar3 me-2"></i>Rekap Penjualan Lunas – 30 Hari Terakhir</span>
                    <small class="text-muted" style="font-size:.75rem">
                        {{ \Carbon\Carbon::now()->subDays(30)->format('d M Y') }} –
                        {{ \Carbon\Carbon::now()->format('d M Y') }}
                    </small>
                </div>
                <div class="card-body p-4">
                    {{-- Summary Row --}}
                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3" style="background:var(--primary-pale)">
                                <p class="text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Total Pendapatan</p>
                                <h4 class="fw-800 mb-0" style="color:#10B981;font-weight:800">
                                    Rp {{ number_format($revenueLastMonth ?? 0, 0, ',', '.') }}
                                </h4>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3" style="background:var(--primary-pale)">
                                <p class="text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Jumlah Transaksi Lunas</p>
                                <h4 class="fw-800 mb-0" style="color:var(--primary-dark);font-weight:800">
                                    {{ $countLastMonth ?? 0 }} <small style="font-size:.85rem;font-weight:500">transaksi</small>
                                </h4>
                            </div>
                        </div>
                    </div>

                    {{-- Mini Table Transaksi Lunas Terbaru --}}
                    @if(($recentLunas30 ?? collect())->count() > 0)
                    <div class="table-responsive" style="max-height:180px;overflow-y:auto">
                        <table class="table table-sm mb-0" style="font-size:.8rem">
                            <thead style="position:sticky;top:0;background:#fff;z-index:1">
                                <tr>
                                    <th style="font-size:.72rem">No. Pesanan</th>
                                    <th style="font-size:.72rem">Pelanggan</th>
                                    <th style="font-size:.72rem">Tgl Bayar</th>
                                    <th style="font-size:.72rem" class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentLunas30 as $lunas)
                                <tr>
                                    <td><span class="fw-bold" style="color:var(--primary)">{{ $lunas->nomor_pesanan ?? '#'.$lunas->id }}</span></td>
                                    <td>{{ $lunas->customer->name ?? 'Anonim' }}</td>
                                    <td class="text-muted">{{ $lunas->pembayaran_at ? $lunas->pembayaran_at->format('d M Y') : '-' }}</td>
                                    <td class="text-end fw-bold" style="color:#10B981">Rp {{ number_format($lunas->total_pembayaran ?? 0, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-3 text-muted" style="font-size:.85rem">
                        <i class="bi bi-inbox fs-4 d-block mb-1 opacity-40"></i>
                        Belum ada transaksi lunas dalam 30 hari terakhir
                    </div>
                    @endif

                    {{-- Action Buttons --}}
                    <div class="d-flex gap-2 mt-3">
                        <a href="{{ route('pemilik.sales-report') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-arrow-right-circle"></i> Lihat Laporan Lengkap
                        </a>
                        <a href="{{ route('katalog.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-shop"></i> Katalog
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Orders --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- Chart Penjualan Lunas                              --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <span><i class="bi bi-bar-chart-fill me-2" style="color:var(--primary)"></i>Grafik Penjualan Lunas</span>
            <div class="d-flex gap-1" id="chartFilterBtns">
                <button class="btn btn-sm chart-filter-btn active" data-period="harian"
                    style="border-radius:20px;font-size:.78rem">Harian</button>
                <button class="btn btn-sm chart-filter-btn" data-period="bulanan"
                    style="border-radius:20px;font-size:.78rem">Bulanan</button>
                <button class="btn btn-sm chart-filter-btn" data-period="tahunan"
                    style="border-radius:20px;font-size:.78rem">Tahunan</button>
            </div>
        </div>
        <div class="card-body p-4">
            {{-- Info subtitle --}}
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <span id="chartSubtitle" class="text-muted" style="font-size:.8rem">
                        30 hari terakhir
                    </span>
                </div>
                <div class="text-end">
                    <span class="text-muted" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;font-weight:600">Total Periode</span><br>
                    <span id="chartTotalLabel" class="fw-bold" style="font-size:1rem;color:#10B981"></span>
                </div>
            </div>
            <div style="position:relative;height:280px">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <span><i class="bi bi-clock-history me-2"></i>Pesanan Terbaru</span>
            <a href="{{ route('pemilik.sales-report') }}" class="btn btn-outline-primary btn-sm">
                Lihat Semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pelanggan</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders ?? [] as $order)
                    <tr>
                        <td><span class="fw-bold" style="color:var(--primary)">#{{ $order->id }}</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:30px;height:30px;border-radius:50%;background:var(--primary-pale);color:var(--primary-dark);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.75rem">
                                    {{ strtoupper(substr($order->customer->name ?? 'A', 0, 1)) }}
                                </div>
                                {{ $order->customer->name ?? 'Anonim' }}
                            </div>
                        </td>
                        <td><small class="text-muted">{{ $order->created_at->format('d M Y') }}</small></td>
                        <td>
                            @php $st = strtolower($order->status ?? '') @endphp
                            @if($st == 'selesai')
                                <span class="badge bg-success">Selesai</span>
                            @elseif($st == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($st == 'dikonfirmasi')
                                <span class="badge bg-info text-dark">Dikonfirmasi</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($st) }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
                            Belum ada pesanan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<style>
    .chart-filter-btn {
        background: var(--primary-pale);
        color: var(--primary-dark);
        border: 1.5px solid var(--border-color);
        font-weight: 600;
        transition: all .2s;
    }
    .chart-filter-btn:hover {
        background: var(--primary);
        color: var(--primary-dark);
        border-color: var(--primary);
    }
    .chart-filter-btn.active {
        background: var(--primary);
        color: var(--primary-dark);
        border-color: var(--primary);
        box-shadow: 0 3px 10px rgba(212,175,55,.35);
    }
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    // ── Data dari PHP ──────────────────────────────────────────────────
    const datasets = {
        harian: {
            labels : @json($chartHarianLabels),
            revenue: @json($chartHarianData),
            count  : @json($chartHarianCount),
            subtitle: '30 hari terakhir',
        },
        bulanan: {
            labels : @json($chartBulananLabels),
            revenue: @json($chartBulananData),
            count  : @json($chartBulananCount),
            subtitle: 'Tahun {{ now()->year }}',
        },
        tahunan: {
            labels : @json($chartTahunanLabels),
            revenue: @json($chartTahunanData),
            count  : @json($chartTahunanCount),
            subtitle: 'Semua tahun',
        },
    };

    // ── Helpers ────────────────────────────────────────────────────────
    function fmtRp(n) {
        return 'Rp ' + Number(n).toLocaleString('id-ID');
    }
    function sumArr(arr) { return arr.reduce((a, b) => a + b, 0); }

    // ── Canvas & Gradient ──────────────────────────────────────────────
    const ctx = document.getElementById('salesChart').getContext('2d');
    function makeGradient() {
        const g = ctx.createLinearGradient(0, 0, 0, 280);
        g.addColorStop(0, 'rgba(212,175,55,0.85)');
        g.addColorStop(1, 'rgba(212,175,55,0.15)');
        return g;
    }

    // ── Chart Init ─────────────────────────────────────────────────────
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: datasets.harian.labels,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: datasets.harian.revenue,
                backgroundColor: makeGradient(),
                borderColor: '#D4AF37',
                borderWidth: 1.5,
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 500, easing: 'easeInOutQuart' },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#101216',
                    titleColor: '#D4AF37',
                    bodyColor: '#fff',
                    padding: 12,
                    cornerRadius: 10,
                    callbacks: {
                        title: (items) => items[0].label,
                        label: function(item) {
                            const period = document.querySelector('.chart-filter-btn.active').dataset.period;
                            const idx = item.dataIndex;
                            const cnt = datasets[period].count[idx];
                            return [
                                '  ' + fmtRp(item.raw),
                                '  ' + cnt + ' transaksi',
                            ];
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: {
                        color: '#6C757D',
                        font: { size: 11 },
                        maxRotation: 45,
                        autoSkip: true,
                        maxTicksLimit: 15,
                    }
                },
                y: {
                    grid: { color: '#F0F7FA', lineWidth: 1 },
                    border: { dash: [4, 4] },
                    ticks: {
                        color: '#6C757D',
                        font: { size: 11 },
                        callback: v => v >= 1e6 ? (v/1e6).toFixed(1)+'jt'
                                    : v >= 1e3 ? (v/1e3).toFixed(0)+'rb' : v,
                    }
                }
            }
        }
    });

    // ── Subtitle & Total ───────────────────────────────────────────────
    function updateMeta(period) {
        const d = datasets[period];
        document.getElementById('chartSubtitle').textContent = d.subtitle;
        document.getElementById('chartTotalLabel').textContent = fmtRp(sumArr(d.revenue));
    }
    updateMeta('harian');
    document.getElementById('chartTotalLabel').textContent = fmtRp(sumArr(datasets.harian.revenue));

    // ── Filter Buttons ─────────────────────────────────────────────────
    document.querySelectorAll('.chart-filter-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.chart-filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const period = this.dataset.period;
            const d = datasets[period];

            chart.data.labels = d.labels;
            chart.data.datasets[0].data = d.revenue;
            chart.data.datasets[0].backgroundColor = makeGradient();
            chart.update();
            updateMeta(period);
        });
    });
})();
</script>
@endpush

@endsection
