@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="h4 font-weight-bold">Insights Pelanggan</h2>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('pemilik.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Customer Overview -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-left-primary">
                <div class="card-body">
                    <div class="text-primary font-weight-bold text-uppercase mb-1">Total Pelanggan</div>
                    <div class="h3 mb-0">{{ $totalCustomers }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Growth Chart -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Pertumbuhan Pelanggan (6 Bulan Terakhir)</h5>
                </div>
                <div class="card-body">
                    <div style="height: 300px; display: flex; align-items: flex-end; gap: 10px; padding: 20px 0;">
                        @forelse($customerGrowth as $month => $count)
                            <div style="flex: 1; display: flex; flex-direction: column; align-items: center;">
                                <div style="height: {{ max(($count / max($customerGrowth) * 250), 20) }}px; width: 100%; background: linear-gradient(to top, #fd7e14, #e8590c); border-radius: 4px;"></div>
                                <small class="mt-2 text-muted">{{ $month }}</small>
                                <small class="font-weight-bold">{{ $count }} pelanggan</small>
                            </div>
                        @empty
                            <p class="text-muted">Tidak ada data</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Customers by Orders -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Pelanggan Paling Aktif (Top 10)</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>Pesanan</th>
                                <th class="text-right">Total Belanja</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customerWithMostOrders as $customer)
                            <tr>
                                <td>
                                    <span class="font-weight-bold">{{ $customer['name'] }}</span>
                                    <br>
                                    <small class="text-muted">{{ $customer['email'] }}</small>
                                </td>
                                <td><span class="badge bg-primary">{{ $customer['orders'] }}</span></td>
                                <td class="text-right font-weight-bold">Rp {{ number_format($customer['totalSpent'], 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-3 text-muted">Tidak ada data pelanggan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Top Customers by Spending -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Pelanggan Top Spender (Top 10)</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>Pesanan</th>
                                <th class="text-right">Total Belanja</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topSpenders as $customer)
                            <tr>
                                <td>
                                    <span class="font-weight-bold">{{ $customer['name'] }}</span>
                                    <br>
                                    <small class="text-muted">{{ $customer['phone'] ?? $customer['email'] }}</small>
                                </td>
                                <td><span class="badge bg-info">{{ $customer['orders'] }}</span></td>
                                <td class="text-right font-weight-bold">Rp {{ number_format($customer['spent'], 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-3 text-muted">Tidak ada data pelanggan</td>
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
