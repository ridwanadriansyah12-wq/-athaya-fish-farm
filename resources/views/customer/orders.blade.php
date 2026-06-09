@extends('layouts.app')

@section('title', 'Riwayat Pembelian')

@section('extra-css')
<style>
    .orders-page { padding: 3rem 0; }
    .orders-title { font-family: 'Playfair Display', serif; font-size: 32px; font-weight: 700; color: #111827; margin-bottom: 2rem; }

    .order-card {
        background: #fff;
        border-radius: 16px;
        border: 0.5px solid #E5E7EB;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: transform 200ms ease, box-shadow 200ms ease;
    }
    .order-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        border-color: rgba(245,166,35,0.3);
    }

    .order-meta-label { font-size: 12px; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; margin-bottom: 4px; }
    .order-meta-val { font-size: 15px; font-weight: 600; color: #111827; }
    .order-meta-total { font-size: 18px; font-weight: 700; color: #F5A623; }

    .btn-toggle-detail {
        color: #F5A623;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        transition: color 200ms ease;
        display: inline-flex; align-items: center; gap: 4px;
    }
    .btn-toggle-detail:hover { color: #D4890F; }

    .order-detail-box {
        margin-top: 1.5rem;
        border-top: 1px solid #E5E7EB;
        padding-top: 1.5rem;
        display: none;
    }
    .order-detail-box.show { display: block; animation: pageFadeInUp 300ms ease; }

    .detail-table th { background: #F8F9FA; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; padding: 10px 16px; border-bottom: none; }
    .detail-table td { font-size: 14px; color: #374151; padding: 12px 16px; border-bottom: 1px solid #F3F4F6; vertical-align: middle; }
    .detail-table tr:last-child td { border-bottom: none; }

    .empty-state { background: #fff; border-radius: 16px; padding: 4rem 2rem; text-align: center; border: 0.5px solid #E5E7EB; }
    .empty-state-icon { font-size: 4rem; color: #D1D5DB; margin-bottom: 1rem; }
</style>
@endsection

@section('content')
<div class="container orders-page">
    <h1 class="orders-title animate-in"><i class="bi bi-receipt me-2" style="color:#F5A623"></i>Riwayat Pembelian</h1>

    @if(isset($penjualan) && $penjualan->count() > 0)
    <div class="row">
        <div class="col-lg-10 mx-auto">
            @foreach($penjualan as $index => $item)
            <div class="order-card animate-in" style="transition-delay: {{ $index * 50 }}ms">
                <div class="row align-items-center g-3">
                    <div class="col-md-3 col-6">
                        <div class="order-meta-label">Tanggal</div>
                        <div class="order-meta-val">{{ $item->Tanggal_Penjualan->format('d M Y H:i') }}</div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="order-meta-label">Jumlah Item</div>
                        <div class="order-meta-val">{{ $item->detailPenjualan->count() }} Item</div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="order-meta-label">Total Pembayaran</div>
                        <div class="order-meta-total">Rp {{ number_format($item->Total_Pendapatan, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-md-3 col-6 text-md-end">
                        <a href="javascript:void(0)" class="btn-toggle-detail" onclick="toggleDetail({{ $item->id }}, this)">
                            Lihat Detail <i class="bi bi-chevron-down ms-1"></i>
                        </a>
                    </div>
                </div>

                <div id="detail-{{ $item->id }}" class="order-detail-box">
                    <div class="table-responsive">
                        <table class="table detail-table mb-0">
                            <thead>
                                <tr>
                                    <th class="text-start rounded-start">Produk</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end rounded-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($item->detailPenjualan as $detail)
                                <tr>
                                    <td class="text-start fw-medium" style="color:#111827">{{ $detail->menu->nama_produk ?? $detail->menu->Nama_Produk ?? 'Produk Dihapus' }}</td>
                                    <td class="text-end">Rp {{ number_format($detail->Harga, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $detail->Qty }}</td>
                                    <td class="text-end fw-semibold">Rp {{ number_format($detail->Harga * $detail->Qty, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="empty-state animate-in">
                <div class="empty-state-icon"><i class="bi bi-journal-x"></i></div>
                <p style="font-size:18px;color:#6B7280;margin-bottom:1.5rem;font-weight:500;">Anda belum melakukan pembelian</p>
                <a href="{{ route('katalog.index') }}" class="btn btn-primary btn-lg px-4" style="border-radius:99px;">
                    Mulai Belanja Sekarang
                </a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function toggleDetail(id, btn) {
        const detailBox = document.getElementById('detail-' + id);
        const icon = btn.querySelector('i');

        if (detailBox.classList.contains('show')) {
            detailBox.classList.remove('show');
            icon.classList.remove('bi-chevron-up');
            icon.classList.add('bi-chevron-down');
            btn.innerHTML = 'Lihat Detail <i class="bi bi-chevron-down ms-1"></i>';
        } else {
            detailBox.classList.add('show');
            icon.classList.remove('bi-chevron-down');
            icon.classList.add('bi-chevron-up');
            btn.innerHTML = 'Tutup Detail <i class="bi bi-chevron-up ms-1"></i>';
        }
    }
</script>
@endpush
