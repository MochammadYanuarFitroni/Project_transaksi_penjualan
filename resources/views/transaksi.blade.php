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
                <button type="button" class="btn btn-secondary" onclick="tombolInputH()" id="inputH">Input</button>
                <button type="button" class="btn btn-secondary" id="hapusH">Hapus</button>
                <button type="button" class="btn btn-secondary" id="editH">Edit</button>
                <button type="button" class="btn btn-secondary" id="simpanH" disabled>Simpan</button>
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
                        <input type="text" class="form-control" id="no_faktur" name="no_faktur" required readOnly>
                    </div>
                    <div class="col-md-6">
                        <label for="tgl_faktur" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="tgl_faktur" name="tgl_faktur" required readOnly>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="kode_customer" class="form-label">Kode Customer</label>
                        <select class="form-select" id="kode_customer" name="kode_customer" style="pointer-events: none; background-color: #ffffff;">
                            <option disabled selected value="" >Silahkan pilih</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->kode_customer }}">{{ $customer->nama_customer }}</option>
                            @endforeach
                        </select>
                        {{-- <input type="text" class="form-control" id="kode_customer" name="kode_customer" value="MAJU TAK GENTAR, PT" readonly> --}}
                    </div>
                    <div class="col-md-6">
                        <label for="kode_tjen" class="form-label">Jenis Transaksi</label>
                        <select class="form-select" id="kode_tjen" name="kode_tjen" style="pointer-events: none; background-color: #ffffff;">
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
                <button type="button" class="btn btn-secondary" onclick="tombolInputB()" id="inputB">Input</button>
                <button type="button" class="btn btn-secondary" id="hapusB" disabled>Hapus</button>
                <button type="button" class="btn btn-secondary" id="editB" disabled>Edit</button>
                <button type="button" class="btn btn-secondary" id="add-item" disabled>Simpan</button>
                <button type="button" class="btn btn-secondary" onclick="batalbtnBarang()" id="batalB" disabled>Batal</button>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <input type="hidden" id="t_djual_id" name="t_djual_id" />
                    <input type="hidden" id="kode_barangHidden" name="kode_barang" />
                    <input type="hidden" id="kode_barangHidden" name="kode_barang" />
                    <input type="hidden" id="no_faktur" value="your_no_faktur_value_here">
                    <div class="col-md-4">
                        <label for="kode_barang" class="form-label">Kode Barang</label>
                        <input type="text" class="form-control" id="kode_barang" name="kode_barang" readOnly>
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
                        <input type="number" class="form-control" id="qty" name="qty" value="0" readOnly>
                    </div>
                    <div class="col-md-2">
                        <label for="diskon" class="form-label">Diskon (%)</label>
                        <input type="number" class="form-control" id="diskon" name="diskon" min="0" max="100" value="0" readOnly>
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

        <style>
            .total-input {
                position: relative;
                text-align: right; /* Aligns text to the right */
                padding-left: 30px; /* Space for the 'Rp.' */
                width: 100%; /* Adjust to fill the container */
                border: 1px solid #ccc; /* Optional: styling for input border */
                border-radius: 4px; /* Optional: styling for rounded corners */
            }

            .total-input::before {
                content: 'Rp.'; /* Add the 'Rp.' before the input value */
                position: absolute;
                left: 10px; /* Positioning of 'Rp.' */
                top: 50%;
                transform: translateY(-50%); /* Center vertically */
                color: black; /* Color of the 'Rp.' text */
                pointer-events: none; /* Ensures 'Rp.' is not interactive */
            }
        </style>
        <div class="col-md-6 ms-auto">
            <div class="card bg-body-secondary mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <div class="d-flex align-items-center">
                                <label for="total_bruto" class="form-label">Total Bruto</label>
                                <div class="col-sm-9 ms-auto">
                                    <input type="text" class="form-control total-input" id="total_bruto" value="Rp 0" readOnly>
                                    <input type="hidden" name="total_bruto" id="hidden_total_bruto" value="0">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <div class="d-flex align-items-center">
                                <label for="total_diskon" class="form-label">Total Diskon</label>
                                <div class="col-sm-9 ms-auto">
                                    <input type="text" class="form-control total-input text-right" id="total_diskon" value="Rp 0" readOnly>
                                    <input type="hidden" name="total_diskon" id="hidden_total_diskon" value="0">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <div class="d-flex align-items-center">
                                <label for="total_jumlah" class="form-label">Total Jumlah</label>
                                <div class="col-sm-9 ms-auto">
                                    <input type="text" class="form-control total-input text-right" id="total_jumlah" value="Rp 0" readOnly>
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
    {{-- <meta name="hapus-barang-route" content="{{ route('transaksi.hapusBarang', '') }}"> --}}
    <meta name="hapus-barang-route" content="{{ route('transaksi.hapusBarang', ['no_faktur' => 'dummy_no_faktur', 'kode_barang' => 'dummy_kode_barang']) }}">

    <meta name="update-barang-route" content="{{ route('transaksi.updateBarang', ['no_faktur' => 'dummy_no_faktur', 'kode_barang' => 'dummy_kode_barang']) }}">
    {{-- <meta name="update-barang-route" content="{{ route('transaksi.updateBarang', '') }}"> --}}
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

        function tombolInputH(){
            console.log("tests")
            document.querySelector('#inputH').disabled = true
            document.querySelector('#simpanH').disabled = false

            const fakturInputs = document.querySelectorAll('.card:nth-of-type(1) .card-body input, .card:nth-of-type(1) .card-body select');
            fakturInputs.forEach(function(input){
                input.readOnly = false
                input.style.pointerEvents = 'auto'
                input.style.backgroundColor = ''
            })
            document.querySelector('#total_bruto').readOnly = true
            document.querySelector('#total_diskon').readOnly = true
            document.querySelector('#total_jumlah').readOnly = true
        }

        function tombolInputB(){
            console.log("tests")
            document.querySelector('#inputB').disabled = true

            document.querySelector('#kode_barang').readOnly = false
            document.querySelector('#qty').readOnly = false
            document.querySelector('#diskon').readOnly = false
            document.querySelector('#total_bruto').readOnly = true
            document.querySelector('#total_diskon').readOnly = true
            document.querySelector('#total_jumlah').readOnly = true

            // const kodeBarangInputs = document.querySelectorAll('.card:nth-of-type(2) .card-body input, .card:nth-of-type(2) .card-body select');
            // kodeBarangInputs.forEach(function(input){
            //     input.readOnly = false
            // })
        }

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
            // tombolB()
        });


        //edit barang/data
        document.getElementById('editB').addEventListener('click', function() {
            const noFaktur = document.getElementById('no_faktur').value; // Get no_faktur from input
            const kodeBarang = document.getElementById('kode_barang').value; // Get kode_barang from input

            // Get data from the form
            const editData = {
                harga: getNumberFromRupiah(document.getElementById('harga_barang').value),
                qty: parseInt(document.getElementById('qty').value),
                diskon: parseFloat(document.getElementById('diskon').value),
                bruto: getNumberFromRupiah(document.getElementById('bruto').value),
                jumlah: getNumberFromRupiah(document.getElementById('jumlah').value)
            };

            // Validate data before sending
            if (!kodeBarang || !editData.harga || !editData.qty) {
                alert('Mohon lengkapi semua field sebelum mengedit.');
                return;
            }

            console.log(editData);
            console.log(kodeBarang);

            if (confirm('Apakah Anda yakin ingin mengedit barang ini?')) {
                fetch(`http://127.0.0.1:8000/update-barang/${noFaktur}/${kodeBarang}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
                    },
                    body: JSON.stringify(editData)
                })
                .then(response => response.json())
                .then(result => {
                    alert(result.message);
                    if (result.status === 200) {
                        // Jika berhasil, perbarui tabel dan bersihkan form
                        updateTable(result.data);
                        clearFormBarang();
                        updateTotals();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengedit barang.');
                });
            }
        });


        document.getElementById('hapusB').addEventListener('click', function() {
            const noFaktur = document.getElementById('no_faktur').value; // Ambil no_faktur dari input
            const kodeBarang = document.getElementById('kode_barang').value; // Ambil kode_barang dari input
            // const hapusBarangRoute = document.querySelector('meta[name="hapus-barang-route"]').getAttribute('content');

            if (confirm('Apakah Anda yakin ingin menghapus barang ini?')) {
                fetch(`http://127.0.0.1:8000/hapus-barang/${noFaktur}/${kodeBarang}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
                    }
                })
                // .then(response => response.json())
                .then(response => {
                    console.log('Status kode:', response.status); // Melihat status response

                    if (!response.ok) {
                        throw new Error('Terjadi masalah dengan response: ' + response.statusText);
                    }
                    return response.json();
                })
                // .then(result => console.log(result))
                .then(result => {
                    alert(result.message);
                    if (result.status === 200) {
                        // Hapus baris atau perbarui UI sesuai kebutuhan
                        // updateTable(kodeBarang);
                        const rowToDelete = document.querySelector(`tr[data-id="${noFaktur}_${kodeBarang}"]`);
                        if (rowToDelete) {
                            rowToDelete.remove(); // Menghapus baris dari tabel
                        }
                        clearFormBarang(); // Fungsi untuk mengosongkan form
                        updateTotals()
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus barang.');
                });
            }
        });

    </script>
@endsection