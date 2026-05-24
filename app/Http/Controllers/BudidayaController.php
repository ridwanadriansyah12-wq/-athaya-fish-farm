<?php

namespace App\Http\Controllers;

use App\Models\PenawaranBudidaya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BudidayaController extends Controller
{
    /**
     * Tampilkan form penawaran budidaya
     */
    public function create()
    {
        return view('budidaya.create');
    }

    /**
     * Simpan data penawaran budidaya
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_ikan'     => 'required|string|max:255',
            'nomor_whatsapp' => 'required|string|max:20',
            'jumlah_ikan'    => 'required|integer|min:1000',
            'deskripsi'      => 'nullable|string|max:1000',
            'foto'           => 'nullable|array|max:5',
            'foto.*'         => 'image|mimes:jpeg,png,jpg,webp|max:3072', // max 3MB per foto
        ], [
            'foto.max'   => 'Maksimal 5 foto yang dapat diunggah.',
            'foto.*.image' => 'File harus berupa gambar.',
            'foto.*.mimes' => 'Format gambar harus JPEG, PNG, JPG, atau WEBP.',
            'foto.*.max'   => 'Ukuran setiap foto maksimal 3MB.',
        ]);

        // Upload foto jika ada
        $fotoPaths = [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $file->store('budidaya', 'public');
                $fotoPaths[] = $path;
            }
        }

        PenawaranBudidaya::create([
            'user_id'        => Auth::id(),
            'jenis_ikan'     => $validated['jenis_ikan'],
            'nomor_whatsapp' => $validated['nomor_whatsapp'],
            'jumlah_ikan'    => $validated['jumlah_ikan'],
            'deskripsi'      => $validated['deskripsi'] ?? null,
            'foto'           => !empty($fotoPaths) ? $fotoPaths : null,
            'status'         => 'pending',
        ]);

        return redirect()->route('budidaya.index')
            ->with('success', 'Penawaran budidaya Anda berhasil dikirim dan sedang dalam proses verifikasi oleh tim kami.');
    }

    /**
     * Tampilkan riwayat penawaran budidaya milik customer
     */
    public function index()
    {
        $penawaran = PenawaranBudidaya::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('budidaya.index', compact('penawaran'));
    }
}
