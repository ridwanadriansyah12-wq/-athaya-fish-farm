@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="h4 font-weight-bold">Metrik Operasional</h2>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('pemilik.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Kolam Overview -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-left-primary">
                <div class="card-body">
                    <div class="text-primary font-weight-bold text-uppercase mb-1">Total Kolam</div>
                    <div class="h3 mb-0">{{ $totalKolams }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-success">
                <div class="card-body">
                    <div class="text-success font-weight-bold text-uppercase mb-1">Kolam Aktif</div>
                    <div class="h3 mb-0">{{ $activeKolams }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-warning">
                <div class="card-body">
                    <div class="text-warning font-weight-bold text-uppercase mb-1">Maintenance</div>
                    <div class="h3 mb-0">{{ $maintenanceKolams }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Health Status -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Status Kesehatan Kolam</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <p class="text-muted mb-1">Normal</p>
                            <p class="h4 font-weight-bold text-success">{{ $healthStatuses['normal'] ?? 0 }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted mb-1">Warning</p>
                            <p class="h4 font-weight-bold text-warning">{{ $healthStatuses['warning'] ?? 0 }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted mb-1">Darurat</p>
                            <p class="h4 font-weight-bold text-danger">{{ $healthStatuses['darurat'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fish Types -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Jenis Ikan & Kolam</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4">Jenis Ikan</th>
                                <th>Total Kolam</th>
                                <th>Suhu Ideal</th>
                                <th>pH Ideal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($fishTypes as $fish)
                            <tr>
                                <td class="px-4"><span class="font-weight-bold">{{ $fish['name'] }}</span></td>
                                <td>{{ $fish['totalKolams'] }} kolam</td>
                                <td>{{ $fish['tempMin'] ?? '-' }}°C - {{ $fish['tempMax'] ?? '-' }}°C</td>
                                <td>{{ $fish['pH_Min'] ?? '-' }} - {{ $fish['pH_Max'] ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-3 text-muted">Tidak ada data jenis ikan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Feed Inventory -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Inventaris Pakan</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4">Nama Pakan</th>
                                <th>Stok</th>
                                <th>Nilai Stok</th>
                                <th>Tanggal Masuk</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($feedInventory as $feed)
                            <tr>
                                <td class="px-4"><span class="font-weight-bold">{{ $feed['name'] }}</span></td>
                                <td>
                                    <span class="badge bg-info">{{ $feed['stock'] }} kg</span>
                                </td>
                                <td><span class="font-weight-bold">Rp {{ number_format($feed['value'], 0, ',', '.') }}</span></td>
                                <td>
                                    <small class="text-muted">{{ $feed['lastDate'] ? \Carbon\Carbon::parse($feed['lastDate'])->format('d M Y') : '-' }}</small>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-3 text-muted">Tidak ada data pakan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Daily Reports -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Laporan Harian Terbaru</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4">Tanggal</th>
                                <th>Kolam</th>
                                <th>Jenis Ikan</th>
                                <th>Suhu / pH</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentReports as $report)
                            <tr>
                                <td class="px-4"><small class="text-muted">{{ $report->Tanggal ? \Carbon\Carbon::parse($report->Tanggal)->format('d M Y') : '-' }}</small></td>
                                <td>{{ $report->kolam->Nama_Kolam ?? '-' }}</td>
                                <td>{{ $report->jenisIkan->Nama_Ikan ?? '-' }}</td>
                                <td><small>{{ $report->Suhu_Air ?? '-' }}°C / {{ $report->pH_Air ?? '-' }}</small></td>
                                <td>
                                    @if($report->Status == 'normal')
                                        <span class="badge bg-success">Normal</span>
                                    @elseif($report->Status == 'warning')
                                        <span class="badge bg-warning">Warning</span>
                                    @elseif($report->Status == 'darurat')
                                        <span class="badge bg-danger">Darurat</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($report->Status) }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-3 text-muted">Tidak ada laporan harian</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
