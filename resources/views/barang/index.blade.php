@extends('layouts.main')

@section('content')
    <h2>Semua Barang</h2>
    <hr class="my-3">
    
    @if(session()->has('success'))
        <div class="col-lg-8 alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <a href="{{ route('barang.create') }}" class="btn btn-primary mb-2">Tambah Barang</a>
    <a href="/" class="btn btn-secondary mb-2">Kembali</a>
    <div class="table-responsive">
      <table class="table table-striped table-sm">
        <thead>
          <tr>
            <th scope="col">No</th>
            <th scope="col">Kode Barang</th>
            <th scope="col">Nama Barang</th>
            <th scope="col">Harga Barang</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($barangs as $barang)    
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $barang->kode_barang }}</td>
                <td>{{ $barang->nama_barang }}</td>
                <td>{{ $barang->harga_barang }}</td>
                <td>
                  {{-- <a href="{{ route('barang.show', $barang->kode_barang) }}" class="badge bg-warning">Show</a> --}}
                  <a href="{{ route('barang.edit', $barang->kode_barang) }}" class="btn btn-warning text-decoration-none">Edit</a>

                  <form action="{{ route('barang.destroy', $barang->kode_barang) }}" method="POST" style="display:inline;">
                      @csrf
                      @method('delete')
                      <button class="btn btn-danger border-0" onclick="return confirm('Yakin mau hapus barang ini ?')">Delete</button>
                  </form>
                </td>
            </tr>
            @endforeach
        </tbody>
      </table>
    </div>
@endsection