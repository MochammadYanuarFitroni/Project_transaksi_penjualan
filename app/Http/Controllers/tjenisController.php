<?php

namespace App\Http\Controllers;

use App\Models\tjenis;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class tjenisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('jenis.index', [
            'title' => 'Jenis',
            'active' => 'jenis',
            'tjenis_s' => tjenis::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('jenis.create', [
            'title' => 'Tambah Jenis',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request);
        $validateNewData = $request -> validate([
            'kode_tjen' => 'required|max:1|unique:tjenis,kode_tjen',
            'nama_tjen' => 'required|max:100',
        ]);

        //change string/char kode_tjen to UPPERCASE
        $validateNewData['kode_tjen'] = Str::upper($validateNewData['kode_tjen']);

        tjenis::create($validateNewData);

        return redirect()->route('tjenis.index')->with("success", "Jenis transaksi baru telah ditambahakan!!!");
    }

    /**
     * Display the specified resource.
     */
    // public function show($kode_tjenis)
    // {
    //     $test = tjenis::find($kode_tjenis);

    //     dd($test);
    //     return view('jenis.edit', [
    //         'tjenis' => $test,
    //         'title' => 'Edit Jenis Transaksi'
    //     ]);
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($kode_tjenis)
    {
        $kode_tjen = tjenis::find($kode_tjenis);

        // dd($kode_tjen);
        return view('jenis.edit', [
            'tjenis' => $kode_tjen,
            'title' => 'Edit Jenis Transaksi'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $kode_tjenis)
    {
        $kode_tjen = tjenis::find($kode_tjenis);

        $rules = [
            'nama_tjen' => 'required|max:100',
        ];

        if ($request->kode_tjen != $kode_tjen->kode_tjen) {
            $rules['kode_tjen'] = 'required|max:1|unique:tjenis,kode_tjen';
        }
        
        $dataUpdate = $request -> validate($rules);

        // dd($dataUpdateCustomer);
        if (isset($dataUpdate['kode_tjen'])) {
            $dataUpdate['kode_tjen'] = Str::upper($dataUpdate['kode_tjen']);  //change string/char kode_tjen to UPPERCASE
        }

        tjenis::where('kode_tjen', $kode_tjen->kode_tjen)
                    ->update($dataUpdate);

        return redirect()->route('tjenis.index')->with("success", "Jenis transaksi telah diedit/update!!!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($kode_tjenis)
    {
        $kode_tjen = tjenis::find($kode_tjenis);
        //dd("Request reached destroy method", $tjenis);
        $kode_tjen->delete();
        return redirect()->route('tjenis.index')->with("success", "Jenis transaksi telah dihapus!!!");
    }
}
