@extends('layouts.main')

@section('content')
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"> Data Customer Baru</h1>
  </div>

  <div class="col-lg-8">
      <form action="{{ route('customer.update', $customer->kode_customer) }}" method="POST">
        @method('put')
        @csrf
        <div class="mb-3">
            <label for="kode_customer" class="form-label">Kode customer</label>
            <input type="text" class="form-control @error('kode_customer') is-invalid @enderror" id="kode_customer" name="kode_customer" placeholder="Kode customer" value="{{ old('kode_customer', $customer->kode_customer) }}">

            @error('kode_customer')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="nama_customer" class="form-label">Nama customer</label>
            <input type="text" class="form-control @error('nama_customer') is-invalid @enderror" id="nama_customer" name="nama_customer" placeholder="Nama customer" value="{{ old('nama_customer', $customer->nama_customer) }}">

            @error('nama_customer')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Edit data customer</button>
      </form>
  </div>
@endsection