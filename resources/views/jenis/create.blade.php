@extends('layouts.main')

@section('content')
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Tambah Data Jenis Transaksi</h1>
  </div>

  <div class="col-lg-8">
      <form action="{{ route('tjenis.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="kode_tjen" class="form-label">Kode transaksi</label>
            <input type="text" class="form-control @error('kode_tjen') is-invalid @enderror" id="kode_tjen" name="kode_tjen" placeholder="Kode transaksi" value="{{ old('kode_tjen') }}">

            @error('kode_tjen')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="nama_tjen" class="form-label">Nama transaksi</label>
            <input type="text" class="form-control @error('nama_tjen') is-invalid @enderror" id="nama_tjen" name="nama_tjen" placeholder="Nama transaksi" value="{{ old('nama_tjen') }}">

            @error('nama_tjen')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Tambah data jenis transaksi</button>
        <a href="/tjenis" class="btn btn-secondary">Batal</a>
      </form>
  </div>
@endsection