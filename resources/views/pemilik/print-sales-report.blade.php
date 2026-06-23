<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Penjualan — Athaya Fish Farm</title>

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            color: #111;
            background: #fff;
            padding: 20px 30px;
        }

        /* ── HEADER ── */
        .print-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px solid #101216;
            padding-bottom: 14px;
            margin-bottom: 18px;
        }

        .print-header .company-info h1 {
            font-size: 20px;
            font-weight: 800;
            color: #101216;
            letter-spacing: -.3px;
        }

        .print-header .company-info p {
            font-size: 11px;
            color: #555;
            margin-top: 2px;
        }

        .print-header .report-meta {
            text-align: right;
            font-size: 11px;
            color: #555;
        }

        .print-header .report-meta strong {
            display: block;
            font-size: 13px;
            color: #111;
            margin-bottom: 4px;
        }

        /* ── FILTER INFO ── */
        .filter-info {
            background: #fffdf4;
            border: 1px solid #f0e6c0;
            border-radius: 6px;
            padding: 8px 14px;
            margin-bottom: 16px;
            font-size: 11px;
            color: #5c4200;
        }

        .filter-info span { font-weight: 700; }

        /* ── STAT CARDS ── */
        .stat-row {
            display: flex;
            gap: 12px;
            margin-bottom: 18px;
        }

        .stat-card {
            flex: 1;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 14px;
            text-align: center;
        }

        .stat-card .label {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #777;
            margin-bottom: 4px;
        }

        .stat-card .value {
            font-size: 15px;
            font-weight: 800;
        }

        .stat-card.blue   .value { color: #1565C0; }
        .stat-card.green  .value { color: #1B8A4E; }
        .stat-card.teal   .value { color: #00838F; }
        .stat-card.gold   .value { color: #0284C7; }

        /* ── TABLE ── */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        thead tr {
            background: #101216;
            color: #fff;
        }

        thead th {
            padding: 8px 10px;
            text-align: left;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        tbody tr:nth-child(even) { background: #f9f9f9; }

        tbody td {
            padding: 7px 10px;
            border-bottom: 1px solid #e8e8e8;
            vertical-align: middle;
        }

        .badge-lunas {
            display: inline-block;
            background: #d4edda;
            color: #155724;
            border-radius: 4px;
            padding: 2px 8px;
            font-size: 10px;
            font-weight: 700;
        }

        .text-success { color: #1B8A4E; font-weight: 700; }
        .text-muted   { color: #777; font-size: 10px; }
        .fw-bold      { font-weight: 700; }
        .text-primary { color: #0284C7; font-weight: 600; }

        /* ── FOOTER TABLE ── */
        .table-footer { background: #101216; color: #fff; }
        .table-footer td {
            padding: 9px 10px;
            font-weight: 700;
            font-size: 12px;
            border: none;
        }

        /* ── PRINT FOOTER ── */
        .print-footer {
            margin-top: 24px;
            border-top: 1px solid #ddd;
            padding-top: 12px;
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: #888;
        }

        /* ── ACTIONS ── */
        .no-print {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-bottom: 16px;
        }

        .btn-print {
            background: #101216;
            color: #fff;
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-back {
            background: #fff;
            color: #333;
            border: 1.5px solid #ccc;
            padding: 8px 20px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        @media print {
            .no-print { display: none !important; }
            body { padding: 10px 15px; }
            thead { display: table-header-group; }
            tfoot { display: table-footer-group; }
            table { page-break-inside: auto; }
            tr    { page-break-inside: avoid; page-break-after: auto; }
        }
    </style>
</head>
<body>

    {{-- Tombol aksi (tidak cetak) --}}
    <div class="no-print">
        <a href="{{ route('pemilik.sales-report', request()->all()) }}" class="btn-back">
            ← Kembali
        </a>
        <button class="btn-print" onclick="window.print()">
            🖨 Cetak Sekarang
        </button>
    </div>

    {{-- Header --}}
    <div class="print-header">
        <div class="company-info">
            <h1>🐟 Athaya Fish Farm</h1>
            <p>Sistem Informasi E-Commerce Budidaya Ikan</p>
            <p>Dicetak oleh: <strong>{{ auth()->user()->name }}</strong></p>
        </div>
        <div class="report-meta">
            <strong>LAPORAN PENJUALAN — PEMILIK</strong>
            @if(($filterType ?? 'harian') === 'bulanan')
                @php
                    $months = [
                        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                        '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                        '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                    ];
                    $monthName = $months[$selectedMonth ?? ''] ?? '';
                @endphp
                Periode: Bulan {{ $monthName }} {{ $selectedYear ?? '' }}
            @elseif(($filterType ?? 'harian') === 'tahunan')
                Periode: Tahun {{ $selectedYear ?? '' }}
            @elseif(request('start_date') || request('end_date'))
                Periode: {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->translatedFormat('d M Y') : 'Awal' }}
                – {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->translatedFormat('d M Y') : 'Sekarang' }}
            @else
                Periode: Semua Waktu
            @endif
            <br>Dicetak: {{ now()->translatedFormat('d M Y, H:i') }} WIB
        </div>
    </div>

    {{-- Info filter aktif --}}
    @if(($filterType ?? 'harian') === 'bulanan')
        @php
            $months = [
                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
            ];
            $monthName = $months[$selectedMonth ?? ''] ?? '';
        @endphp
        <div class="filter-info">
            🔍 Filter aktif — Bulan: <span>{{ $monthName }} {{ $selectedYear ?? '' }}</span>
        </div>
    @elseif(($filterType ?? 'harian') === 'tahunan')
        <div class="filter-info">
            🔍 Filter aktif — Tahun: <span>{{ $selectedYear ?? '' }}</span>
        </div>
    @elseif(request('start_date') || request('end_date'))
        <div class="filter-info">
            🔍 Filter aktif —
            @if(request('start_date')) Dari: <span>{{ \Carbon\Carbon::parse(request('start_date'))->translatedFormat('d M Y') }}</span> @endif
            @if(request('end_date')) Sampai: <span>{{ \Carbon\Carbon::parse(request('end_date'))->translatedFormat('d M Y') }}</span> @endif
        </div>
    @endif

    {{-- Statistik --}}
    <div class="stat-row">
        <div class="stat-card blue">
            <div class="label">Total Penjualan</div>
            <div class="value">{{ $totalSales }}</div>
            <div class="text-muted">transaksi lunas</div>
        </div>
        <div class="stat-card green">
            <div class="label">Total Pendapatan</div>
            <div class="value">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</div>
        </div>
        <div class="stat-card teal">
            <div class="label">Rata-rata Penjualan</div>
            <div class="value">Rp {{ number_format($averageSale ?? 0, 0, ',', '.') }}</div>
        </div>
        <div class="stat-card gold">
            <div class="label">Penjualan Tertinggi</div>
            <div class="value">Rp {{ number_format($maxSale ?? 0, 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- Tabel --}}
    <table>
        <thead>
            <tr>
                <th style="width:5%">No.</th>
                <th style="width:15%">No. Pesanan</th>
                <th style="width:18%">Pelanggan</th>
                <th style="width:13%">Tanggal Lunas</th>
                <th style="width:22%">Produk</th>
                <th style="width:14%">Total Pembayaran</th>
                <th style="width:8%">Metode</th>
                <th style="width:5%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($allSales as $index => $sale)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="text-primary">{{ $sale->nomor_pesanan }}</td>
                <td>
                    <div class="fw-bold">{{ $sale->customer->name ?? 'Pelanggan Tidak Ditemukan' }}</div>
                    <div class="text-muted">{{ $sale->customer->email ?? '-' }}</div>
                </td>
                <td>
                    @if($sale->pembayaran_at)
                        <div>{{ $sale->pembayaran_at->format('d M Y') }}</div>
                        <div class="text-muted">{{ $sale->pembayaran_at->format('H:i') }} WIB</div>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td>
                    @if($sale->detailPesanan->isNotEmpty())
                        @foreach($sale->detailPesanan->take(3) as $detail)
                            <div>{{ $detail->katalogIkan->nama_produk ?? 'Produk' }} <span class="text-muted">×{{ $detail->kuantitas }}</span></div>
                        @endforeach
                        @if($sale->detailPesanan->count() > 3)
                            <div class="text-muted">+{{ $sale->detailPesanan->count() - 3 }} item lainnya</div>
                        @endif
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td class="text-success">Rp {{ number_format($sale->total_pembayaran, 0, ',', '.') }}</td>
                <td>
                    @php $metode = $sale->pembayaran->metode_pembayaran ?? null; @endphp
                    {{ $metode ? ucwords(str_replace('_', ' ', $metode)) : '-' }}
                </td>
                <td><span class="badge-lunas">Lunas</span></td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center;padding:20px;color:#888;">
                    Tidak ada data penjualan
                </td>
            </tr>
            @endforelse
        </tbody>
        @if($allSales->isNotEmpty())
        <tfoot>
            <tr class="table-footer">
                <td colspan="5" style="text-align:right;">TOTAL KESELURUHAN</td>
                <td>Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</td>
                <td colspan="2">{{ $totalSales }} transaksi</td>
            </tr>
        </tfoot>
        @endif
    </table>

    {{-- Footer dokumen --}}
    <div class="print-footer">
        <span>© {{ date('Y') }} Athaya Fish Farm — Laporan Penjualan Pemilik</span>
        <span>{{ $totalSales }} transaksi lunas | Total: Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</span>
    </div>

</body>
</html>
