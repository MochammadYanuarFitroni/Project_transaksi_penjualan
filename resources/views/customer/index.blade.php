@extends('layouts.main')

@section('content')
    <h2>Semua Customer</h2>
    <hr class="my-3">
    
    @if(session()->has('success'))
        <div class="col-lg-8 alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <a href="{{ route('customer.create') }}" class="btn btn-primary mb-2">Tambah Data Customer</a>
    <a href="/" class="btn btn-secondary mb-2">Kembali</a>
    <div class="table-responsive">
      <table class="table table-striped table-sm">
        <thead>
          <tr>
            <th scope="col">No</th>
            <th scope="col">Kode Customer</th>
            <th scope="col">Nama Customer</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($customers as $customer)    
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $customer->kode_customer }}</td>
                <td>{{ $customer->nama_customer }}</td>
                <td>
                  {{-- <a href="{{ route('customer.show', $customer->kode_customer) }}" class="badge bg-warning">Show</a> --}}
                  <a href="{{ route('customer.edit', $customer->kode_customer) }}" class="btn btn-warning text-decoration-none">Edit</a>

                  <form action="{{ route('customer.destroy', $customer->kode_customer) }}" method="POST" style="display:inline;">
                      @csrf
                      @method('delete')
                      <button class="btn btn-danger border-0" onclick="return confirm('Yakin mau hapus customer ini ?')">Delete</button>
                  </form>
                </td>
            </tr>
            @endforeach
        </tbody>
      </table>
    </div>
@endsection