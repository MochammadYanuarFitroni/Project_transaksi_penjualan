<?php

namespace App\Http\Controllers;

use App\Models\customer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class customerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('customer.index',[
            "title" => "Customer",
            "active" => "customer",
            "customers" => customer::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customer.create',[
            "title" => "Tambah Customer",
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
        $validateNewData = $request -> validate([
            'kode_customer' => 'required|max:4|unique:customers,kode_customer',
            'nama_customer' => 'required|max:100',
        ]);

        //change string/char kode_customer to UPPERCASE
        $validateNewData['kode_customer'] = Str::upper($validateNewData['kode_customer']);

        customer::create($validateNewData);

        return redirect()->route('customer.index')->with("success", "Data customer baru telah ditambahakan!!!");
    }

    /**
     * Display the specified resource.
     */
    // public function show(customer $customer)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(customer $customer)
    {
        //dd($customer);
        return view('customer.edit', [
            'customer' => $customer,
            'title' => 'Edit Customer'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, customer $customer)
    {
        $rules = [
            'nama_customer' => 'required|max:100',
        ];

        if ($request->kode_customer != $customer->kode_customer) {
            $rules['kode_customer'] = 'required|max:4|unique:customers,kode_customer';
        }
        
        $dataUpdate = $request -> validate($rules);

        // dd($dataUpdateCustomer);
        if (isset($dataUpdate['kode_customer'])) {
            $dataUpdate['kode_customer'] = Str::upper($dataUpdate['kode_customer']);  //change string/char kode_customer to UPPERCASE
        }

        customer::where('kode_customer', $customer->kode_customer)
                    ->update($dataUpdate);

        return redirect()->route('customer.index')->with("success", "Data customer telah diedit/update!!!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(customer $customer)
    {
        //dd($customer);
        $customer->delete();
        return redirect()->route('customer.index')->with("success", "Data customer telah di hapus!!!");
    }
}
