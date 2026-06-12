<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\KatalogIkan;
use App\Models\JenisIkan;


use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    /**
     * Show dashboard
     */
    public function index(): View|RedirectResponse
    {
        $user = auth()->user();

        if ($user->isCustomer()) {
            $pesanan = Pesanan::where('customer_id', $user->id)->latest()->get();
            $statistik = [
                'total_pesanan' => $pesanan->count(),
                'pesanan_pending' => $pesanan->where('status', 'pending')->count(),
                'pesanan_selesai' => $pesanan->where('status', 'selesai')->count(),
                'total_belanja' => $pesanan->sum('total_pembayaran')
            ];
            return view('customer.dashboard', compact('user', 'pesanan', 'statistik'));
        } elseif ($user->isPemilik()) {
            // PEMILIK = redirect to dedicated pemilik dashboard
            return redirect()->route('pemilik.dashboard');
        } else {
            // ADMIN = Full access management dashboard
            $pesanan = Pesanan::with(['customer', 'detailPesanan'])->latest()->get();
            $users = \App\Models\User::all();
            $statistik = [
                'total_users' => $users->count(),
                'total_customers' => $users->where('role', 'customer')->count(),
                'total_pemilik' => $users->where('role', 'pemilik')->count(),
                'total_pesanan' => $pesanan->count(),
                'pesanan_pending' => $pesanan->where('status', 'pending')->count(),
                'pesanan_selesai' => $pesanan->where('status', 'selesai')->count(),
                'total_produk' => KatalogIkan::count()
            ];
            return view('admin.dashboard', compact('user', 'pesanan', 'users', 'statistik'));
        }
    }
}
