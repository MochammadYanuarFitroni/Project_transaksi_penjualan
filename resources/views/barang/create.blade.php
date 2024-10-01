@extends('layouts.main')

@section('content')
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Tambah Data Barang Baru</h1>
  </div>

  <div class="col-lg-8">
      <form action="{{ route('barang.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="kode_barang" class="form-label">Kode barang</label>
            <input type="text" class="form-control @error('kode_barang') is-invalid @enderror" id="kode_barang" name="kode_barang" placeholder="Kode barang" value="{{ old('kode_barang') }}">

            @error('kode_barang')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="nama_barang" class="form-label">Nama barang</label>
            <input type="text" class="form-control @error('nama_barang') is-invalid @enderror" id="nama_barang" name="nama_barang" placeholder="Nama barang" value="{{ old('nama_barang') }}">

            @error('nama_barang')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="harga_barang" class="form-label">Harga barang</label>
            <input type="number" class="form-control @error('harga_barang') is-invalid @enderror" id="harga_barang" name="harga_barang" placeholder="Harga barang" value="{{ old('harga_barang') }}">

            @error('harga_barang')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Tambah Barang</button>
      </form>
  </div>
@endsection