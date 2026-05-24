<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Keranjang;
use App\Models\Penjualan;
use App\Models\Pemesanan;
use App\Models\DetailPemesanan;
use App\Models\PreOrder;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Display dashboard customer
     */
    public function index()
    {
        $user = Auth::user();
        $totalPembelian = $user->penjualan()->count();
        $totalPemesanan = $user->pemesanan()->count();
        $totalPreOrder = $user->preOrder()->count();
        $keranjang = $user->keranjang()->with('menu')->get();
        $totalKeranjang = $keranjang->count();

        return view('customer.dashboard', [
            'user' => $user,
            'totalPembelian' => $totalPembelian,
            'totalPemesanan' => $totalPemesanan,
            'totalPreOrder' => $totalPreOrder,
            'keranjang' => $keranjang,
            'totalKeranjang' => $totalKeranjang,
        ]);
    }

    /**
     * Show profile customer
     */
    public function showProfile()
    {
        $user = Auth::user();
        return view('customer.profile', ['user' => $user]);
    }

    /**
     * Edit profile customer
     */
    public function editProfile()
    {
        $user = Auth::user();
        return view('customer.edit-profile', ['user' => $user]);
    }

    /**
     * Update profile customer
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'nomor_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
        ]);

        $user->update($validated);
        return redirect()->route('customer.profile')->with('success', 'Profil berhasil diperbarui');
    }

    /**
     * Show cart / keranjang
     */
    public function showCart()
    {
        $user = Auth::user();
        $keranjang = $user->keranjang()->with('menu')->get();
        $totalHarga = $keranjang->sum(function($item) {
            return $item->jumlah * $item->menu->Harga;
        });

        return view('customer.cart', [
            'keranjang' => $keranjang,
            'totalHarga' => $totalHarga,
        ]);
    }

    /**
     * Show orders (Penjualan)
     */
    public function showOrders()
    {
        $user = Auth::user();
        $penjualan = $user->penjualan()->with('detailPenjualan')->orderBy('Tanggal_Penjualan', 'desc')->get();

        return view('customer.orders', ['penjualan' => $penjualan]);
    }

    /**
     * Show pemesanan (orders)
     */
    public function showPemesanan()
    {
        $user = Auth::user();
        $pemesanan = $user->pemesanan()->with('detailPemesanan')->orderBy('created_at', 'desc')->get();

        return view('customer.pemesanan', ['pemesanan' => $pemesanan]);
    }

    /**
     * Show pre-order
     */
    public function showPreOrder()
    {
        $user = Auth::user();
        $preOrder = $user->preOrder()->with('detailPreOrder')->orderBy('created_at', 'desc')->get();

        return view('customer.pre-order', ['preOrder' => $preOrder]);
    }

    /**
     * Show menu / katalog produk
     */
    public function showMenu()
    {
        $menu = Menu::paginate(12);
        return view('customer.menu', ['menu' => $menu]);
    }

    /**
     * Show menu detail
     */
    public function showMenuDetail($id)
    {
        $menu = Menu::findOrFail($id);
        return view('customer.menu-detail', ['menu' => $menu]);
    }

    /**
     * Create pemesanan / order
     */
    public function createPemesanan()
    {
        $user = Auth::user();
        $keranjang = $user->keranjang()->with('menu')->get();
        
        if ($keranjang->isEmpty()) {
            return redirect()->route('customer.cart')->with('error', 'Keranjang kosong');
        }

        return view('customer.create-pemesanan', ['keranjang' => $keranjang]);
    }

    /**
     * Store pemesanan / order
     */
    public function storePemesanan(Request $request)
    {
        $user = Auth::user();
        $keranjang = $user->keranjang()->with('menu')->get();

        if ($keranjang->isEmpty()) {
            return redirect()->route('customer.cart')->with('error', 'Keranjang kosong');
        }

        $validated = $request->validate([
            'Nama_Pemesan' => 'required|string|max:255',
            'Alamat' => 'required|string|max:500',
        ]);

        $totalHarga = $keranjang->sum(function($item) {
            return $item->jumlah * $item->menu->Harga;
        });

        $pemesanan = Pemesanan::create([
            'User_ID' => $user->id,
            'Nama_Pemesan' => $validated['Nama_Pemesan'],
            'Alamat' => $validated['Alamat'],
            'Total_Harga' => $totalHarga,
            'Status' => 'pending',
        ]);

        // Create detail pemesanan
        foreach ($keranjang as $item) {
            DetailPemesanan::create([
                'ID_Pemesanan' => $pemesanan->id,
                'Menu_ID' => $item->id_produk,
                'Harga_partai' => $item->menu->Harga,
                'Jumlah' => $item->jumlah,
                'Subtotal' => $item->jumlah * $item->menu->Harga,
            ]);
        }

        // Clear cart
        $user->keranjang()->delete();

        return redirect()->route('customer.pemesanan')->with('success', 'Pesanan berhasil dibuat');
    }

    /**
     * Add item to cart
     */
    public function addToCart(Request $request, $id)
    {
        $user = Auth::user();
        $menu = Menu::findOrFail($id);
        $jumlah = $request->input('jumlah', 1);

        // Check if item already in cart
        $existing = Keranjang::where('id_user', $user->id)
            ->where('id_produk', $id)
            ->first();

        if ($existing) {
            $existing->jumlah += $jumlah;
            $existing->save();
        } else {
            Keranjang::create([
                'id_user' => $user->id,
                'id_produk' => $id,
                'jumlah' => $jumlah,
            ]);
        }

        return redirect()->route('customer.menu')->with('success', $menu->Nama_Produk . ' ditambahkan ke keranjang');
    }

    /**
     * Update cart item
     */
    public function updateCart(Request $request, $id)
    {
        $keranjang = Keranjang::findOrFail($id);
        $jumlah = $request->input('jumlah', 1);

        if ($jumlah > 0) {
            $keranjang->update(['jumlah' => $jumlah]);
        }

        return redirect()->route('customer.cart')->with('success', 'Keranjang berhasil diperbarui');
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart($id)
    {
        $keranjang = Keranjang::findOrFail($id);
        $keranjang->delete();

        return redirect()->route('customer.cart')->with('success', 'Item dihapus dari keranjang');
    }
}
