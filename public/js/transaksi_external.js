//Preview data yang telah dicari/mau diprint
document.getElementById('previewButton').addEventListener('click', function() {
    const no_faktur = noFaktur.value
    const previewRoute = document.querySelector('meta[name="preview-route"]').getAttribute('content');

    fetch(`${previewRoute}/${no_faktur}`)
        .then(response => response.json())
        // .then(result => console.log(result))
        .then(result => {
            if (result.status === 200) {
                const faktur = result.data;
                const jenisTransaksi = result.data.jenis_transaksi;

                // Isi modal dengan data faktur dan jenis transaksi
                let modalContent = `
                    <h5 class="modal-title text-center">No Faktur: ${faktur.no_faktur}</h5>
                    <hr>
                    <p>Customer: ${faktur.customer.nama_customer}</p>
                    <p>Jenis Transaksi: ${jenisTransaksi.nama_tjen}</p>
                    <p>Tanggal: ${faktur.tgl_faktur}</p>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Diskon</th>
                                <th>Bruto</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>`;

                faktur.detail_penjualan.forEach(item => {
                    modalContent += `
                        <tr>
                            <td>${item.kode_barang}</td>
                            <td>${item.barang.nama_barang}</td>
                            <td>Rp. ${Math.round(item.harga)}</td>
                            <td>${Math.round(item.qty)}</td>
                            <td>${Math.round(item.diskon)}%</td>
                            <td>Rp. ${Math.round(item.bruto)}</td>
                            <td>Rp. ${Math.round(item.jumlah)}</td>
                        </tr>`;
                });

                modalContent += `
                        </tbody>
                    </table>
                    <p>Total Bruto: Rp ${Math.round(faktur.total_bruto)}</p>
                    <p>Total Diskon: Rp ${Math.round(faktur.total_diskon)}</p>
                    <p>Total Jumlah: Rp ${Math.round(faktur.total_jumlah)}</p>
                `;

                // Tampilkan konten di modal
                document.getElementById('faktur-preview-content').innerHTML = modalContent;
                
                // Tampilkan modal
                const previewModal = new bootstrap.Modal(document.getElementById('previewModal'), {
                    keyboard: false // Mencegah penutupan modal dengan tombol ESC (opsional)
                });
                previewModal.show();
            } else {
                alert('Faktur tidak ditemukan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memuat data faktur');
        });
})

//pint data ke pdf
document.getElementById('printButton').addEventListener('click', function() {
    const no_faktur = noFaktur.value
    const printRoute = document.querySelector('meta[name="print-route"]').getAttribute('content');

    // Redirect to the Laravel controller route
    window.location.href = `${printRoute}/${no_faktur}`;

    setTimeout(function() {
        window.location.reload();
    }, 3000);
});

//print data ke csv
document.getElementById('csv-button').addEventListener('click', function() {
    const no_faktur = noFaktur.value
    const csvRoute = document.querySelector('meta[name="csv-route"]').getAttribute('content');

    // Redirect ke route export CSV dengan nomor faktur yang dipilih
    const url = `${csvRoute}/${no_faktur}`;
    window.location.href = url;

    setTimeout(function() {
        window.location.reload();
    }, 3000);
});