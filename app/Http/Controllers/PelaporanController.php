<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pelaporan;
use Illuminate\Support\Facades\Storage;

class PelaporanController extends Controller
{

    public function create(Request $request)
    {
        // Validasi dan penyimpanan laporan
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'provinsi' => 'required|string|max:255', // Memastikan provinsi adalah string (nama)
            'lokasi' => 'required|string',
            'jenis_laporan' => 'required|in:Pembuangan Sampah Laut,Penangkapan Ikan Illegal',
            'gambar' => 'nullable|file',
        ]);

         // Upload foto jika ada
         $fotoPath = null;
         if ($request->hasFile('gambar')) {
             $file = $request->file('gambar');
 
             // Cek apakah file benar-benar ada
             if ($file->isValid()) {
                 $fileName = $file->getClientOriginalName();
                 $file->move(public_path('storage/uploads'), $fileName);
                 $fotoPath = 'uploads/' . $fileName; // Lokasi file di server
             } else {
                 return response()->json([
                     'message' => 'Foto pengaduan tidak valid.',
                 ], 400);
             }
         } else {
             $fotoPath = null;
         }

        $laporan = pelaporan::create([
            'nama'=> $request->nama,
            'provinsi' => $request->provinsi,
            'lokasi' => $request->lokasi,
            'jenis_laporan' => $request->jenis_laporan,
            'gambar' => $fotoPath,
        ]);

        return response()->json(['message' => 'Laporan berhasil disimpan', 'data' => $laporan], 201);
    }

    // Mendapatkan semua laporan
    public function get()
{
    $laporan = pelaporan::all();

    // Menambahkan URL gambar agar frontend bisa mengaksesnya
    foreach ($laporan as $item) {
        $item->gambar_url = $item->gambar ? asset('storage/' . $item->gambar) : null;
    }

    return response()->json($laporan);
}

    // Menampilkan laporan berdasarkan ID
    public function show($id_pelaporan)
    {
        $laporan = pelaporan::find($id_pelaporan);

        if (!$laporan) {
            return response()->json(['message' => 'Laporan tidak ditemukan'], 404);
        }

        $laporan->gambar_url = $laporan->gambar ? asset('storage/' . $laporan->gambar) : null;

        return response()->json($laporan);
    }

    // Mengupdate laporan
    public function update(Request $request, $id_pelaporan)
    {
        // Mencari laporan berdasarkan ID
        $laporan = pelaporan::find($id_pelaporan);

        // Jika laporan tidak ditemukan
        if (!$laporan) {
            return response()->json(['message' => 'Laporan tidak ditemukan'], 404);
        }

        // Validasi data (memastikan hanya status yang wajib diubah, dan gambar jika ada)
        $validated = $request->validate([
            'nama' => 'sometimes|required|string|max:255',
            'provinsi' => 'sometimes|required|string|max:255',
            'lokasi' => 'sometimes|required|string',
            'jenis_laporan' => 'sometimes|required|in:Pembuangan Sampah Laut,Penangkapan Ikan Illegal',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'sometimes|required|in:Belum Diproses,Sedang Diproses,Selesai Diproses', // Menambahkan validasi status
        ]);

        // Mengupdate status jika ada
        if ($request->has('status')) {
            $laporan->status = $request->status; // Mengupdate status yang baru
        }

        // Jika ada gambar baru, proses pengunggahan gambar
        if ($request->hasFile('gambar')) {
            // Menghapus gambar yang lama jika ada
            if ($laporan->gambar) {
                Storage::disk('public')->delete($laporan->gambar);
            }

            // Mengunggah gambar baru
            $file = $request->file('gambar');
            $path = $file->store('pelaporan_gambar', 'public');
            $laporan->gambar = $path; // Menyimpan path gambar baru
        }

        // Mengupdate laporan dengan data yang telah divalidasi dan gambar baru jika ada
        $laporan->update($validated);

        // Mengembalikan response sukses dengan data laporan terbaru
        return response()->json(['message' => 'Laporan berhasil diupdate', 'data' => $laporan]);
    }

    // Menghapus laporan beserta gambar
    public function delete($id_pelaporan)
    {
        $laporan = pelaporan::find($id_pelaporan);

        if (!$laporan) {
            return response()->json(['message' => 'Laporan tidak ditemukan'], 404);
        }

        if ($laporan->gambar) {
            Storage::disk('public')->delete($laporan->gambar);
        }

        $laporan->delete();

        return response()->json(['message' => 'Laporan berhasil dihapus']);
    }

    public function getStatistikHarian()
    {
        // Mengambil jumlah laporan berdasarkan tanggal
        $laporanPerHari = pelaporan::selectRaw('DATE(created_at) as tanggal, COUNT(*) as jumlah')
                                    ->groupBy('tanggal')
                                    ->orderBy('tanggal', 'asc')
                                    ->get();

        // Mengubah data agar lebih mudah digunakan untuk chart
        $tanggal = $laporanPerHari->pluck('tanggal');
        $jumlahLaporan = $laporanPerHari->pluck('jumlah');

        return response()->json([
            'tanggal' => $tanggal,
            'jumlah_laporan' => $jumlahLaporan,
        ]);
    }

}
