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
            $totalRevenue   = Penjualan::sum('Total_Pendapatan') ?? 0;
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

            // Revenue last 30 days
            $revenueLastMonth = Penjualan::where('Tanggal_Penjualan', '>=', Carbon::now()->subDays(30))
                ->sum('Total_Pendapatan') ?? 0;

            return view('pemilik.dashboard', [
                'totalOrders'      => $totalOrders,
                'totalRevenue'     => $totalRevenue,
                'totalCustomers'   => $totalCustomers,
                'totalProduk'      => $totalProduk,
                'recentOrders'     => $recentOrders,
                'orderStatus'      => $orderStatus,
                'revenueLastMonth' => $revenueLastMonth,
            ]);
        } catch (\Exception $e) {
            \Log::error('Pemilik Dashboard Error: ' . $e->getMessage());
            return view('pemilik.dashboard', [
                'totalOrders'      => 0,
                'totalRevenue'     => 0,
                'totalCustomers'   => 0,
                'totalProduk'      => 0,
                'recentOrders'     => collect(),
                'orderStatus'      => [],
                'revenueLastMonth' => 0,
            ]);
        }
    }

    /**
     * Display sales report (read-only) — sumber: Pesanan status 'lunas'
     */
    public function salesReport(Request $request)
    {
        $query = Pesanan::with('customer', 'detailPesanan.katalogIkan', 'pembayaran')
            ->where('status', 'lunas');

        if ($request->filled('start_date')) {
            $query->whereDate('pembayaran_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('pembayaran_at', '<=', $request->end_date);
        }

        $sales = $query->orderBy('pembayaran_at', 'desc')->paginate(15)->withQueryString();

        // Stats
        $statsQuery = Pesanan::where('status', 'lunas');
        if ($request->filled('start_date')) {
            $statsQuery->whereDate('pembayaran_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $statsQuery->whereDate('pembayaran_at', '<=', $request->end_date);
        }

        $totalRevenue = $statsQuery->sum('total_pembayaran');
        $totalSales   = $statsQuery->count();
        $averageSale  = $totalSales > 0 ? $statsQuery->avg('total_pembayaran') : 0;
        $maxSale      = $statsQuery->max('total_pembayaran') ?? 0;

        return view('pemilik.sales-report', [
            'sales'        => $sales,
            'totalRevenue' => $totalRevenue,
            'totalSales'   => $totalSales,
            'averageSale'  => $averageSale,
            'maxSale'      => $maxSale,
        ]);
    }

    /**
     * Print-friendly version of sales report for pemilik (all data, no pagination)
     */
    public function printSalesReport(Request $request)
    {
        $query = Pesanan::with('customer', 'detailPesanan.katalogIkan', 'pembayaran')
            ->where('status', 'lunas');

        if ($request->filled('start_date')) {
            $query->whereDate('pembayaran_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('pembayaran_at', '<=', $request->end_date);
        }

        $allSales = $query->orderBy('pembayaran_at', 'desc')->get();

        $statsQuery = Pesanan::where('status', 'lunas');
        if ($request->filled('start_date')) {
            $statsQuery->whereDate('pembayaran_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $statsQuery->whereDate('pembayaran_at', '<=', $request->end_date);
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
        ]);
    }
}
