<?php

namespace App\Http\Controllers;

use App\Models\KatalogIkan;
use App\Models\JenisIkan;
use Illuminate\View\View;
use Illuminate\Http\Request;

class KatalogController extends Controller
{
    /**
     * Show katalog ikan — dengan filter, search, dan sort
     */
    public function index(Request $request): View
    {
        $query = KatalogIkan::with('jenisIkan')->where('tersedia', true);

        // Filter jenis ikan
        if ($request->filled('jenis')) {
            $query->where('jenis_ikan_id', $request->jenis);
        }

        // Search nama produk
        if ($request->filled('search')) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        // Sort (harga_asc, harga_desc, terbaru, stok)
        $sort = $request->get('sort', 'terbaru');
        match ($sort) {
            'harga_asc'  => $query->orderBy('harga_satuan', 'asc'),
            'harga_desc' => $query->orderBy('harga_satuan', 'desc'),
            'stok'       => $query->orderBy('stok', 'desc'),
            default      => $query->latest(),
        };

        $katalog   = $query->paginate(12)->withQueryString();
        $jenisIkan = JenisIkan::whereHas('katalogIkan', fn($q) => $q->where('tersedia', true))
                        ->orderBy('nama_jenis')
                        ->get();

        // Hitung total produk tersedia untuk info
        $totalTersedia = KatalogIkan::where('tersedia', true)->count();

        return view('katalog.index', compact('katalog', 'jenisIkan', 'sort', 'totalTersedia'));
    }

    /**
     * Show detail produk
     */
    public function show(KatalogIkan $katalog): View
    {
        $produkSerupa = KatalogIkan::where('jenis_ikan_id', $katalog->jenis_ikan_id)
            ->where('id', '!=', $katalog->id)
            ->where('tersedia', true)
            ->with('jenisIkan')
            ->limit(4)
            ->get();

        // Ambil statistik rating simulasi (nilai jual) — bisa diganti data real nanti
        $ratingSimulasi = [
            'nilai' => 4.8,
            'count' => rand(12, 87),
        ];

        return view('katalog.show', compact('katalog', 'produkSerupa', 'ratingSimulasi'));
    }
}
