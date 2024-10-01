@extends('layouts.main')

@section('content')
    <h2>Semua Jenis Transaksi</h2>
    <hr class="my-3">
    
    @if(session()->has('success'))
        <div class="col-lg-8 alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    
    <a href="{{ route('tjenis.create') }}" class="btn btn-primary mb-2">Tambah jenis transaksi</a>
    <a href="/" class="btn btn-secondary mb-2">Kembali</a>
    <div class="table-responsive">
      <table class="table table-striped table-sm">
        <thead>
          <tr>
            <th scope="col">No</th>
            <th scope="col">Kode Jenis Transaksi</th>
            <th scope="col">Nama Jenis Transaksi</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($tjenis_s as $tjenis)    
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $tjenis->kode_tjen }}</td>
                <td>{{ $tjenis->nama_tjen }}</td>
                <td>
                  <a href="{{ route('tjenis.edit', $tjenis->kode_tjen) }}" class="btn btn-warning text-decoration-none">Edit</a>
                  <form action="{{ route('tjenis.destroy', $tjenis->kode_tjen) }}" method="POST" style="display:inline;">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-danger border-0" onclick="return confirm('Yakin mau hapus jenis transaksi ini ?')">Delete</button>
                  </form>
                </td>
            </tr>
            @endforeach
        </tbody>
      </table>
    </div>
    
@endsection