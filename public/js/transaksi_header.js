const nomFaktur = document.querySelector('#no_faktur')

//input data no_faktur tanpa total bruto, total diskon, dan total jumlah
document.querySelector('#simpanH').addEventListener('click', function(){
    // document.querySelector('#inputH').disabled = true
    // console.log('test')

    const data = {
        _token: document.querySelector('input[name=_token]').value, // CSRF token
        no_faktur: noFaktur.value,
        tgl_faktur: document.querySelector('#tgl_faktur').value,
        kode_customer: document.querySelector('#kode_customer').value,
        kode_tjen: document.querySelector('#kode_tjen').value,
        total_bruto: getNumberFromRupiah(document.querySelector('#total_bruto').value) || 0,
        total_diskon: getNumberFromRupiah(document.querySelector('#total_diskon').value) || 0,
        total_jumlah: getNumberFromRupiah(document.querySelector('#total_jumlah').value) || 0
    };
    console.log(data)

    const tambahInputHeaderRoute = document.querySelector('meta[name="tambah-inputHeader-route"]').getAttribute('content');

    fetch(tambahInputHeaderRoute,{
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': data._token
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        alert('data faktur baru telah ditambahakan')
        console.log(result.data)
    })
})

// fungsi cari
const cariButton = document.querySelector('#cari-button')
cariButton.addEventListener('click', function() {
    
    console.log(noFaktur.value)
    const no_faktur = noFaktur.value
    // const noFaktur = document.querySelector('#no_faktur').value;

    if(no_faktur == ''){
        alert('isi nomor faktur')
    }
    else{
        fetchData()
    }
});

function fetchData() {
    const cariDataRoute = document.querySelector('meta[name="cariData-route"]').getAttribute('content');
    const no_faktur = noFaktur.value
    const btnDetail = document.getElementById('detailH')

    fetch(cariDataRoute, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
        },
        body: JSON.stringify({ no_faktur: no_faktur })
    })
    .then(response => response.json())
    // .then(result => console.log(result))
    .then(result => {
        console.log('Result:', result.data); // Debugging output
        if (result && result.status === 200) { // Pastikan result ada
            alert(result.message); // Alert jika data ditemukan
            btnDetail.hidden = false

            // Mengisi field dasar
            document.getElementById('no_faktur').value = result.data.no_faktur;
            document.getElementById('tgl_faktur').value = new Date(result.data.tgl_faktur).toISOString().split('T')[0]; 
            document.getElementById('kode_customer').value = result.data.kode_customer;
            document.getElementById('kode_tjen').value = result.data.kode_tjen;
            console.log(document.getElementById('total_bruto').value = rupiah(result.data.total_bruto))
            document.getElementById('total_jumlah').value = rupiah(result.data.total_jumlah)
            document.getElementById('total_diskon').value = rupiah(result.data.total_diskon)
            // const total_bruto = rupiah(document.querySelector('#total_bruto').value);
            // const total_jumlah = rupiah(document.querySelector('#total_jumlah').value)
            // const total_diskon = rupiah(document.querySelector('#total_diskon').value)

            btnDetail.addEventListener('click', function(){
                updateTable(result.data.detail_penjualan); // Memperbarui tabel dengan data
                updateTotals()
            })
            document.querySelector('#inputH').disabled = true
            document.querySelector('#hapusH').disabled = false
            document.querySelector('#editH').disabled = false
            document.querySelector('#printButton').disabled = false
            document.querySelector('#previewButton').disabled = false
            document.querySelector('#csv-button').disabled = false
            document.querySelector('#batalBtn').disabled = false
        } else {
            alert(result.message); // Alert jika data tidak ditemukan
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mencari data.');
    });
}

//fungsi hapus
document.querySelector('#hapusH').addEventListener('click', function() {
    const no_faktur = noFaktur.value

    const hapusFakturRoute = document.querySelector('meta[name="hapus-faktur-route"]').getAttribute('content');
    
    if (confirm('Apakah Anda yakin ingin menghapus faktur ini?')) {
        console.log(no_faktur)
        fetch(`${hapusFakturRoute}/${no_faktur}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
            }
        })
        .then(response => response.json())
        .then(result => {
            alert(result.message); // Tampilkan pesan hasil
            if (result.status === 200) {
                // Reset atau hapus tampilan yang ada di UI jika perlu
                updateTable([]); // Kosongkan tabel atau refresh tampilan

            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus data.');
        });
    }
});

//edit no faktur/header
document.querySelector('#editH').addEventListener('click', function() {
    const no_faktur = noFaktur.value
    const tgl_faktur = document.querySelector('#tgl_faktur').value;
    const kode_customer = document.querySelector('#kode_customer').value;
    const kode_tjen = document.querySelector('#kode_tjen').value;
    const total_bruto = getNumberFromRupiah(document.querySelector('#total_bruto').value);
    const total_jumlah = getNumberFromRupiah(document.querySelector('#total_jumlah').value)
    const total_diskon = getNumberFromRupiah(document.querySelector('#total_diskon').value)

    const updateFakturRoute = document.querySelector('meta[name="update-faktur-route"]').getAttribute('content');

    if (confirm('Apakah Anda yakin ingin mengedit data header ini?')) {
        const editData = {
            no_faktur: no_faktur,
            tgl_faktur: tgl_faktur,
            kode_customer: kode_customer,
            kode_tjen: kode_tjen,
            total_bruto: total_bruto,
            total_jumlah: total_jumlah,
            total_diskon: total_diskon,
        };
        console.log(editData)

        fetch(`${updateFakturRoute}/${no_faktur}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
            },
            body: JSON.stringify(editData)
        })
        .then(response => response.json())
        .then(result => {
            // alert(result.message); // Tampilkan pesan hasil
            if (result.status === 200) {
                // Lakukan tindakan setelah data berhasil diperbarui
                alert('Data header berhasil diperbarui edit');
                updateTable([])
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengedit data: ' + error.message);
        });
    }
});

function populateDetailForm(item) {
    // Mengisi form atau card dengan data barang yang dipilih
    document.getElementById('kode_barang').value = item.kode_barang;
    document.getElementById('nama_barang').value = item.barang ? item.barang.nama_barang : 'Nama tidak tersedia';
    document.getElementById('harga_barang').value = rupiah(item.harga)
    document.getElementById('qty').value = Math.round(item.qty)
    document.getElementById('diskon').value = item.diskon
    document.getElementById('bruto').value = rupiah(item.bruto);
    document.getElementById('jumlah').value = rupiah(item.jumlah);

    document.getElementById('t_djual_id').value = item.id
}


function updateTable(data) {
    const tableBody = document.querySelector('tbody');

    // Jika data tidak kosong
    if (Object.keys(data).length !== 0) {
        let detailData = data;

        // Pastikan detailData adalah array
        if (!Array.isArray(detailData)) {
            detailData = [detailData];
        }

        console.log(detailData);
        document.getElementById('aksi').hidden = false;

        detailData.forEach((item, index) => {
            console.log(item)
            // Mencari baris yang ada berdasarkan ID
            const existingRow = tableBody.querySelector(`tr[data-id="${item.no_faktur}_${item.kode_barang}"]`);
            console.log(existingRow)

            // Jika baris sudah ada, update isi baris tersebut
            if (existingRow) {
                existingRow.innerHTML = `
                    <td>${item.no_faktur}</td>
                    <td>${item.kode_barang}</td>
                    <td>${item.barang ? item.barang.nama_barang : 'Nama tidak tersedia'}</td>
                    <td>${rupiah(item.harga)}</td>
                    <td>${Math.round(item.qty)}</td>
                    <td>${item.diskon}%</td>
                    <td>${rupiah(item.bruto)}</td>
                    <td>${rupiah(item.jumlah)}</td>
                    <td>
                        <button type="button" class="btn-detail" data-index="${index}">Detail</button>
                    </td>
                `;
            } else {
                // Jika baris tidak ada, tambahkan baris baru
                const row = `
                    <tr data-id="${item.no_faktur}_${item.kode_barang}">
                        <td>${item.no_faktur}</td>
                        <td>${item.kode_barang}</td>
                        <td>${item.barang ? item.barang.nama_barang : 'Nama tidak tersedia'}</td>
                        <td>${rupiah(item.harga)}</td>
                        <td>${Math.round(item.qty)}</td>
                        <td>${item.diskon}%</td>
                        <td>${rupiah(item.bruto)}</td>
                        <td>${rupiah(item.jumlah)}</td>
                        <td>
                            <button type="button" class="btn-detail" data-index="${index}">Detail</button>
                        </td>
                    </tr>`;
                tableBody.innerHTML += row;
            }
        });

        // Tambahkan event listener untuk tombol detail
        document.querySelectorAll('.btn-detail').forEach(button => {
            button.addEventListener('click', function() {
                const itemIndex = this.getAttribute('data-index'); // Mengambil index item
                const selectedItem = detailData[itemIndex]; // Mengambil data barang berdasarkan index
                if (selectedItem) {
                    populateDetailForm(selectedItem);
                    document.getElementById('editB').disabled = false;
                    document.getElementById('hapusB').disabled = false;
                    batalButton.disabled=false

                    document.querySelector('#kode_barang').readOnly = false
                    document.querySelector('#qty').readOnly = false
                    document.querySelector('#diskon').readOnly = false
                    inputButton.disabled = true
                }
            });
        });
    } else {
        batalnRestUi(); // Fungsi ini diharapkan mengatur UI saat tidak ada data
    }
}

// function updateTable(data) {
//     const tableBody = document.querySelector('tbody');

//     // Jika data tidak kosong
//     if (data && data.length > 0) {  // Pastikan data adalah array dan memiliki elemen
//         let detailData = data;

//         // Debugging: Cek isi data yang diterima
//         console.log("Data yang diterima:", detailData);

//         document.getElementById('aksi').hidden = false;

//         detailData.forEach((item, index) => {
//             console.log("Memproses item:", item);

//             // Mencari baris yang ada berdasarkan ID
//             const existingRow = tableBody.querySelector(`tr[data-id="${item.no_faktur}_${item.kode_barang}"]`);
            
//             // Jika baris sudah ada, update isi baris tersebut
//             if (existingRow) {
//                 existingRow.innerHTML = `
//                     <td>${item.no_faktur}</td>
//                     <td>${item.kode_barang}</td>
//                     <td>${item.barang ? item.barang.nama_barang : 'Nama tidak tersedia'}</td>
//                     <td>${rupiah(item.harga)}</td>
//                     <td>${Math.round(item.qty)}</td>
//                     <td>${item.diskon}%</td>
//                     <td>${rupiah(item.bruto)}</td>
//                     <td>${rupiah(item.jumlah)}</td>
//                     <td>
//                         <button type="button" class="btn-detail" data-index="${index}">Detail</button>
//                     </td>
//                 `;
//             } else {
//                 // Jika baris tidak ada, tambahkan baris baru
//                 const row = `
//                     <tr data-id="${item.no_faktur}_${item.kode_barang}">
//                         <td>${item.no_faktur}</td>
//                         <td>${item.kode_barang}</td>
//                         <td>${item.barang ? item.barang.nama_barang : 'Nama tidak tersedia'}</td>
//                         <td>${rupiah(item.harga)}</td>
//                         <td>${Math.round(item.qty)}</td>
//                         <td>${item.diskon}%</td>
//                         <td>${rupiah(item.bruto)}</td>
//                         <td>${rupiah(item.jumlah)}</td>
//                         <td>
//                             <button type="button" class="btn-detail" data-index="${index}">Detail</button>
//                         </td>
//                     </tr>`;
//                 tableBody.innerHTML += row;
//             }
//         });

//         // Tambahkan event listener untuk tombol detail
//         document.querySelectorAll('.btn-detail').forEach(button => {
//             button.addEventListener('click', function() {
//                 const itemIndex = this.getAttribute('data-index'); // Mengambil index item
//                 console.log(this.getAttribute('data-id'))
//                 const selectedItem = detailData[itemIndex]; // Mengambil data barang berdasarkan index
//                 console.log(selectedItem)
//                 if (selectedItem) {
//                     populateDetailForm(selectedItem);
//                     document.getElementById('editB').disabled = false;
//                     document.getElementById('hapusB').disabled = false;
//                     batalButton.disabled = false;

//                     document.querySelector('#kode_barang').readOnly = false;
//                     document.querySelector('#qty').readOnly = false;
//                     document.querySelector('#diskon').readOnly = false;
//                     inputButton.disabled = true;
//                 }
//             });
//         });
//     } else {
//         console.log("Tidak ada data untuk ditampilkan");
//         batalnRestUi(); // Fungsi ini diharapkan mengatur UI saat tidak ada data
//     }
// }

