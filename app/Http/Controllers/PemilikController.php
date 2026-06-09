<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pesanan;
use App\Models\Penjualan;
use App\Models\KatalogIkan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PemilikController extends Controller
{
    /**
     * Display pemilik dashboard — overview only
     */
    public function dashboard()
    {
        try {
            $totalOrders    = Pesanan::count();
            $totalRevenue   = Pesanan::where('status', 'lunas')->sum('total_pembayaran') ?? 0;
            $totalCustomers = User::where('role', 'customer')->count();
            $totalProduk    = KatalogIkan::count();

            $recentOrders = Pesanan::with('customer')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            $orderStatus = [
                'pending'      => Pesanan::where('status', 'pending')->count(),
                'dikonfirmasi' => Pesanan::where('status', 'dikonfirmasi')->count(),
                'selesai'      => Pesanan::where('status', 'selesai')->count(),
            ];

            // Rekap penjualan LUNAS dalam 30 hari terakhir
            $thirtyDaysAgo = Carbon::now()->subDays(30)->startOfDay();

            $revenueLastMonth = Pesanan::where('status', 'lunas')
                ->where('pembayaran_at', '>=', $thirtyDaysAgo)
                ->sum('total_pembayaran') ?? 0;

            $countLastMonth = Pesanan::where('status', 'lunas')
                ->where('pembayaran_at', '>=', $thirtyDaysAgo)
                ->count();

            $recentLunas30 = Pesanan::with('customer')
                ->where('status', 'lunas')
                ->where('pembayaran_at', '>=', $thirtyDaysAgo)
                ->orderBy('pembayaran_at', 'desc')
                ->limit(5)
                ->get();

            // ── Chart: Harian (30 hari terakhir) ──
            $harianRaw = Pesanan::where('status', 'lunas')
                ->where('pembayaran_at', '>=', $thirtyDaysAgo)
                ->selectRaw('DATE(pembayaran_at) as tgl, SUM(total_pembayaran) as total, COUNT(*) as jumlah')
                ->groupBy('tgl')->orderBy('tgl')->get()->keyBy('tgl');

            $chartHarianLabels = $chartHarianData = $chartHarianCount = [];
            for ($i = 29; $i >= 0; $i--) {
                $d = Carbon::now()->subDays($i);
                $chartHarianLabels[] = $d->format('d M');
                $key = $d->format('Y-m-d');
                $chartHarianData[]  = $harianRaw->has($key) ? (float) $harianRaw[$key]->total  : 0;
                $chartHarianCount[] = $harianRaw->has($key) ? (int)   $harianRaw[$key]->jumlah : 0;
            }

            // ── Chart: Bulanan (tahun ini) ──
            $bulananRaw = Pesanan::where('status', 'lunas')
                ->whereYear('pembayaran_at', Carbon::now()->year)
                ->selectRaw('MONTH(pembayaran_at) as bln, SUM(total_pembayaran) as total, COUNT(*) as jumlah')
                ->groupBy('bln')->get()->keyBy('bln');

            $chartBulananLabels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
            $chartBulananData = $chartBulananCount = [];
            for ($m = 1; $m <= 12; $m++) {
                $chartBulananData[]  = $bulananRaw->has($m) ? (float) $bulananRaw[$m]->total  : 0;
                $chartBulananCount[] = $bulananRaw->has($m) ? (int)   $bulananRaw[$m]->jumlah : 0;
            }

            // ── Chart: Tahunan ──
            $tahunanRaw = Pesanan::where('status', 'lunas')
                ->whereNotNull('pembayaran_at')
                ->selectRaw('YEAR(pembayaran_at) as thn, SUM(total_pembayaran) as total, COUNT(*) as jumlah')
                ->groupBy('thn')->orderBy('thn')->get();

            $chartTahunanLabels = $tahunanRaw->pluck('thn')->map(fn($y) => (string)$y)->toArray();
            $chartTahunanData   = $tahunanRaw->pluck('total')->map(fn($v) => (float)$v)->toArray();
            $chartTahunanCount  = $tahunanRaw->pluck('jumlah')->map(fn($v) => (int)$v)->toArray();
            if (empty($chartTahunanLabels)) {
                $chartTahunanLabels = [(string) Carbon::now()->year];
                $chartTahunanData   = [0];
                $chartTahunanCount  = [0];
            }

            return view('pemilik.dashboard', [
                'totalOrders'         => $totalOrders,
                'totalRevenue'        => $totalRevenue,
                'totalCustomers'      => $totalCustomers,
                'totalProduk'         => $totalProduk,
                'recentOrders'        => $recentOrders,
                'orderStatus'         => $orderStatus,
                'revenueLastMonth'    => $revenueLastMonth,
                'countLastMonth'      => $countLastMonth,
                'recentLunas30'       => $recentLunas30,
                'chartHarianLabels'   => $chartHarianLabels,
                'chartHarianData'     => $chartHarianData,
                'chartHarianCount'    => $chartHarianCount,
                'chartBulananLabels'  => $chartBulananLabels,
                'chartBulananData'    => $chartBulananData,
                'chartBulananCount'   => $chartBulananCount,
                'chartTahunanLabels'  => $chartTahunanLabels,
                'chartTahunanData'    => $chartTahunanData,
                'chartTahunanCount'   => $chartTahunanCount,
            ]);
        } catch (\Exception $e) {
            \Log::error('Pemilik Dashboard Error: ' . $e->getMessage());
            return view('pemilik.dashboard', [
                'totalOrders'         => 0,
                'totalRevenue'        => 0,
                'totalCustomers'      => 0,
                'totalProduk'         => 0,
                'recentOrders'        => collect(),
                'orderStatus'         => [],
                'revenueLastMonth'    => 0,
                'countLastMonth'      => 0,
                'recentLunas30'       => collect(),
                'chartHarianLabels'   => [],
                'chartHarianData'     => [],
                'chartHarianCount'    => [],
                'chartBulananLabels'  => [],
                'chartBulananData'    => [],
                'chartBulananCount'   => [],
                'chartTahunanLabels'  => [],
                'chartTahunanData'    => [],
                'chartTahunanCount'   => [],
            ]);
        }
    }

    /**
     * Display sales report (read-only) — sumber: Pesanan status 'lunas'
     */
    public function salesReport(Request $request)
    {
        $filterType = $request->input('filter_type', 'harian');
        $selectedMonth = $request->input('month', date('m'));
        $selectedYear = $request->input('year', date('Y'));

        $query = Pesanan::with('customer', 'detailPesanan.katalogIkan', 'pembayaran')
            ->where('status', 'lunas');

        if ($filterType === 'bulanan') {
            $query->whereYear('pembayaran_at', $selectedYear)->whereMonth('pembayaran_at', $selectedMonth);
        } elseif ($filterType === 'tahunan') {
            $query->whereYear('pembayaran_at', $selectedYear);
        } else {
            if ($request->filled('start_date')) {
                $query->whereDate('pembayaran_at', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $query->whereDate('pembayaran_at', '<=', $request->end_date);
            }
        }

        $sales = $query->orderBy('pembayaran_at', 'desc')->paginate(15)->withQueryString();

        // Stats
        $statsQuery = Pesanan::where('status', 'lunas');
        if ($filterType === 'bulanan') {
            $statsQuery->whereYear('pembayaran_at', $selectedYear)->whereMonth('pembayaran_at', $selectedMonth);
        } elseif ($filterType === 'tahunan') {
            $statsQuery->whereYear('pembayaran_at', $selectedYear);
        } else {
            if ($request->filled('start_date')) {
                $statsQuery->whereDate('pembayaran_at', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $statsQuery->whereDate('pembayaran_at', '<=', $request->end_date);
            }
        }

        $totalRevenue = $statsQuery->sum('total_pembayaran');
        $totalSales   = $statsQuery->count();
        $averageSale  = $totalSales > 0 ? $statsQuery->avg('total_pembayaran') : 0;
        $maxSale      = $statsQuery->max('total_pembayaran') ?? 0;

        $availableYears = Pesanan::where('status', 'lunas')
            ->whereNotNull('pembayaran_at')
            ->selectRaw('YEAR(pembayaran_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
        if (empty($availableYears)) {
            $availableYears = [date('Y')];
        }

        return view('pemilik.sales-report', [
            'sales'          => $sales,
            'totalRevenue'   => $totalRevenue,
            'totalSales'     => $totalSales,
            'averageSale'    => $averageSale,
            'maxSale'        => $maxSale,
            'filterType'     => $filterType,
            'selectedMonth'  => $selectedMonth,
            'selectedYear'   => $selectedYear,
            'availableYears' => $availableYears,
        ]);
    }

    /**
     * Print-friendly version of sales report for pemilik (all data, no pagination)
     */
    public function printSalesReport(Request $request)
    {
        $filterType = $request->input('filter_type', 'harian');
        $selectedMonth = $request->input('month', date('m'));
        $selectedYear = $request->input('year', date('Y'));

        $query = Pesanan::with('customer', 'detailPesanan.katalogIkan', 'pembayaran')
            ->where('status', 'lunas');

        if ($filterType === 'bulanan') {
            $query->whereYear('pembayaran_at', $selectedYear)->whereMonth('pembayaran_at', $selectedMonth);
        } elseif ($filterType === 'tahunan') {
            $query->whereYear('pembayaran_at', $selectedYear);
        } else {
            if ($request->filled('start_date')) {
                $query->whereDate('pembayaran_at', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $query->whereDate('pembayaran_at', '<=', $request->end_date);
            }
        }

        $allSales = $query->orderBy('pembayaran_at', 'desc')->get();

        $statsQuery = Pesanan::where('status', 'lunas');
        if ($filterType === 'bulanan') {
            $statsQuery->whereYear('pembayaran_at', $selectedYear)->whereMonth('pembayaran_at', $selectedMonth);
        } elseif ($filterType === 'tahunan') {
            $statsQuery->whereYear('pembayaran_at', $selectedYear);
        } else {
            if ($request->filled('start_date')) {
                $statsQuery->whereDate('pembayaran_at', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $statsQuery->whereDate('pembayaran_at', '<=', $request->end_date);
            }
        }

        $totalRevenue = $statsQuery->sum('total_pembayaran');
        $totalSales   = $statsQuery->count();
        $averageSale  = $totalSales > 0 ? $statsQuery->avg('total_pembayaran') : 0;
        $maxSale      = $statsQuery->max('total_pembayaran') ?? 0;

        return view('pemilik.print-sales-report', [
            'allSales'     => $allSales,
            'totalRevenue' => $totalRevenue,
            'totalSales'   => $totalSales,
            'averageSale'  => $averageSale,
            'maxSale'      => $maxSale,
            'filterType'   => $filterType,
            'selectedMonth'=> $selectedMonth,
            'selectedYear' => $selectedYear,
        ]);
    }
}
