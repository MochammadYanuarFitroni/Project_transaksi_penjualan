// Mengambil elemen-elemen input
const kodeBarang = document.querySelector('#kode_barang');
const namaBarang = document.querySelector('#nama_barang');
const hargaBarang = document.querySelector('#harga_barang');
// const noFaktur = document.querySelector('#no_faktur');

// Tombol-tombol input barang
const inputButton = document.querySelector('#inputB');
const batalButton = document.querySelector('#batalB');
const hapusButton = document.querySelector('#hapusB');
const editButton = document.querySelector('#editB');
const simpanButton = document.querySelector('#add-item');

// Fungsi untuk mengaktifkan atau menonaktifkan tombol
function toggleButtons(enable) {
    inputButton.disabled = !enable;
    hapusButton.disabled = !enable;
    editButton.disabled = !enable;
    simpanButton.disabled = !enable;
    batalButton.disabled = !enable;
}

// Event Listener untuk Kode Barang
kodeBarang.addEventListener("keyup", function () {
    kodeBarang.value = kodeBarang.value.toUpperCase(); // Mengubah Kode Barang ke huruf kapital
    let kode_barang = kodeBarang.value;
    if (kode_barang.length > 0) {
        // Fetch data barang berdasarkan Kode Barang yang diinput
        fetch(`/get-barang/${kode_barang}`)
            .then(response => response.json())
            .then(result => {
                if (result) {
                    console.log(result.data)
                    // Jika data barang ditemukan, isi field Nama Barang dan Harga Barang
                    namaBarang.value = result.data.nama_barang;
                    hargaBarang.value = result.data.harga_barang;
                    hitungBrutoJumlah();
                    // Aktifkan tombol-tombol jika data barang ditemukan
                    inputButton.disabled = false
                    batalButton.disabled = false
                } else {
                    // Jika data barang tidak ditemukan, kosongkan input dan nonaktifkan tombol
                    namaBarang.value = "";
                    hargaBarang.value = "0";
                    toggleButtons(false);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Jika ada error, nonaktifkan tombol
                toggleButtons(false);
            });
    } else {
        // Jika Kode Barang kosong, kosongkan input dan nonaktifkan tombol
        namaBarang.value = "";
        hargaBarang.value = "0";
        toggleButtons(false);
    }
});

// Tambahkan event listener untuk perubahan QTY dan diskon
document.querySelector('#qty').addEventListener('input', hitungBrutoJumlah);
document.querySelector('#diskon').addEventListener('input', hitungBrutoJumlah);

// Fungsi untuk menghitung Bruto dan Jumlah berdasarkan input Harga, QTY, dan Diskon
function hitungBrutoJumlah() {
    let harga = parseFloat(hargaBarang.value) || 0;
    let qty = parseFloat(document.querySelector('#qty').value) || 0;
    let diskonPersen = parseFloat(document.querySelector('#diskon').value) || 0;

    let bruto = harga * qty;
    let nilaiDiskon = bruto * (diskonPersen / 100);
    let jumlah = bruto - nilaiDiskon;

    document.querySelector('#bruto').value = bruto.toFixed(2); // Mengisi Bruto
    document.querySelector('#jumlah').value = jumlah.toFixed(2); // Mengisi Jumlah setelah diskon
}

// Pada saat pertama kali halaman dimuat, tombol-tombol di-disable
toggleButtons(false);

document.querySelector('#inputB').addEventListener('click', function (e) {
    e.preventDefault();

    const kodeBarang = document.querySelector('#kode_barang').value;
    const namaBarang = document.querySelector('#nama_barang').value;
    const qty = parseInt(document.querySelector('#qty').value);
    const harga = parseFloat(document.querySelector('#harga_barang').value);
    const diskon = parseFloat(document.querySelector('#diskon').value) || 0;
    const bruto = parseFloat(document.querySelector('#bruto').value);
    const jumlah = parseFloat(document.querySelector('#jumlah').value);

    const no_faktur = document.querySelector('#no_faktur').value
    const tgl_faktur = document.querySelector('#tgl_faktur').value
    const kode_customer = document.querySelector('#kode_customer').value
    const kode_tjen = document.querySelector('#kode_tjen').value

    console.log(tgl_faktur)

    if(no_faktur === '' || tgl_faktur === '' || kode_customer === '' || kode_tjen === ''){
        // simpanButton.disabled = true
        alert('tolong isi bagian faktur terlebih dahulu!!')
        return;
    }
    else{
        // Validasi input barang tidak boleh kosong
        if (kodeBarang === '' || namaBarang === '' || qty <= 0) {
            alert('Kode barang, nama barang, dan jumlah (QTY) harus diisi dengan benar!');
            return;
        }
    
        // Cek apakah kode barang sudah ada di tabel
        const existingRows = document.querySelectorAll('.table tbody tr');
        let isBarangExists = false;
    
        existingRows.forEach(row => {
            const existingKodeBarang = row.querySelector('td:nth-child(2)').textContent;
            console.log(existingKodeBarang)
    
            if (existingKodeBarang === kodeBarang) {
                isBarangExists = true;
            }
        });
    
        if (isBarangExists) {
            alert('Barang dengan kode ' + kodeBarang + ' sudah ada di tabel!');
            simpanButton.disabled = true
            return;
        }
        else{
            simpanButton.disabled = false  
            // Jika barang belum ada, tambahkan ke tabel
            const tbody = document.querySelector('.table tbody');
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>${document.querySelector('#no_faktur').value}</td>
                <td>${kodeBarang}</td>
                <td>${namaBarang}</td>
                <td>Rp. ${harga}</td>
                <td>${qty}</td>
                <td>${diskon}%</td>
                <td>Rp. ${bruto}</td>
                <td>Rp. ${jumlah}</td>
            `;
        
            tbody.appendChild(newRow);
            toggleSimpanButton()
        
            // Reset form input barang
            document.querySelector('#kode_barang').value = '';
            document.querySelector('#nama_barang').value = '';
            document.querySelector('#harga_barang').value = '0';
            document.querySelector('#qty').value = '0';
            document.querySelector('#diskon').value = '0';
            document.querySelector('#bruto').value = 'Rp. 0';
            document.querySelector('#jumlah').value = 'Rp 0';
        
            // Update total jika diperlukan
            updateTotals();
        }
    }


})

document.querySelector('#add-item').addEventListener('click', function (e) {
    e.preventDefault();
    
    const tbody = document.querySelector('.table tbody');
    const rows = tbody.querySelectorAll('tr');
    const items = [];

    rows.forEach(row => {
        const columns = row.querySelectorAll('td');
        items.push({
            no_faktur: columns[0].innerText,
            kode_barang: columns[1].innerText,
            harga: parseFloat(columns[3].textContent.replace(/Rp. /, '').replace(/,/g, '')),
            qty: parseInt(columns[4].innerText) || 0,
            diskon: parseFloat(columns[5].innerText.replace('%', '').trim()) || 0,
            bruto: parseFloat(columns[6].textContent.replace(/Rp. /, '').replace(/,/g, '')),
            jumlah: parseFloat(columns[7].textContent.replace(/Rp. /, '').replace(/,/g, '')),
        });
    });

    console.log(items)

    const tambahBarangRoute = document.querySelector('meta[name="tambah-barang-route"]').getAttribute('content');
    const dataToSave = {
        _token: document.querySelector('input[name=_token]').value,
        items: items // Mengirim array items
    };

    fetch(tambahBarangRoute, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': dataToSave._token
        },
        body: JSON.stringify(dataToSave)
    })
    .then(response => response.json())
    .then(result => {
        if (result.status === 200) {
            alert('Data baru berhasil disimpan.');
            console.log('Data baru:', result.newItems);
            simpanButton.disabled = true
        } else if (result.status === 400) {
            alert('Beberapa data sudah ada di database.');
            console.log('Data yang sudah ada:', result.existingItems);
            console.log('Data yang berhasil disimpan:', result.newItems);
            simpanButton.disabled = true
        }
    })
    .catch(error => console.error('Error:', error));
});


//hapus barang
document.getElementById('hapusB').addEventListener('click', function() {
    const t_djual_id = document.getElementById('t_djual_id').value
    const hapusBarangRoute = document.querySelector('meta[name="hapus-barang-route"]').getAttribute('content');
    // const rowToDelete = document.querySelector(`tr[data-id="${t_djual_id}"]`)
    // console.log(rowToDelete)

    if (confirm('Apakah Anda yakin ingin menghapus barang ini?')) {
        fetch(`${hapusBarangRoute}/${t_djual_id}`, {
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
                // Ambil data terbaru dari server dan update tabel
                // fetchData();
                const rowToDelete = document.querySelector(`tr[data-id="${t_djual_id}"]`);
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
})


//edit barang/data
document.getElementById('editB').addEventListener('click', function() {
    const t_djual_id = document.getElementById('t_djual_id').value; // Ambil ID dari hidden input
    const updateBarangRoute = document.querySelector('meta[name="update-barang-route"]').getAttribute('content');

    // Ambil data dari form
    const editData = {
        kode_barang: document.getElementById('kode_barang').value,
        harga: parseFloat(document.getElementById('harga_barang').value),
        qty: parseFloat(document.getElementById('qty').value),
        diskon: parseFloat(document.getElementById('diskon').value),
        bruto: parseFloat(document.getElementById('bruto').value),
        jumlah: parseFloat(document.getElementById('jumlah').value)
    };

    // Validasi data sebelum mengirim
    if (!editData.kode_barang || !editData.harga || !editData.qty) {
        alert('Mohon lengkapi semua field sebelum mengedit.');
        return;
    }

    if (confirm('Apakah Anda yakin ingin mengedit barang ini?')) {
        fetch(`${updateBarangRoute}/${t_djual_id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
            },
            body: JSON.stringify(editData)
        })
        .then(response => response.json())
        .then(result => {
            alert(result.message); // Tampilkan pesan hasil
            if (result.status === 200) {
                // const rowToUpdate = document.querySelector(`tr[data-id="${result.data.id}"]`);
    
                // if (rowToUpdate) {
                //     // Memperbarui baris dengan data baru
                //     rowToUpdate.innerHTML = `
                //         <td>${result.data.no_faktur}</td>
                //         <td>${result.data.kode_barang}</td>
                //         <td>${result.data.barang ? result.data.barang.nama_barang : 'Nama tidak tersedia'}</td>
                //         <td>Rp. ${Math.round(result.data.harga)}</td>
                //         <td>${Math.round(result.data.qty)}</td>
                //         <td>${Math.round(result.data.diskon)}%</td>
                //         <td>Rp. ${Math.round(result.data.bruto)}</td>
                //         <td>Rp. ${Math.round(result.data.jumlah)}</td>
                //         <td>
                //             <button type="button" class="btn-detail" data-id="${result.data.id}">Detail</button>
                //         </td>
                //     `;
                // }
                updateTable(result.data)
                clearFormBarang(); // Fungsi untuk mengosongkan form
                updateTotals()
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengedit barang.');
        });
    }
});

// Fungsi untuk menghitung total Bruto, Diskon, dan Jumlah dari tabel
function updateTotals() {
    let totalBruto = 0;
    let totalDiskon = 0;
    let totalJumlah = 0;

    // Ambil semua baris dari tabel (kecuali header)
    const rows = document.querySelectorAll('.table tbody tr');
    // console.log(rows.value)

    rows.forEach(row => {
        const cells = row.querySelectorAll('td')
        cells.forEach((cell) => {
            console.log(cell.textContent); // Mencetak isi sel
        });
        // Ambil nilai Bruto dan Jumlah dari sel tabel, konversi menjadi angka
        const bruto = parseFloat(row.cells[6].textContent.replace(/Rp. /, '').replace(/,/g, ''));
        console.log(bruto)
        const jumlah = parseFloat(row.cells[7].textContent.replace(/Rp. /, '').replace(/,/g, ''));
        console.log(jumlah)

        totalBruto += bruto;
        totalJumlah += jumlah;
    });

    // Total diskon dihitung dari selisih antara Bruto dan Jumlah
    totalDiskon = totalBruto - totalJumlah;

    // Update nilai total Bruto, Diskon, dan Jumlah pada elemen input di form
    document.getElementById('total_bruto').value = `Rp. ${totalBruto}`;
    document.getElementById('total_diskon').value = `Rp. ${totalDiskon}`;
    document.getElementById('total_jumlah').value = `Rp. ${totalJumlah}`;
    
    // Simpan nilai asli dalam elemen tersembunyi jika dibutuhkan
    document.querySelector('#hidden_total_bruto').value = totalBruto;
    document.querySelector('#hidden_total_diskon').value = totalDiskon;
    document.querySelector('#hidden_total_jumlah').value = totalJumlah;

    // Cek input apakah valid untuk mengaktifkan tombol simpan
    // checkInputs();
}

// Fungsi untuk memeriksa apakah input total bruto, diskon, dan jumlah valid
function checkInputs() {
    const totalBruto = parseFloat(document.getElementById('hidden_total_bruto').value) || 0;
    const totalDiskon = parseFloat(document.getElementById('hidden_total_diskon').value) || 0;
    const totalJumlah = parseFloat(document.getElementById('hidden_total_jumlah').value) || 0;
    const simpanButton = document.getElementById('simpanH');

    // Jika semua nilai bukan 0, aktifkan tombol simpan, sebaliknya nonaktifkan
    if (totalBruto !== 'Rp 0.00' && totalDiskon !== 'Rp 0.00' && totalJumlah !== 'Rp 0.00') {
        simpanButton.removeAttribute('disabled');
    } else {
        simpanButton.setAttribute('disabled', true);
    }
}

const simpanButtonH = document.getElementById('simpanH')
function toggleSimpanButton() {
    const rows = document.querySelectorAll('.table tbody tr');
    // Aktifkan tombol jika ada baris baru
    simpanButtonH.disabled = rows.length === 0; // Jika tidak ada baris, tombol dinonaktifkan
}