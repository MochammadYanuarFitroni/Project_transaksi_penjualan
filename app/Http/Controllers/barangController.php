<?php

namespace App\Http\Controllers;

use App\Models\barang;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class barangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('barang.index',[
            "title" => "Barang",
            "active" => "barang",
            "barangs" => barang::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('barang.create',[
            "title" => "Tambah Barang"
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateNewData = $request -> validate([
            'kode_barang' => 'required|max:10|unique:barangs,kode_barang',
            'nama_barang' => 'required|max:100',
            'harga_barang' => 'required|numeric',
        ]);

        $validateNewData['kode_barang'] = Str::upper($validateNewData['kode_barang']);

        barang::create($validateNewData);

        return redirect()->route('barang.index')->with("success", "Barang baru telah ditambahakan!!!");
    }

    /**
     * Display the specified resource.
     */
    // public function show(barang $barang)
    // {
    //     return view('barang.edit',[
    //         'barangs' => $barang,
    //         'title' => 'Edit Barang'
    //     ]);
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(barang $barang)
    {
        // dd($barang);
        return view('barang.edit',[
            'barang' => $barang,
            'title' => 'Edit Barang'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, barang $barang)
    {
        $rules = [
            'nama_barang' => 'required|max:100',
            'harga_barang' => 'required|numeric',
        ];

        if ($request->kode_barang != $barang->kode_barang) {
            $rules['kode_barang'] = 'required|max:10|unique:barangs,kode_barang';
        }
        
        $dataUpdate = $request -> validate($rules);

        // dd($dataUpdate);
        if (isset($dataUpdate['kode_barang'])) {
            $dataUpdate['kode_barang'] = Str::upper($dataUpdate['kode_barang']);
        }

        barang::where('kode_barang', $barang->kode_barang)
                ->update($dataUpdate);

        return redirect()->route('barang.index')->with("success", "Barang telah diedit/update!!!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(barang $barang)
    {
        $barang->delete();
        return redirect()->route('barang.index')->with("success", "Barang telah dihapus!!!");
    }
}
