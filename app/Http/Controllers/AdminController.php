<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\KatalogIkan;
use App\Models\Pesanan;
use App\Models\PenawaranBudidaya;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function index()
    {
        try {
            $totalUsers = User::count();
            $totalOrders = Pesanan::count();
            $totalRevenue = Penjualan::sum('Total_Pendapatan') ?? 0;
            $pendingOrders = Pesanan::where('status', 'pending')->count();

            $recentOrders = Pesanan::with('customer')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            $statistik = [
                'total_users' => $totalUsers,
                'total_orders' => $totalOrders,
                'total_revenue' => $totalRevenue,
                'pending_orders' => $pendingOrders,
                'total_customers' => User::where('role', 'customer')->count(),
                'total_pemilik' => User::where('role', 'pemilik')->count(),
                'pesanan_selesai' => Pesanan::where('status', 'selesai')->count(),
                'total_produk' => KatalogIkan::count(),
                'total_pesanan' => $totalOrders,
                'pesanan_pending' => $pendingOrders,
            ];

            return view('admin.dashboard', [
                'statistik' => $statistik,
                'recentOrders' => $recentOrders,
            ]);
        } catch (\Exception $e) {
            \Log::error('Admin Dashboard Error: ' . $e->getMessage());
            return view('admin.dashboard', [
                'statistik' => [],
                'recentOrders' => collect(),
            ]);
        }
    }

    // ===== MENU MANAGEMENT =====
    
    /**
     * Display list of menu
     */
    public function indexMenu()
    {
        $menu = KatalogIkan::paginate(15);
        return view('admin.menu.index', ['menu' => $menu]);
    }

    /**
     * Show form create menu
     */
    public function createMenu()
    {
        $jenisIkan = \App\Models\JenisIkan::orderBy('nama_jenis')->get();
        return view('admin.menu.create', compact('jenisIkan'));
    }

    /**
     * Store menu
     */
    public function storeMenu(Request $request)
    {
        $validated = $request->validate([
            'jenis_ikan_id' => 'required|exists:jenis_ikan,id',
            'nama_produk'   => 'required|string|max:255',
            'harga_satuan'  => 'required|numeric|min:0',
            'stok'          => 'required|integer|min:0',
            'berat_gram'    => 'nullable|integer|min:0',
            'deskripsi'     => 'nullable|string',
            'gambar'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['tersedia'] = $request->has('tersedia');

        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('produk', 'public');
            $validated['gambar'] = $gambarPath;
        }

        KatalogIkan::create($validated);
        return redirect()->route('admin.menu.index')->with('success', 'Produk berhasil ditambahkan');
    }

    /**
     * Show form edit menu
     */
    public function editMenu(KatalogIkan $menu)
    {
        $jenisIkan = \App\Models\JenisIkan::orderBy('nama_jenis')->get();
        return view('admin.menu.edit', compact('menu', 'jenisIkan'));
    }

    /**
     * Update menu
     */
    public function updateMenu(Request $request, KatalogIkan $menu)
    {
        $validated = $request->validate([
            'jenis_ikan_id' => 'required|exists:jenis_ikan,id',
            'nama_produk'   => 'required|string|max:255',
            'harga_satuan'  => 'required|numeric|min:0',
            'stok'          => 'required|integer|min:0',
            'berat_gram'    => 'nullable|integer|min:0',
            'deskripsi'     => 'nullable|string',
            'gambar'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['tersedia'] = $request->has('tersedia');

        if ($request->hasFile('gambar')) {
            // Delete old image if exists
            if ($menu->gambar && \Illuminate\Support\Facades\Storage::disk('public')->exists($menu->gambar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($menu->gambar);
            }
            $gambarPath = $request->file('gambar')->store('produk', 'public');
            $validated['gambar'] = $gambarPath;
        }

        $menu->update($validated);
        return redirect()->route('admin.menu.index')->with('success', 'Produk berhasil diperbarui');
    }

    /**
     * Delete menu
     */
    public function destroyMenu(KatalogIkan $menu)
    {
        if ($menu->gambar && \Illuminate\Support\Facades\Storage::disk('public')->exists($menu->gambar)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($menu->gambar);
        }
        $menu->delete();
        return redirect()->route('admin.menu.index')->with('success', 'Produk berhasil dihapus');
    }

    /**
     * Delete selected menu (bulk delete)
     */
    public function destroyBulkMenu(Request $request)
    {
        $validated = $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer|exists:katalog_ikan,id',
        ]);

        $produk = KatalogIkan::whereIn('id', $validated['ids'])->get();

        foreach ($produk as $item) {
            if ($item->gambar && \Illuminate\Support\Facades\Storage::disk('public')->exists($item->gambar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($item->gambar);
            }
        }

        KatalogIkan::whereIn('id', $validated['ids'])->delete();

        return redirect()->route('admin.menu.index')
            ->with('warning', count($validated['ids']) . ' produk berhasil dihapus secara permanen.');
    }

    /**
     * Delete ALL menu products
     */
    public function destroyAllMenu()
    {
        $produk = KatalogIkan::all();

        foreach ($produk as $item) {
            if ($item->gambar && \Illuminate\Support\Facades\Storage::disk('public')->exists($item->gambar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($item->gambar);
            }
            $item->delete();
        }

        return redirect()->route('admin.menu.index')
            ->with('warning', 'Semua produk berhasil dihapus secara permanen.');
    }

    // ===== ORDER MANAGEMENT =====

    /**
     * Display list of orders
     */
    public function indexOrder()
    {
        $orders = Pesanan::with('customer', 'detailPesanan')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.order.index', ['orders' => $orders]);
    }

    /**
     * Show order detail
     */
    public function showOrder(Pesanan $order)
    {
        $order->load('customer', 'detailPesanan.katalogIkan');
        return view('admin.order.show', ['order' => $order]);
    }

    /**
     * Update order status
     */
    public function updateOrderStatus(Request $request, Pesanan $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,dikonfirmasi,ditolak,pembayaran,lunas,persiapan,dikirim,selesai,batal',
        ]);

        $order->update($validated);
        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui');
    }

    /**
     * Delete order
     */
    public function destroyOrder(Pesanan $order)
    {
        $order->delete();
        return redirect()->route('admin.order.index')->with('success', 'Pesanan berhasil dihapus');
    }

    // ===== USER MANAGEMENT =====

    /**
     * Display list of users
     */
    public function indexUser()
    {
        $users = User::paginate(15);
        return view('admin.user.index', ['users' => $users]);
    }

    /**
     * Show form create user
     */
    public function createUser()
    {
        return view('admin.user.create');
    }

    /**
     * Store new user
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'nomor_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
            'role' => 'required|in:user,admin,pemilik',
        ]);

        $validated['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);

        User::create($validated);
        return redirect()->route('admin.user.index')->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Show user detail
     */
    public function showUser(User $user)
    {
        $user->load('pesanan', 'penawaranBudidaya');
        return view('admin.user.show', ['user' => $user]);
    }

    /**
     * Edit user
     */
    public function editUser(User $user)
    {
        return view('admin.user.edit', ['user' => $user]);
    }

    /**
     * Update user
     */
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'nomor_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
            'role' => 'required|in:user,admin,pemilik',
        ]);

        $user->update($validated);
        return redirect()->route('admin.user.index')->with('success', 'User berhasil diperbarui');
    }

    /**
     * Delete user
     */
    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Tidak bisa menghapus user yang sedang login');
        }

        $user->delete();
        return redirect()->route('admin.user.index')->with('success', 'User berhasil dihapus');
    }

    // ===== SALES REPORT =====

    /**
     * Display sales report — sumber data: Pesanan dengan status 'lunas'
     */
    public function salesReport(Request $request)
    {
        // Hanya pemilik yang bisa akses laporan penjualan
        abort_unless(auth()->user()->isPemilik(), 403, 'Fitur ini hanya tersedia untuk Pemilik.');
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

        // Statistik — scope sama dengan filter
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

        return view('admin.report.sales', [
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
     * Print-friendly version of sales report (all data, no pagination)
     */
    public function printSalesReport(Request $request)
    {
        // Hanya pemilik yang bisa akses laporan penjualan
        abort_unless(auth()->user()->isPemilik(), 403, 'Fitur ini hanya tersedia untuk Pemilik.');
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

        // Statistik
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

        return view('admin.report.print-sales', [
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




    // ===== BUDIDAYA MANAGEMENT =====

    public function indexBudidaya()
    {
        $penawaran = PenawaranBudidaya::with('user')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.budidaya.index', compact('penawaran'));
    }

    public function updateStatusBudidaya(Request $request, PenawaranBudidaya $penawaranBudidaya)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,diterima,ditolak',
        ]);

        $penawaranBudidaya->update(['status' => $validated['status']]);

        return redirect()->route('admin.budidaya.index')->with('success', 'Status penawaran budidaya berhasil diperbarui');
    }
}
