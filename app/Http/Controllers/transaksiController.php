<?php

namespace App\Http\Controllers;

use Log;
use App\Models\t_jual;
use App\Models\t_djual;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class transaksiController extends Controller
{
    public function inputHeader(Request $request)
    {
        // dd($request);
        $validateNewData = $request -> validate([
            'no_faktur' => 'required|max:6|unique:t_juals,no_faktur',
            'kode_customer' => 'required|max:4',
            'tgl_faktur' => 'required|date|date_format:Y-m-d',
            'kode_tjen' => 'required|max:1',
            'total_bruto' => 'required|numeric',
            'total_diskon' => 'required|numeric',
            'total_jumlah' => 'required|numeric',
        ]);

        t_jual::create($validateNewData);

        return response()->json([
            'method' => 'POST',
            'status' => 200,
            'data' => $validateNewData
        ]);
    }

    // public function tambahBarang(Request $request) 
    // {
    //     // Validasi data yang diterima
    //     $validatedData = $request->validate([
    //         'items' => 'required|array',
    //         'items.*.no_faktur' => 'required|max:6',
    //         'items.*.kode_barang' => 'required|max:10',
    //         'items.*.harga' => 'required|numeric',
    //         'items.*.qty' => 'required|integer|min:1',
    //         'items.*.diskon' => 'required|numeric|min:0|max:100',
    //         'items.*.bruto' => 'required|numeric',
    //         'items.*.jumlah' => 'required|numeric',
    //     ]);

    //     // Array untuk menyimpan item yang sudah ada dan yang baru
    //     $existingItems = [];
    //     $newItems = [];

    //     // Loop melalui setiap item dan lakukan pengecekan sebelum disimpan
    //     foreach ($validatedData['items'] as $item) {
    //         // Pengecekan apakah data dengan no_faktur dan kode_barang sudah ada di database
    //         $exists = t_djual::where('no_faktur', $item['no_faktur'])
    //                         ->where('kode_barang', $item['kode_barang'])
    //                         ->exists();

    //         if ($exists) {
    //             // Jika data sudah ada, simpan ke array existingItems
    //             $existingItems[] = $item;
    //         } else {
    //             // Jika tidak ada, simpan item baru ke database
    //             t_djual::create($item);
    //             $newItems[] = $item;
    //         }
    //     }

    //     // Mengembalikan respons ke front-end
    //     if (count($existingItems) > 0) {
    //         return response()->json([
    //             'status' => 400,
    //             'message' => 'Beberapa data sudah ada di database.',
    //             'existingItems' => $existingItems,
    //             'newItems' => $newItems
    //         ]);
    //     }

    //     return response()->json([
    //         'method' => 'POST',
    //         'status' => 200,
    //         'message' => 'Data berhasil disimpan.',
    //         'newItems' => $newItems
    //     ]);
    // }


    // public function updateDataFaktur(Request $request)
    // {
    //     // Validasi input yang masuk
    //     $validateUp = $request->validate([
    //         'no_faktur' => 'required|max:6|unique:t_juals,no_faktur,' . $request->no_faktur . ',no_faktur',
    //         'kode_customer' => 'required|max:4',
    //         'tgl_faktur' => 'required|date|date_format:Y-m-d',
    //         'kode_tjen' => 'required|max:1',
    //         'total_bruto' => 'required|numeric',
    //         'total_diskon' => 'required|numeric',
    //         'total_jumlah' => 'required|numeric',
    //     ]);

    //     try {
    //         // Cari faktur berdasarkan no_faktur yang diinput
    //         $nF = t_jual::where('no_faktur', $request->no_faktur)->first();

    //         if (!$nF) {
    //             return response()->json(['success' => false, 'message' => 'Faktur tidak ditemukan'], 404);
    //         }

    //         // Update faktur dengan data yang divalidasi
    //         $nF->update($validateUp);

    //         // Kembalikan pesan sukses ke halaman sebelumnya
    //         return redirect()->back()->with('success', 'Faktur berhasil diperbarui');
    //     } catch (\Exception $e) {
    //         // Kembalikan pesan error jika terjadi exception
    //         return back()->withErrors(['message' => 'Terjadi kesalahan saat memperbarui faktur: ' . $e->getMessage()]);
    //     }
    // }

    public function manageFaktur(Request $request) 
    {
        // Validasi data yang diterima
        $validatedData = $request->validate([
            'items' => 'required|array',
            'items.*.no_faktur' => 'required|max:6',
            'items.*.kode_barang' => 'required|max:10',
            'items.*.harga' => 'required|numeric',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.diskon' => 'required|numeric|min:0|max:100',
            'items.*.bruto' => 'required|numeric',
            'items.*.jumlah' => 'required|numeric',
            'no_faktur' => 'sometimes|required|max:6|unique:t_juals,no_faktur,' . $request->no_faktur . ',no_faktur',
            'kode_customer' => 'sometimes|required|max:4',
            'tgl_faktur' => 'sometimes|required|date|date_format:Y-m-d',
            'kode_tjen' => 'sometimes|required|max:1',
            'total_bruto' => 'sometimes|required|numeric',
            'total_diskon' => 'sometimes|required|numeric',
            'total_jumlah' => 'sometimes|required|numeric',
        ]);

        // Cek jika no_faktur ada untuk update
        if (isset($request->no_faktur)) {
            // Cari faktur berdasarkan no_faktur yang diinput
            $nF = t_jual::where('no_faktur', $request->no_faktur)->first();

            if (!$nF) {
                return response()->json(['success' => false, 'message' => 'Faktur tidak ditemukan'], 404);
            }

            // Update faktur dengan data yang divalidasi
            $nF->update($validatedData);
        }

        // Array untuk menyimpan item yang sudah ada dan yang baru
        $existingItems = [];
        $newItems = [];

        // Loop melalui setiap item dan lakukan pengecekan sebelum disimpan
        foreach ($validatedData['items'] as $item) {
            // Pengecekan apakah data dengan no_faktur dan kode_barang sudah ada di database
            $exists = t_djual::where('no_faktur', $item['no_faktur'])
                            ->where('kode_barang', $item['kode_barang'])
                            ->exists();

            if ($exists) {
                // Jika data sudah ada, simpan ke array existingItems
                $existingItems[] = $item;
            } else {
                // Jika tidak ada, simpan item baru ke database
                t_djual::create($item);
                $newItems[] = $item;
            }
        }

        // Mengembalikan respons ke front-end
        if (count($existingItems) > 0) {
            return response()->json([
                'status' => 400,
                'message' => 'Beberapa data sudah ada di database.',
                'existingItems' => $existingItems,
                'newItems' => $newItems
            ]);
        }

        return response()->json([
            'method' => 'POST',
            'status' => 200,
            'message' => 'Data berhasil disimpan.',
            'newItems' => $newItems
        ]);
    }


    public function cariData(Request $request)
    {
        // Validasi no_faktur
        $request->validate([
            'no_faktur' => 'required|max:6',
        ]);

        // Mengambil data t_jual dan detail dari t_djual
        $result = t_jual::with('detailPenjualan.barang')
            ->where('no_faktur', $request->no_faktur)
            ->first();

        if ($result) {
            return response()->json([
                'status' => 200,
                'message' => 'Data ditemukan',
                'data' => $result
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Data tidak ditemukan',
            ]);
        }
    }

    public function hapusFaktur($no_faktur)
    {
        // Hapus data detail dari t_djual
        // t_djual::where('no_faktur', $no_faktur)->delete();

        // Hapus data faktur dari t_jual
        t_jual::where('no_faktur', $no_faktur)->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Data faktur dan detail penjualan berhasil dihapus'
        ]);
    }

    public function updateFaktur(Request $request, $no_faktur)
    {
        $validatedData = $request->validate([
            'tgl_faktur' => 'required|date',
            'kode_customer' => 'required|max:4',
            'kode_tjen' => 'required|max:1',
            'total_bruto' => 'required|numeric',
            'total_jumlah' => 'required|numeric',
            'total_diskon' => 'required|numeric',
        ]);

        // Cari data berdasarkan no_faktur
        $transaksi = t_jual::where('no_faktur', $no_faktur)->first();

        if ($transaksi) {
            // Update data header
            $transaksi->update($validatedData);

            return response()->json([
                'status' => 200,
                'message' => 'Data header berhasil diperbarui.',
                'data' => $transaksi
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Data tidak ditemukan.'
            ]);
        }
    }

    // public function hapusBarang($id)
    // {
    //     $transaksi = t_djual::find($id);

    //     if ($transaksi) {
    //         $transaksi->delete(); // Hapus data
    //         return response()->json([
    //             'status' => 200,
    //             'message' => 'Barang berhasil dihapus.'
    //         ]);
    //     } else {
    //         return response()->json([
    //             'status' => 404,
    //             'message' => 'Barang tidak ditemukan.'
    //         ]);
    //     }
    // }

    public function hapusBarang($no_faktur, $kode_barang)
    {
        try {
            // Mencari item berdasarkan no_faktur dan kode_barang
            $transaksi = t_djual::where('no_faktur', $no_faktur)
                            ->where('kode_barang', $kode_barang)
                            ->first();

            if ($transaksi) {
                $transaksi->delete(); // Hapus data
                return response()->json([
                    'status' => 200,
                    'message' => 'Barang berhasil dihapus.'
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Barang tidak ditemukan.'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function updateBarang(Request $request, $no_faktur, $kode_barang)
    {
        // Validasi data yang masuk
        $validatedData = $request->validate([
            'harga' => 'required|numeric',
            'qty' => 'required|numeric',
            'diskon' => 'required|numeric',
            'bruto' => 'required|numeric',
            'jumlah' => 'required|numeric',
        ]);

        // Mencari item berdasarkan no_faktur dan kode_barang
        $transaksi = t_djual::where('no_faktur', $no_faktur)
                            ->where('kode_barang', $kode_barang)
                            ->first();

        if ($transaksi) {
            // Update data barang
            $transaksi->update($validatedData);

            return response()->json([
                'status' => 200,
                'message' => 'Data barang berhasil diperbarui.',
                'data' => $transaksi
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Data barang tidak ditemukan.'
            ]);
        }
    }

    public function previewFaktur($no_faktur)
    {
        // dd($no_faktur);
        // Ambil data faktur beserta relasi ke customer, detail barang, dan jenis transaksi
        $faktur = t_jual::with('customer', 'detailPenjualan.barang', 'jenisTransaksi')
            ->where('no_faktur', $no_faktur)
            ->first();

        if (!$faktur) {
            return response()->json([
                'status' => 404,
                'message' => 'Faktur tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Faktur berhasil ditemukan',
            'data' => $faktur,
        ]);
    }

    public function printFaktur($no_faktur)
    {
        // Ambil data faktur dengan relasi yang diperlukan
        $faktur = t_jual::with('customer', 'detailPenjualan.barang', 'jenisTransaksi')
            ->where('no_faktur', $no_faktur)
            ->first();

        if (!$faktur) {
            return redirect()->back()->with('error', 'Faktur tidak ditemukan');
        }

        // dd($faktur);

        // Load the view file to generate PDF
        $pdf = Pdf::loadView('print', compact('faktur'));

        // Download the generated PDF
        return $pdf->download('Faktur_' . $faktur->no_faktur . '.pdf');
    }

    public function exportCsv($no_faktur)
    {
        // Ambil data faktur dengan relasi yang diperlukan
        $faktur = t_jual::with('customer', 'detailPenjualan.barang', 'jenisTransaksi')
            ->where('no_faktur', $no_faktur)
            ->first();

        if (!$faktur) {
            return redirect()->back()->with('error', 'Faktur tidak ditemukan');
        }

        // Siapkan data untuk CSV
        $data = [];
        
        // Informasi faktur
        $data[] = ['Faktur No:', $faktur->no_faktur];
        $data[] = ['Customer:', $faktur->customer->nama_customer];
        $data[] = ['Jenis Transaksi:', $faktur->jenisTransaksi->nama_tjen];
        $data[] = ['Tanggal:', $faktur->tgl_faktur];
        $data[] = []; // Baris kosong
        
        // Header tabel
        $data[] = ['Kode Barang', 'Nama Barang', 'Harga', 'Qty', 'Diskon (%)', 'Bruto', 'Jumlah'];
        
        // Detail faktur
        foreach ($faktur->detailPenjualan as $item) {
            $data[] = [
                $item->kode_barang,
                $item->barang->nama_barang,
                number_format($item->harga, 2),
                $item->qty,
                $item->diskon . '%',
                number_format($item->bruto, 2),
                number_format($item->jumlah, 2),
            ];
        }

        // Buat file CSV
        $filename = "Faktur_{$faktur->no_faktur}.csv";
        $handle = fopen('php://output', 'w');
        header('Content-Type: text/csv');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Pragma: no-cache');
        header('Expires: 0');

        // Tulis data ke CSV
        foreach ($data as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);
        exit();
    }






    // public function store(Request $request)
    // {
    //     // Validasi input
    //     $request->validate([
    //         'no_faktur' => 'required|size:6',
    //         'kode_customer' => 'required',
    //         'tgl_faktur' => 'required|date',
    //         'barang' => 'required|array',
    //         'barang.*.kode_barang' => 'required',
    //         'barang.*.qty' => 'required|integer|min:1',
    //         'barang.*.harga' => 'required|numeric|min:0',
    //     ]);

    //     // Simpan atau update header transaksi
    //     $jual = t_jual::updateOrCreate(
    //         ['no_faktur' => $request->no_faktur], 
    //         [
    //             'kode_customer' => $request->kode_customer,
    //             'kode_tjen' => $request->jenis_transaksi,
    //             'tgl_faktur' => $request->tgl_faktur,
    //         ]
    //     );

    //     // Variabel untuk menyimpan total transaksi
    //     $totalBruto = 0;
    //     $totalDiskon = 0;
    //     $totalJumlah = 0;

    //     // Simpan barang ke dalam detail transaksi
    //     foreach ($request->barang as $item) {
    //         $bruto = $item['qty'] * $item['harga'];
    //         $diskon = isset($item['diskon']) ? ($bruto * $item['diskon'] / 100) : 0;
    //         $jumlah = $bruto - $diskon;

    //         // Hitung total bruto, diskon, dan jumlah
    //         $totalBruto += $bruto;
    //         $totalDiskon += $diskon;
    //         $totalJumlah += $jumlah;

    //         t_djual::create([
    //             'no_faktur' => $jual->no_faktur,
    //             'kode_barang' => $item['kode_barang'],
    //             'qty' => $item['qty'],
    //             'harga' => $item['harga'],
    //             'diskon' => $diskon,
    //             'bruto' => $bruto,
    //             'jumlah' => $jumlah,
    //         ]);
    //     }

    //     // Simpan total bruto, diskon, dan jumlah ke transaksi header (jika diperlukan)
    //     $jual->update([
    //         'total_bruto' => $totalBruto,
    //         'total_diskon' => $totalDiskon,
    //         'total_jumlah' => $totalJumlah
    //     ]);

    //     dd($request);

    //     return response()->json([
    //         'message' => 'Transaksi berhasil disimpan!',
    //         'total_bruto' => $totalBruto,
    //         'total_diskon' => $totalDiskon,
    //         'total_jumlah' => $totalJumlah
    //     ]);
    // }
}
