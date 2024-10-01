@extends('layouts.main')

@section('content')
    <h2 class="text-center">Transaksi Penjualan</h2>
    <a href="/barang" class="btn btn-primary">Master Barang</a>
    <a href="/customer" class="btn btn-primary">Master Customer</a>
    <a href="/tjenis" class="btn btn-primary">Master Jenis Transaksi</a>
    @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger mt-3">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <form action="{{ route('transaksi.updateDataFaktur') }}" method="POST" class="mt-2">
        @csrf
        @method('put')
        <!-- Bagian Detail Transaksi -->
        <div class="card mb-4 bg-body-secondary">
            <div class="card-header">
                <button type="button" class="btn btn-secondary" id="inputH">Input</button>
                <button type="button" class="btn btn-secondary" id="hapusH">Hapus</button>
                <button type="button" class="btn btn-secondary" id="editH">Edit</button>
                <button type="submit" class="btn btn-secondary" id="simpanH" disabled>Simpan</button>
                <button type="button" class="btn btn-secondary" id="cari-button">Cari</button>
                <button type="button" class="btn btn-secondary" onclick="batalnRestUi()" id="batalBtn">Batal</button>
                <button type="button" class="btn btn-secondary" id="printButton">Print</button>
                <button type="button" class="btn btn-secondary" id="previewButton">Preview</button>
                <button type="button" class="btn btn-secondary" id="csv-button">CSV</button>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="no_faktur" class="form-label">No Faktur</label>
                        <input type="text" class="form-control" id="no_faktur" name="no_faktur" required>
                    </div>
                    <div class="col-md-6">
                        <label for="tgl_faktur" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="tgl_faktur" name="tgl_faktur" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="kode_customer" class="form-label">Kode Customer</label>
                        <select class="form-select" id="kode_customer" name="kode_customer">
                            <option disabled selected value="" >Silahkan pilih</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->kode_customer }}">{{ $customer->nama_customer }}</option>
                            @endforeach
                        </select>
                        {{-- <input type="text" class="form-control" id="kode_customer" name="kode_customer" value="MAJU TAK GENTAR, PT" readonly> --}}
                    </div>
                    <div class="col-md-6">
                        <label for="kode_tjen" class="form-label">Jenis Transaksi</label>
                        <select class="form-select" id="kode_tjen" name="kode_tjen">
                            <option disabled selected value="" >Silahkan pilih</option>
                            @foreach ($tjenis as $jenis)
                                <option value="{{ $jenis->kode_tjen }}">{{ $jenis->nama_tjen }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary" id="detailH" hidden>DETAIL</button>
            </div>
        </div>

        <!-- Bagian Input Barang -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="card mb-4 bg-body-secondary">
            <div class="card-header">
                <button type="button" class="btn btn-secondary" id="inputB" disabled>Input</button>
                <button type="button" class="btn btn-secondary" id="hapusB" disabled>Hapus</button>
                <button type="button" class="btn btn-secondary" id="editB" disabled>Edit</button>
                <button type="button" class="btn btn-secondary" id="add-item" disabled>Simpan</button>
                <button type="button" class="btn btn-secondary" onclick="batalbtnBarang()" id="batalB" disabled>Batal</button>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <input type="hidden" id="t_djual_id" name="t_djual_id" />
                    <div class="col-md-4">
                        <label for="kode_barang" class="form-label">Kode Barang</label>
                        <input type="text" class="form-control" id="kode_barang" name="kode_barang">
                    </div>
                    <div class="col-md-4">
                        <label for="nama_barang" class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" id="nama_barang" name="nama_barang" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="harga_barang" class="form-label">Harga Barang</label>
                        <input type="text" class="form-control" id="harga_barang" name="harga_barang" value="Rp. 0" readonly>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-2">
                        <label for="qty" class="form-label">QTY</label>
                        <input type="number" class="form-control" id="qty" name="qty" value="0">
                    </div>
                    <div class="col-md-2">
                        <label for="diskon" class="form-label">Diskon (%)</label>
                        <input type="number" class="form-control" id="diskon" name="diskon" min="0" max="100" value="0">
                    </div>
                    <div class="col-md-4">
                        <label for="bruto" class="form-label">Bruto</label>
                        <input type="text" class="form-control" id="bruto" name="bruto" value="Rp. 0" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="jumlah" class="form-label">Jumlah</label>
                        <input type="text" class="form-control" id="jumlah" name="jumlah" value="Rp. 0" readonly>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4 bg-body-secondary">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">NO FAKTUR</th>
                    <th scope="col">KODE BARANG</th>
                    <th scope="col">NAMA BARANG</th>
                    <th scope="col">HARGA</th>
                    <th scope="col">QTY</th>
                    <th scope="col">DISKON</th>
                    <th scope="col">BRUTO</th>
                    <th scope="col">JUMLAH</th>
                    <th scope="col" hidden id="aksi">AKSI</th>
                </tr>
                </thead>
                <tbody>
                
                </tbody>
            </table>
        </div>
        <!-- Bagian Total -->
        <div class="col-md-6 ms-auto">
            <div class="card bg-body-secondary mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <div class="d-flex align-items-center">
                                <label for="total_bruto" class="form-label">Total Bruto</label>
                                <div class="col-sm-9 ms-auto">
                                    <input type="text" class="form-control" id="total_bruto" value="Rp. 0" readonly>
                                    <input type="hidden" name="total_bruto" id="hidden_total_bruto" value="0">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <div class="d-flex align-items-center">
                                <label for="total_diskon" class="form-label">Total Diskon</label>
                                <div class="col-sm-9 ms-auto">
                                    <input type="text" class="form-control" id="total_diskon" value="Rp. 0" readonly>
                                    <input type="hidden" name="total_diskon" id="hidden_total_diskon" value="0">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <div class="d-flex align-items-center">
                                <label for="total_jumlah" class="form-label">Total Jumlah</label>
                                <div class="col-sm-9 ms-auto">
                                    <input type="text" class="form-control" id="total_jumlah" value="Rp. 0" readonly>
                                    <input type="hidden" name="total_jumlah" id="hidden_total_jumlah" value="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Modal Bootstrap -->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h3 class="modal-title text-center">PT. XYZ Indonesia</h3>
                    <h5 class="modal-title text-center" id="previewModalLabel">Preview Faktur</h5>
                    
                    <!-- Konten Preview Faktur akan diisi melalui JavaScript -->
                    <div id="faktur-preview-content">
                        <!-- Dynamic content will be injected here by JavaScript -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <meta name="tambah-barang-route" content="{{ route('transaksi.tambahBarang') }}">
    <meta name="tambah-inputHeader-route" content="{{ route('transaksi.inputHeader') }}">
    <meta name="cariData-route" content="{{ route('transaksi.cariData') }}">
    <meta name="hapus-faktur-route" content="{{ route('transaksi.hapusFaktur', '') }}">

    <meta name="update-faktur-route" content="{{ route('transaksi.updateHeader', '') }}">
    <meta name="hapus-barang-route" content="{{ route('transaksi.hapusBarang', '') }}">
    <meta name="update-barang-route" content="{{ route('transaksi.updateBarang', '') }}">
    <meta name="preview-route" content="{{ route('transaksi.preview', '') }}">
    <meta name="print-route" content="{{ route('transaksi.printFaktur', '') }}">
    <meta name="csv-route" content="{{ route('transaksi.exportCsv', '') }}">

    <script src="{{ asset('js/transaksi_barang.js') }}"></script>
    <script src="{{ asset('js/transaksi_header.js') }}"></script>
    <script src="{{ asset('js/transaksi_external.js') }}"></script>

    <script>
        const noFaktur = document.querySelector('#no_faktur')
        noFaktur.addEventListener('keyup', function(){
            noFaktur.value = noFaktur.value.toUpperCase()

            if(noFaktur.value.trim() !== ''){
                document.querySelector('#cari-button').disabled = false
                document.querySelector('#batalBtn').disabled = false
            }
        })

        function clearFormBarang() {
            document.getElementById('kode_barang').value = '';
            document.getElementById('nama_barang').value = '';
            document.getElementById('harga_barang').value = 'Rp. 0';
            document.getElementById('qty').value = '0';
            document.getElementById('diskon').value = '0';
            document.getElementById('bruto').value = 'Rp. 0';
            document.getElementById('jumlah').value = 'Rp. 0';
            document.getElementById('t_djual_id').value = ''; // Reset hidden input
        }

        function batalbtnBarang(){
            clearFormBarang()
            toggleButtons(false);
        }

        function batalnRestUi() {
            //faktur
            document.getElementById('no_faktur').value = '';
            document.getElementById('kode_customer').value = '';
            document.getElementById('tgl_faktur').value = '';
            document.getElementById('kode_tjen').value = '';

            clearFormBarang()
            const tableBody = document.querySelector('table tbody');
            tableBody.innerHTML = ''; // Menghapus semua isi tabel

            //total
            document.getElementById('total_bruto').value = 'Rp. 0';
            document.getElementById('total_diskon').value = 'Rp. 0';
            document.getElementById('total_jumlah').value = 'Rp. 0';

            //hidden
            document.getElementById('hidden_total_jumlah').value = '0';
            document.getElementById('hidden_total_jumlah').value = '0';
            document.getElementById('hidden_total_jumlah').value = '0';
            
            // document.querySelector('#inputH').disabled = false
            document.getElementById('detailH').hidden = true
            document.getElementById('aksi').hidden = true
            tombolH()
            tombolB()
        }

        function tombolH(){
            // Menonaktifkan tombol di card bagian nomor faktur kecuali tombol input
            const fakturCardButtons = document.querySelectorAll('.card:nth-of-type(1) .card-header .btn');
            
            fakturCardButtons.forEach(function(button) {
                // Jika tombol bukan 'input' baru di-disable
                if (button.id === 'inputH') {
                    button.disabled = false;
                }
                else{
                    button.disabled = true
                }
            });
        }

        function tombolB(){
            // Menonaktifkan semua tombol di card bagian kode barang
            const barangCardButtons = document.querySelectorAll('.card:nth-of-type(2) .card-header .btn');
            
            barangCardButtons.forEach(function(button) {
                button.disabled = true;
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            tombolH()
            tombolB()
        });
    </script>
@endsection