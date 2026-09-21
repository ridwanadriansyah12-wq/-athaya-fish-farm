@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $pesanan->nomor_pesanan . ' — Athaya Fish Farm')

@section('extra-css')
<style>
    .order-section { padding: 2.5rem 0 4rem; background: #F8F9FA; }

    /* Breadcrumb */
    .bc { display: flex; align-items: center; gap: 6px; font-size: 13px; margin-bottom: 1.5rem; }
    .bc a { color: #0EA5E9; text-decoration: none; font-weight: 500; }
    .bc a:hover { color: #0284C7; text-decoration: underline; }
    .bc-sep { color: #D1D5DB; }
    .bc-current { color: #6B7280; }

    /* Cards */
    .o-card {
        background: #fff; border-radius: 16px;
        border: 1px solid #E5E7EB;
        box-shadow: 0 1px 6px rgba(0,0,0,.05);
        margin-bottom: 1.25rem; overflow: hidden;
    }
    .o-card-header {
        padding: .9rem 1.25rem;
        border-bottom: 1px solid #F0F0F0;
        display: flex; align-items: center; gap: 8px;
    }
    .o-card-header h5 { font-size: 14px; font-weight: 700; color: #111827; margin: 0; }
    .o-card-body { padding: 1.25rem; }

    /* Order number banner */
    .order-banner {
        background: linear-gradient(135deg, #0D1117, #161B22);
        border-radius: 16px; padding: 1.25rem 1.5rem;
        margin-bottom: 1.25rem;
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: .75rem;
        border: 1px solid rgba(255,255,255,.06);
    }
    .order-no { font-size: 18px; font-weight: 800; color: #F0F6FC; }
    .order-date { font-size: 12px; color: #8B949E; margin-top: 2px; }

    /* Status badges */
    .status-pill {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 12px; font-weight: 700;
        padding: 5px 14px; border-radius: 99px;
    }
    .status-pending     { background: #FEF9C3; color: #854D0E; border: 1px solid #FDE047; }
    .status-dikonfirmasi{ background: #DBEAFE; color: #1E40AF; border: 1px solid #93C5FD; }
    .status-lunas       { background: #DCFCE7; color: #166534; border: 1px solid #86EFAC; }
    .status-selesai     { background: #DCFCE7; color: #166534; border: 1px solid #86EFAC; }
    .status-persiapan   { background: #FCE7F3; color: #9D174D; border: 1px solid #F9A8D4; }
    .status-dikirim     { background: #DBEAFE; color: #1D4ED8; border: 1px solid #93C5FD; }
    .status-ditolak     { background: #FEF2F2; color: #991B1B; border: 1px solid #FCA5A5; }
    .status-batal       { background: #F3F4F6; color: #6B7280; border: 1px solid #D1D5DB; }

    /* Order timeline */
    .timeline { padding: .75rem 0; }
    .timeline-item {
        display: flex; gap: 14px;
        padding-bottom: 1.25rem;
        position: relative;
    }
    .timeline-item:last-child { padding-bottom: 0; }
    .timeline-item:not(:last-child)::before {
        content: '';
        position: absolute;
        left: 14px; top: 32px; bottom: 0;
        width: 2px; background: #E5E7EB;
    }
    .timeline-item.done::before { background: #10B981; }
    .timeline-dot {
        width: 30px; height: 30px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 14px; flex-shrink: 0;
        margin-top: 2px; position: relative; z-index: 1;
    }
    .timeline-dot.done { background: #DCFCE7; color: #059669; border: 2px solid #10B981; }
    .timeline-dot.active { background: #DBEAFE; color: #2563EB; border: 2px solid #3B82F6; animation: pulse-dot .8s ease infinite alternate; }
    .timeline-dot.idle { background: #F3F4F6; color: #9CA3AF; border: 2px solid #E5E7EB; }
    @keyframes pulse-dot { from { transform: scale(1); } to { transform: scale(1.12); } }
    .timeline-content-label { font-size: 13px; font-weight: 700; color: #111827; }
    .timeline-content-desc { font-size: 12px; color: #9CA3AF; }

    /* Meta row */
    .meta-row { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: .6rem; font-size: 13px; }
    .meta-row-label { color: #6B7280; }
    .meta-row-val { font-weight: 600; color: #111827; text-align: right; max-width: 60%; }

    /* Items table */
    .item-row { display: flex; align-items: center; gap: 12px; padding: .85rem 0; border-bottom: 1px solid #F0F0F0; }
    .item-row:last-child { border-bottom: none; }
    .item-img { width: 52px; height: 52px; border-radius: 8px; object-fit: cover; border: 1px solid #E5E7EB; flex-shrink: 0; }
    .item-img-ph { width: 52px; height: 52px; border-radius: 8px; background: #F3F4F6; display: flex; align-items: center; justify-content: center; color: #D1D5DB; font-size: 1.2rem; flex-shrink: 0; }
    .item-name { font-size: 13px; font-weight: 700; color: #111827; }
    .item-meta { font-size: 12px; color: #9CA3AF; }
    .item-subtotal { font-size: 14px; font-weight: 800; color: #0EA5E9; white-space: nowrap; margin-left: auto; }

    /* Summary card (sticky) */
    .summary-card {
        background: #fff; border-radius: 16px;
        border: 1px solid #E5E7EB;
        box-shadow: 0 1px 6px rgba(0,0,0,.05);
        position: sticky; top: 76px;
        overflow: hidden;
    }
    .summary-dark-header {
        background: linear-gradient(135deg, #0D1117, #161B22);
        padding: .9rem 1.25rem;
        display: flex; align-items: center; gap: 8px;
    }
    .summary-dark-header h5 { color: #F0F6FC; font-size: 14px; font-weight: 700; margin: 0; }
    .summary-body { padding: 1.25rem; }
    .sum-row { display: flex; justify-content: space-between; align-items: center; font-size: 13px; margin-bottom: .5rem; }
    .sum-label { color: #6B7280; }
    .sum-val { font-weight: 600; color: #111827; }
    .sum-divider { border: none; border-top: 1px dashed #E5E7EB; margin: .75rem 0; }
    .sum-total-label { font-size: 14px; font-weight: 700; color: #111827; }
    .sum-total-val { font-size: 20px; font-weight: 800; color: #0EA5E9; }

    .btn-pay-now {
        width: 100%; padding: 13px;
        background: linear-gradient(135deg, #10B981, #059669);
        color: #fff; border: none; border-radius: 12px;
        font-size: 14px; font-weight: 800;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        cursor: pointer;
        transition: all 200ms ease;
        box-shadow: 0 4px 14px rgba(16,185,129,.3);
    }
    .btn-pay-now:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(16,185,129,.4); }
    .btn-pay-now:disabled { opacity: .7; transform: none; cursor: not-allowed; }
</style>
@endsection

@section('content')
<div class="order-section">
    <div class="container">

        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb">
            <div class="bc animate-in">
                <a href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
                <span class="bc-sep">/</span>
                <a href="{{ route('pesanan.list') }}">Pesanan Saya</a>
                <span class="bc-sep">/</span>
                <span class="bc-current">{{ $pesanan->nomor_pesanan }}</span>
            </div>
        </nav>

        {{-- Order Number Banner --}}
        <div class="order-banner animate-in" style="transition-delay:40ms;">
            <div>
                <div class="order-no"><i class="bi bi-receipt me-2" style="color:#0EA5E9;"></i>{{ $pesanan->nomor_pesanan }}</div>
                <div class="order-date">Dipesan: {{ $pesanan->created_at?->format('d F Y, H:i') ?? '-' }} WIB</div>
            </div>
            @php
                $statusClass = match($pesanan->status) {
                    'lunas', 'selesai' => 'lunas',
                    'dikonfirmasi'     => 'dikonfirmasi',
                    'persiapan'        => 'persiapan',
                    'dikirim'          => 'dikirim',
                    'ditolak'          => 'ditolak',
                    'batal'            => 'batal',
                    default            => 'pending',
                };
            @endphp
            <span class="status-pill status-{{ $statusClass }}">
                <i class="bi bi-circle-fill" style="font-size:6px;"></i>
                {{ ucfirst(str_replace('_', ' ', $pesanan->status)) }}
            </span>
        </div>

        <div class="row g-4">
            {{-- Kiri: Detail --}}
            <div class="col-md-8">

                {{-- Order Timeline --}}
                <div class="o-card animate-in" style="transition-delay:60ms;">
                    <div class="o-card-header">
                        <i class="bi bi-clock-history" style="color:#0EA5E9;font-size:1rem;"></i>
                        <h5>Status Pesanan</h5>
                    </div>
                    <div class="o-card-body">
                        @php
                            $allStatuses = ['pending', 'dikonfirmasi', 'lunas', 'persiapan', 'dikirim', 'selesai'];
                            $statusOrder = ['pending' => 0, 'dikonfirmasi' => 1, 'lunas' => 2, 'persiapan' => 3, 'dikirim' => 4, 'selesai' => 5];
                            $currentIdx  = $statusOrder[$pesanan->status] ?? -1;
                            $statusLabels = [
                                'pending'      => ['label' => 'Pesanan Diterima', 'desc' => 'Pesanan berhasil dibuat', 'icon' => 'bi-cart-check'],
                                'dikonfirmasi' => ['label' => 'Dikonfirmasi', 'desc' => 'Admin telah menerima pesanan', 'icon' => 'bi-check-circle'],
                                'lunas'        => ['label' => 'Pembayaran Lunas', 'desc' => 'Pembayaran berhasil diverifikasi', 'icon' => 'bi-wallet2'],
                                'persiapan'    => ['label' => 'Persiapan', 'desc' => 'Produk sedang disiapkan', 'icon' => 'bi-box-seam'],
                                'dikirim'      => ['label' => 'Dalam Pengiriman', 'desc' => 'Produk sedang dalam perjalanan', 'icon' => 'bi-truck'],
                                'selesai'      => ['label' => 'Selesai', 'desc' => 'Pesanan telah diterima', 'icon' => 'bi-stars'],
                            ];
                        @endphp

                        @if(in_array($pesanan->status, ['ditolak', 'batal']))
                            <div style="display:flex;align-items:center;gap:10px;padding:.5rem 0;">
                                <div class="timeline-dot" style="background:#FEF2F2;color:#DC2626;border:2px solid #EF4444;">
                                    <i class="bi bi-x-circle"></i>
                                </div>
                                <div>
                                    <div class="timeline-content-label" style="color:#DC2626;">
                                        Pesanan {{ ucfirst($pesanan->status) }}
                                    </div>
                                    <div class="timeline-content-desc">Hubungi kami jika ada pertanyaan</div>
                                </div>
                            </div>
                        @else
                            <div class="timeline">
                                @foreach($allStatuses as $idx => $st)
                                    @php
                                        $stIdx = $statusOrder[$st] ?? 0;
                                        $isDone   = $stIdx < $currentIdx;
                                        $isActive = $stIdx === $currentIdx;
                                        $isIdle   = $stIdx > $currentIdx;
                                        $dotClass = $isDone ? 'done' : ($isActive ? 'active' : 'idle');
                                        $info = $statusLabels[$st] ?? ['label' => $st, 'desc' => '', 'icon' => 'bi-circle'];
                                    @endphp
                                    <div class="timeline-item {{ $isDone ? 'done' : '' }}">
                                        <div class="timeline-dot {{ $dotClass }}">
                                            @if($isDone)
                                                <i class="bi bi-check-lg"></i>
                                            @else
                                                <i class="{{ $info['icon'] }}"></i>
                                            @endif
                                        </div>
                                        <div class="mt-1">
                                            <div class="timeline-content-label" style="{{ $isIdle ? 'color:#9CA3AF;' : '' }}">
                                                {{ $info['label'] }}
                                            </div>
                                            <div class="timeline-content-desc">{{ $info['desc'] }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Daftar Produk --}}
                <div class="o-card animate-in" style="transition-delay:80ms;">
                    <div class="o-card-header">
                        <i class="bi bi-bag-check" style="color:#0EA5E9;font-size:1rem;"></i>
                        <h5>Item Pesanan ({{ $detailPesanan->count() }} produk)</h5>
                    </div>
                    <div class="o-card-body" style="padding-top:.5rem;padding-bottom:.5rem;">
                        @foreach($detailPesanan as $detail)
                            <div class="item-row">
                                @php $img = $detail->katalogIkan?->first_image; @endphp
                                @if($img)
                                    <img src="{{ asset('storage/' . $img) }}" class="item-img" alt="{{ $detail->katalogIkan->nama_produk }}">
                                @else
                                    <div class="item-img-ph"><i class="bi bi-fish"></i></div>
                                @endif
                                <div class="flex-grow-1">
                                    <div class="item-name">{{ $detail->katalogIkan?->nama_produk ?? 'Produk dihapus' }}</div>
                                    <div class="item-meta">
                                        {{ $detail->kuantitas }} unit × Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                                    </div>
                                    @if($detail->dengan_layanan_budidaya)
                                        <div class="item-meta" style="color:#059669;margin-top:2px;">
                                            <i class="bi bi-droplet-half"></i>
                                            Budidaya {{ $detail->durasi_budidaya_hari }} hari — Rp {{ number_format($detail->biaya_budidaya, 0, ',', '.') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="item-subtotal">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Alamat & Info Pesanan --}}
                <div class="o-card animate-in" style="transition-delay:100ms;">
                    <div class="o-card-header">
                        <i class="bi bi-info-circle" style="color:#0EA5E9;font-size:1rem;"></i>
                        <h5>Detail Pesanan</h5>
                    </div>
                    <div class="o-card-body">
                        <div class="meta-row">
                            <span class="meta-row-label">Nomor Pesanan</span>
                            <span class="meta-row-val" style="font-family:monospace;">{{ $pesanan->nomor_pesanan }}</span>
                        </div>
                        <div class="meta-row">
                            <span class="meta-row-label">Tanggal Pesan</span>
                            <span class="meta-row-val">{{ $pesanan->created_at?->format('d M Y, H:i') ?? '-' }} WIB</span>
                        </div>
                        <div class="meta-row">
                            <span class="meta-row-label">Customer</span>
                            <span class="meta-row-val">{{ optional($pesanan->customer)->name ?? '-' }}</span>
                        </div>
                        @if($pesanan->catatan_pesanan)
                            <div class="meta-row">
                                <span class="meta-row-label">Catatan</span>
                                <span class="meta-row-val">{{ $pesanan->catatan_pesanan }}</span>
                            </div>
                        @endif
                        <hr style="border-color:#F0F0F0;margin:.75rem 0;">
                        <div style="background:#F8F9FA;border-radius:10px;padding:.85rem 1rem;border:1px solid #F0F0F0;">
                            <div style="font-size:11px;color:#9CA3AF;font-weight:700;text-transform:uppercase;letter-spacing:.4px;margin-bottom:4px;">
                                <i class="bi bi-geo-alt-fill" style="color:#EF4444;"></i> Alamat Pengiriman
                            </div>
                            <div style="font-size:13px;font-weight:600;color:#111827;line-height:1.5;">
                                {{ $pesanan->alamat_pengiriman ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Persiapan notice --}}
                @if($pesanan->status === 'persiapan')
                    <div class="animate-in" style="background:#FEF9C3;border:1px solid #FDE047;border-radius:12px;padding:.9rem 1.1rem;display:flex;gap:10px;align-items:flex-start;transition-delay:120ms;">
                        <i class="bi bi-clock-history" style="color:#D97706;font-size:1.2rem;flex-shrink:0;margin-top:1px;"></i>
                        <div>
                            <strong style="color:#92400E;font-size:13px;">Pesanan Sedang Disiapkan</strong>
                            <p style="font-size:12px;color:#78350F;margin:.3rem 0 0;">Jika lebih dari <strong>7 hari</strong> belum ada pengiriman, hubungi kami via WhatsApp.</p>
                        </div>
                    </div>
                @endif

                {{-- Pengiriman info --}}
                @if($pesanan->pengiriman)
                    <div class="o-card animate-in" style="transition-delay:130ms;">
                        <div class="o-card-header">
                            <i class="bi bi-truck" style="color:#0EA5E9;font-size:1rem;"></i>
                            <h5>Informasi Pengiriman</h5>
                        </div>
                        <div class="o-card-body">
                            <div class="meta-row">
                                <span class="meta-row-label">Alamat</span>
                                <span class="meta-row-val">{{ $pesanan->pengiriman->alamat_pengiriman }}</span>
                            </div>
                            <div class="meta-row">
                                <span class="meta-row-label">Kurir</span>
                                <span class="meta-row-val">{{ $pesanan->pengiriman->kurir ?? '-' }}</span>
                            </div>
                            <div class="meta-row">
                                <span class="meta-row-label">No. Resi</span>
                                <span class="meta-row-val" style="font-family:monospace;">{{ $pesanan->pengiriman->nomor_resi ?? '-' }}</span>
                            </div>
                            <div class="meta-row">
                                <span class="meta-row-label">Status Kirim</span>
                                <span class="meta-row-val">{{ ucfirst(str_replace('_', ' ', $pesanan->pengiriman->status)) }}</span>
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            {{-- Kanan: Summary & Bayar --}}
            <div class="col-md-4">
                <div class="summary-card animate-in" style="transition-delay:80ms;">
                    <div class="summary-dark-header">
                        <i class="bi bi-wallet2" style="color:#0EA5E9;font-size:1rem;"></i>
                        <h5>Ringkasan Pembayaran</h5>
                    </div>
                    <div class="summary-body">
                        <div class="sum-row">
                            <span class="sum-label">Subtotal Produk</span>
                            <span class="sum-val">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                        </div>
                        @if(isset($pesanan->total_jasa_budidaya) && $pesanan->total_jasa_budidaya > 0)
                            <div class="sum-row">
                                <span class="sum-label">Biaya Budidaya</span>
                                <span class="sum-val">Rp {{ number_format($pesanan->total_jasa_budidaya, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="sum-row">
                            <span class="sum-label">Biaya Layanan</span>
                            <span class="sum-val" style="color:#059669;">Gratis</span>
                        </div>
                        <hr class="sum-divider">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="sum-total-label">Total Bayar</span>
                            <span class="sum-total-val">Rp {{ number_format($pesanan->total_pembayaran, 0, ',', '.') }}</span>
                        </div>

                        {{-- Payment method info --}}
                        @if($pesanan->pembayaran && in_array($pesanan->pembayaran->status_pembayaran, ['settlement', 'capture']))
                            <div style="background:#DCFCE7;border:1px solid #86EFAC;border-radius:10px;padding:.75rem 1rem;margin-bottom:1rem;text-align:center;">
                                <div style="font-size:13px;font-weight:700;color:#166534;">
                                    <i class="bi bi-check-circle-fill me-1"></i> Pembayaran Lunas
                                </div>
                                @if($pesanan->pembayaran->metode_pembayaran)
                                    <div style="font-size:11px;color:#15803D;margin-top:2px;">
                                        via {{ ucwords(str_replace('_', ' ', $pesanan->pembayaran->metode_pembayaran)) }}
                                    </div>
                                @endif
                            </div>
                        @endif

                        {{-- Bayar button --}}
                        @if(in_array($pesanan->status, ['dikonfirmasi', 'pembayaran', 'pending']))
                            <button type="button" id="btn-bayar-midtrans" onclick="bayarViaMidtrans()" class="btn-pay-now">
                                <i class="bi bi-lock-fill"></i> Bayar Sekarang
                            </button>
                            <p style="font-size:11px;color:#9CA3AF;text-align:center;margin-top:.6rem;margin-bottom:1rem;">
                                <i class="bi bi-shield-check" style="color:#10B981;"></i> Aman via Midtrans
                            </p>
                        @endif

                        {{-- Download invoice --}}
                        @if($pesanan->pembayaran && $pesanan->pembayaran->invoice_path)
                            <a href="{{ asset('storage/' . $pesanan->pembayaran->invoice_path) }}"
                               class="btn btn-outline-secondary btn-sm w-100" target="_blank">
                                <i class="bi bi-download"></i> Download Invoice
                            </a>
                        @endif

                        {{-- Hubungi --}}
                        <a href="https://wa.me/6289613130130?text={{ urlencode('Halo, saya ingin tanya soal pesanan ' . $pesanan->nomor_pesanan) }}"
                           target="_blank"
                           style="display:flex;align-items:center;justify-content:center;gap:6px;margin-top:.75rem;font-size:12px;color:#16A34A;text-decoration:none;font-weight:600;">
                            <i class="bi bi-whatsapp"></i> Tanya via WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Midtrans Snap JS --}}
<script src="{{ config('services.midtrans.is_production')
    ? 'https://app.midtrans.com/snap/snap.js'
    : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
    data-client-key="{{ config('services.midtrans.client_key') }}">
</script>

<script>
    const snapTokenUrl     = "{{ route('pembayaran.snap-token', $pesanan) }}";
    const handleSuccessUrl = "{{ route('pembayaran.handle-success', $pesanan) }}";
    const csrfToken        = "{{ csrf_token() }}";

    function bayarViaMidtrans() {
        const btn = document.getElementById('btn-bayar-midtrans');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Memproses...';

        fetch(snapTokenUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        })
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                alert('Gagal mendapatkan token: ' + data.error);
                resetBtn(btn);
                return;
            }
            window.snap.pay(data.snap_token, {
                onSuccess: function (result) {
                    fetch(handleSuccessUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                    }).finally(() => window.location.reload());
                },
                onPending: function () { window.location.reload(); },
                onError: function () {
                    alert('Pembayaran gagal. Silakan coba lagi.');
                    resetBtn(btn);
                },
                onClose: function () { resetBtn(btn); }
            });
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan jaringan. Silakan coba lagi.');
            resetBtn(btn);
        });
    }

    function resetBtn(btn) {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-lock-fill"></i> Bayar Sekarang';
    }
</script>
@endsection
