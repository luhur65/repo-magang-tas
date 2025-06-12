
function formatToIDR(number) {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(number);
}

// Remove non-digit characters for database
function parseIDR(str) {
  return parseInt(str.replace(/[^0-9]/g, ''), 10) || 0;
}

const tableBarang = document.querySelector('#tableBarang tbody');

if (tableBarang !== null) {

  const isUpdateForm = tableBarang.parentElement.dataset.table;
  const rowCount = tableBarang.rows.length;

  if (isUpdateForm == "ubah" || isUpdateForm == "hapus") {

    Array.from(tableBarang.rows).forEach((row, idx) => {
      const btnHapusData = row.querySelectorAll('.btnHapusBaris');
      Array.from(btnHapusData).forEach(btn => {
        btn.addEventListener('click', function () {
          // Jika berhasil, hapus row dari tabel
          row.remove();
          // Update nomor urut
          Array.from(tableBarang.rows).forEach((r, i) => {
            const th = r.querySelector('th');
            if (th) th.textContent = i + 1;
          });
        });
      });
    });

    const rows = tableBarang.querySelectorAll('tr');
    rows.forEach(row => {
      const qtyInput = row.querySelector('.qty');
      const hargaInput = row.querySelector('.harga');
      const totalInput = row.querySelector('.total');

      function updateTotal() {
        const qty = parseInt(qtyInput.value) || 0;
        const harga = parseIDR(hargaInput.value);
        const total = qty * harga;
        totalInput.value = total ? formatToIDR(total) : '';
      }

      let value = parseIDR(hargaInput.value);
      hargaInput.value = value ? formatToIDR(value) : '';
      updateTotal();

      // ketika form dikirim 
      const form = document.querySelector('form');
      form.addEventListener('submit', function () {
        hargaInput.value = parseIDR(hargaInput.value);
        totalInput.value = parseIDR(totalInput.value);
      });
    });

  }

  function calculateTotal() {
    const rows = tableBarang.querySelectorAll('tr');
    rows.forEach(row => {
      const qtyInput = row.querySelector('.qty');
      const hargaInput = row.querySelector('.harga');
      const totalInput = row.querySelector('.total');

      function updateTotal() {
        const qty = parseInt(qtyInput.value) || 0;
        const harga = parseIDR(hargaInput.value);
        const total = qty * harga;
        totalInput.value = total ? formatToIDR(total) : '';
      }

      // Format harga 
      hargaInput.addEventListener('input', function (e) {
        let value = parseIDR(hargaInput.value);
        hargaInput.value = value ? formatToIDR(value) : '';
        updateTotal();
      });

      qtyInput.addEventListener('input', updateTotal);

      // ketika form dikirim 
      const form = document.querySelector('form');
      form.addEventListener('submit', function () {
        hargaInput.value = parseIDR(hargaInput.value);
        totalInput.value = parseIDR(totalInput.value);
      });
    });
  }

  calculateTotal();

  const btnTambahBarang = document.querySelector('#tambahbarang');
  if (btnTambahBarang !== null) {

    btnTambahBarang.addEventListener('click', function () {
      const rowCount = tableBarang.rows.length;
      const newRow = document.createElement('tr');
      newRow.innerHTML = `
      <th scope="row">${rowCount + 1}</th>
      <td>
        <div class="row">
          <div class="col">
            <div class="mb-3">
              <input type="text" class="form-control form-control-sm" placeholder="nama barang" name="namabarang[]">
            </div>
          </div>
        </div>
      </td>
      <td>
        <div class="row">
          <div class="col">
            <div class="mb-3">
              <input type="text" class="form-control form-control-sm qty" placeholder="qty barang" name="qty[]">
            </div>
          </div>
        </div>
      </td>
      <td>
        <div class="row">
          <div class="col">
            <div class="mb-3">
              <input type="text" class="form-control form-control-sm harga" placeholder="harga barang" name="harga[]">
            </div>
          </div>
        </div>
      </td>
      <td>
        <div class="row">
          <div class="col">
            <div class="mb-3">
              <input type="text" class="form-control form-control-sm total" placeholder="total" readonly>
            </div>
          </div>
        </div>
      </td>
      <td>
        <button type="button" class="btn btn-sm text-danger btnHapusBaris">
          <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
            <polyline points="3 6 5 6 21 6"></polyline>
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
          </svg>
        </button>
      </td>
    `;
      tableBarang.appendChild(newRow);

      // cari tombol btn hapus row, lalu hapus row berdasarkan row yang dihapus
      const btnHapusBaris = newRow.querySelector('.btnHapusBaris');
      btnHapusBaris.addEventListener('click', function () {
        newRow.remove();
        Array.from(tableBarang.rows).forEach((row, idx) => {
          const th = row.querySelector('th');
          if (th) th.textContent = idx + 1;
        });
        // for (let index = 0; index < rowCount; index++) {
        //     const th = tableBarang.rows[index].querySelector('th');

        //     if (th) {
        //       th.textContent = index+1;
        //     }

        // }
      });

      // console.log(tableBarang.rows);

    });

    btnTambahBarang.addEventListener('click', function () {
      setTimeout(calculateTotal, 100); // kasih jeda waktu
    });
  }


}

document.addEventListener('DOMContentLoaded', function () {
  const tableInvoice = document.querySelector('#tableInvoice tbody');
  if (tableInvoice !== null) {
    const rowsInvoice = tableInvoice.querySelectorAll('tr');
    rowsInvoice.forEach(row => {
      // const qtyInvoice = row.querySelector('.qtydetail');
      const hargaInvoice = row.querySelector('.hargadetail');
      const totalInvoice = row.querySelector('.totaldetail');

      hargaInvoice.textContent = formatToIDR(hargaInvoice.textContent);
      totalInvoice.textContent = formatToIDR(totalInvoice.textContent);

      console.log(hargaInvoice.textContent);

    });

    const tableFoot = document.querySelector('#tableInvoice tfoot');
    const rowfoot = tableFoot.querySelector('tr');
    const totalakhir = rowfoot.querySelector('.totalakhirdetail');

    totalakhir.textContent = formatToIDR(totalakhir.textContent);
  }

  if (tableBarang.parentElement.dataset.table == "hapus") {

    Array.from(tableBarang.rows).forEach((row, idx) => {
      const btnHapusData = row.querySelectorAll('.btnHapusData');
      Array.from(btnHapusData).forEach(btn => {
        btn.addEventListener('click', function () {
          const idBarang = btn.dataset.barangid;
          const penjualanId = btn.dataset.idpenjualan;

          // Buat objek XMLHttpRequest baru setiap klik, agar tidak share instance
          const xhr = new XMLHttpRequest();
          xhr.open("POST", "./pages/master/hapussatubarang.php", true);
          xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
          xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
              // Jika berhasil, hapus row dari tabel
              row.remove();
              // Update nomor urut
              Array.from(tableBarang.rows).forEach((r, i) => {
                const th = r.querySelector('th');
                if (th) th.textContent = i + 1;
              });
              if (xhr.responseText == "berhasil") {
                alert("Berhasil Dihapus");
              } else {
                alert("Gagal Dihapus");
              }
            }
          };
          xhr.send("idbarang=" + encodeURIComponent(idBarang));
        });
      });
    });

    console.log("Ini halaman hapus")

  }

});