<?php

namespace App\Http\Controllers;

use App\Models\KatalogIkan;
use App\Models\JenisIkan;
use Illuminate\View\View;
use Illuminate\Http\Request;

class KatalogController extends Controller
{
    /**
     * Show katalog ikan
     */
    public function index(Request $request): View
    {
        $query = KatalogIkan::query();

        if ($request->filled('jenis')) {
            $query->where('jenis_ikan_id', $request->jenis);
        }

        if ($request->filled('search')) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        $katalog = $query->where('tersedia', true)->with('jenisIkan')->paginate(12);
        $jenisIkan = JenisIkan::all();

        return view('katalog.index', compact('katalog', 'jenisIkan'));
    }

    /**
     * Show detail produk
     */
    public function show(KatalogIkan $katalog): View
    {
        $produkSerupa = KatalogIkan::where('jenis_ikan_id', $katalog->jenis_ikan_id)
            ->where('id', '!=', $katalog->id)
            ->with('jenisIkan')
            ->limit(4)
            ->get();

        return view('katalog.show', compact('katalog', 'produkSerupa'));
    }
}
