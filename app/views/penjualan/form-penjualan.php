<?php

require_once '../../penjualan_config.php';
$pelanggans = query("SELECT * FROM penjualan.tbl_pelanggan");

$formID = "tambah";
$notif = "";
$barangs = [
  [
    "nama_barang" => "",
    "qty" => "",
    "harga" => ""
  ]
];

if (isset($_GET['id_del'])) {
  $formID = "hapus";
}

if (isset($_GET['id'])) {
  $formID = "ubah";
}

if ($formID != 'tambah') {

  // Cek apakah parameter id atau id_del ada, dan ambil nilainya
  $idpenjualan = isset($_GET['id']) ? $_GET['id'] : (isset($_GET['id_del']) ? $_GET['id_del'] : null);

  $query = "SELECT * FROM penjualan.tbl_penjualan WHERE id_penjualan = " . $idpenjualan;

  $penjualan = mysqli_query($conn, $query);
  $p = mysqli_fetch_assoc($penjualan);

  // Convert MySQL date (Y-m-d) to d-m-Y for jQuery datepicker
  $tgl_bukti = isset($p['tgl_bukti']) ? date('d-m-Y', strtotime($p['tgl_bukti'])) : '';

  // Data Barang Penjualan
  $barangs = query("SELECT * FROM penjualan.penjualan_detail WHERE penjualan_id = " . $idpenjualan);
  if (count($barangs) == 0) {
    // Tampilkan satu baris input kosong
    $notif = "Data barang masih kosong";
    $barangs = [
      [
        'nama_barang' => '',
        'qty' => '',
        'harga' => ''
      ]
    ];
  }
}

?>

<form action="" method="post" id="data-form">
  <table>
    <tbody>
      <tr>
        <td style="padding-right: 20px;">No Bukti</td>
        <td style="margin: 20px;">
          <input type="text" name="no_bukti" id="no_bukti" class="ui-widget-content ui-corner-all" autocomplete="off" data-inputmask="'mask': 'AAA99', 'greedy': 'false'" required value="<?= $formID != 'tambah' ? $p['no_bukti'] : '' ?>" <?= ($formID != "tambah") ? 'readonly' : 'autofocus'; ?>>
        </td>
      </tr>
      <tr>
        <td style="padding-right: 20px;">Tanggal Bukti</td>
        <td>
          <input type="text" name="tgl_bukti" id="tgl_bukti" class="ui-widget-content ui-corner-all" data-inputmask="'alias': 'datetime','inputFormat': 'dd-mm-yyyy'" inputmode="numeric" required value="<?= $formID != 'tambah' ? $tgl_bukti : '' ?>" <?= ($formID == "hapus") ? 'readonly disabled' : ''; ?>>
        </td>
      </tr>
      <tr>
        <td style="padding-right: 20px;">Pelanggan</td>
        <td>
          <select style="width: 100%" class="ui-widget-content ui-corner-all js-example-placeholder-single js-states js-example-matcher" name="pelanggan" id="pelanggan" required <?= ($formID == "hapus") ? 'disabled' : ''; ?>>
            <option value="0"></option>
            <?php foreach ($pelanggans as $pelanggan) : ?>
              <option <?php if ($formID != "tambah") {
                        echo ($pelanggan['id'] == $p['pelanggan_id']) ? 'selected' : '';
                      } ?> value="<?= $pelanggan['id']; ?> "><?= $pelanggan['nama_pelanggan']; ?></option>
            <?php endforeach; ?>
          </select>
        </td>
      </tr>
      <tr>
        <td style="padding-right: 20px;">Harga Total Barang</td>
        <td>
          <input type="text" id="totalSemuaBarang" class="outline-none ui-widget-content ui-corner-all" readonly disabled>
        </td>
      </tr>
    </tbody>
  </table>

  <br>
  <h5 style="color: red;"><?= $notif; ?></h5>
  <table class="border-collapse" id="tableBarang" data-table="<?= $formID; ?>">
    <thead>
      <tr>
        <th scope="col" class="border border-gray-600">No</th>
        <th scope="col" class="border border-gray-600">Nama Barang</th>
        <th scope="col" class="border border-gray-600">Banyak Barang</th>
        <th scope="col" class="border border-gray-600">Harga Satuan</th>
        <th scope="col" class="border border-gray-600">Total Harga</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($barangs as $i => $barang): ?>
        <tr>
          <th scope="row" class="border border-gray-600"><?= $i + 1; ?></th>
          <td class="border border-gray-600">
            <div class="row">
              <div class="col">
                <div class="my-1">
                  <input type="text" class="focus:outline-hidden p-2" placeholder="Kecap Bangau" name="namabarang[]" value="<?= $barang['nama_barang']; ?>" <?= ($formID == "hapus") ? 'readonly disabled' : ''; ?>>
                </div>
              </div>
            </div>
          </td>
          <td class="border border-gray-600">
            <div class="row">
              <div class="col">
                <div class="my-1">
                  <input type="text" class="focus:outline-hidden p-2 qty text-center" placeholder="10" name="qty[]" value="<?= $barang['qty']; ?>" <?= ($formID == "hapus") ? 'readonly disabled' : ''; ?>>
                </div>
              </div>
            </div>
          </td>
          <td class="border border-gray-600">
            <div class="row">
              <div class="col">
                <div class="my-1">
                  <input type="text" class="focus:outline-hidden p-2 harga text-right" placeholder="Rp. 1000" name="harga[]" value="<?= $barang['harga']; ?>" <?= ($formID == "hapus") ? 'readonly disabled' : ''; ?>>
                </div>
              </div>
            </div>
          </td>
          <td class="border border-gray-600">
            <div class="row">
              <div class="col">
                <div class="my-1">
                  <input type="text" class="focus:outline-hidden p-2 total text-right" placeholder="Rp 10.000" readonly disabled>
                </div>
              </div>
            </div>
          </td>
          <td>
            <button type="<?= ($formID == "tambah") ? 'reset' : 'button'; ?>" class="px-2 text-red-700 btnHapusBaris">
              <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                <polyline points="3 6 5 6 21 6"></polyline>
                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
              </svg>
            </button>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <button type="button" class="px-4 py-1 bg-blue-400 rounded mt-4 active:bg-blue-600 shadow-lg" id="tambahbarang">
    <p class="text-sm text-center">Barang Baru</p>
  </button>

</form>

<script>
  $(document).ready(function() {

    // Datepicker
    $('#tgl_bukti').datepicker({
      dateFormat: 'dd-mm-yyyy'
    });

    // Select2
    $(".js-example-placeholder-single").select2({
      placeholder: "Pilih Pelanggan",
      allowClear: true,
      dropdownParent: $('#dialogElem')
    });

    // InputMask -> Uppercase
    // Inputmask.extendDefinitions({
    //   '*': {
    //     validator: "[A-Za-z0-9 ]",
    //     casing: "upper" //auto uppercasing
    //   }
    // });

    // function formatToIDR(number) {
    //   return new Intl.NumberFormat('id-ID', {
    //     style: 'currency',
    //     currency: 'IDR',
    //     minimumFractionDigits: 0
    //   }).format(number);
    // }

    const setMoneyNumeric = {
      digitGroupSeparator: ',',
      decimalCharacter: '.',
      decimalPlaces: 2,
      minimumValue: '0',
      // currencySymbol: 'Rp ',
      modifyValueOnWheel: false,
      currencySymbolPlacement: 'p',
      // unformatOnSubmit: true
    }

    const setQtyNumeric = {
      digitGroupSeparator: '', // Tanpa pemisah ribuan
      decimalCharacter: '.',
      decimalPlaces: 2,
      minimumValue: '0',
    }

    function updateTotalRow(row) {
      const qtyInput = row.querySelector('.qty');
      const hargaInput = row.querySelector('.harga');
      const totalInput = row.querySelector('.total');

      const anQty = AutoNumeric.getAutoNumericElement(qtyInput);
      const anHarga = AutoNumeric.getAutoNumericElement(hargaInput);
      const anTotal = AutoNumeric.getAutoNumericElement(totalInput);

      const qty = anQty.getNumber() || 0;
      const harga = anHarga ? anHarga.getNumber() : 0;
      anTotal.set(qty * harga);

    }

    function updateGrandTotal() {
      let sum = 0;

      tableBarang.querySelectorAll('.total').forEach(ti => {
        const anT = AutoNumeric.getAutoNumericElement(ti);
        sum += anT.getNumber();
      });

      NumericTotal.set(sum);
    }

    const tableBarang = document.querySelector('#tableBarang tbody');
    const totalHarga = document.querySelector('#totalSemuaBarang');
    const NumericTotal = new AutoNumeric(totalHarga, setMoneyNumeric);

    if (tableBarang !== null) {

      const isUpdateForm = tableBarang.parentElement.dataset.table;
      const rowCount = tableBarang.rows.length;

      if (isUpdateForm == "ubah" || isUpdateForm == "hapus") {

        Array.from(tableBarang.rows).forEach((row, idx) => {
          const btnHapusData = row.querySelectorAll('.btnHapusBaris');
          Array.from(btnHapusData).forEach(btn => {
            btn.addEventListener('click', function() {
              // Jika berhasil, hapus row dari tabel
              row.remove();
              // Update nomor urut
              Array.from(tableBarang.rows).forEach((r, i) => {
                const th = r.querySelector('th');
                if (th) th.textContent = i + 1;
              });

              updateGrandTotal();
            });
          });
        });

        const rows = tableBarang.querySelectorAll('tr');
        rows.forEach(row => {
          const qtyInput = row.querySelector('.qty');
          const hargaInput = row.querySelector('.harga');
          const totalInput = row.querySelector('.total');

          // AutoNumeric
          const anQty = new AutoNumeric(qtyInput, setQtyNumeric);
          const anHarga = new AutoNumeric(hargaInput, setMoneyNumeric);
          const anTotal = new AutoNumeric(totalInput, setMoneyNumeric);

          let value = anHarga.getNumber();
          anHarga.set(value);
          updateTotalRow(row);

        });

      }

      function calculateTotal() {
        const rows = tableBarang.querySelectorAll('tr');
        rows.forEach(row => {
          const qtyInput = row.querySelector('.qty');
          const hargaInput = row.querySelector('.harga');
          const totalInput = row.querySelector('.total');

          // Cek AutoNumeric
          let anQty = AutoNumeric.getAutoNumericElement(qtyInput);
          if (!anQty) {
            anQty = new AutoNumeric(qtyInput, setQtyNumeric);
          }
          let anHarga = AutoNumeric.getAutoNumericElement(hargaInput);
          if (!anHarga) {
            anHarga = new AutoNumeric(hargaInput, setMoneyNumeric);
          }
          let anTotal = AutoNumeric.getAutoNumericElement(totalInput);
          if (!anTotal) {
            anTotal = new AutoNumeric(totalInput, setMoneyNumeric);
          }

          // Format harga 
          hargaInput.addEventListener('input', function(e) {
            let value = anHarga.getNumber();
            anHarga.set(value);
            updateTotalRow(row);
            updateGrandTotal();
          });

          qtyInput.addEventListener('input', function() {
            updateTotalRow(row);
            updateGrandTotal();
          });

          updateTotalRow(row);

        });
      }

      calculateTotal();
      updateGrandTotal();

      const btnTambahBarang = document.querySelector('#tambahbarang');
      if (btnTambahBarang !== null) {

        btnTambahBarang.addEventListener('click', function() {
          const rowCount = tableBarang.rows.length;
          const newRow = document.createElement('tr');
          newRow.innerHTML = `
      <th scope="row" class="border border-gray-600">${rowCount + 1}</th>
      <td class="border border-gray-600">
        <div class="row">
          <div class="col">
            <div class="my-1">
              <input type="text" class="focus:outline-hidden p-2" placeholder="nama barang" name="namabarang[]">
            </div>
          </div>
        </div>
      </td>
      <td class="border border-gray-600">
        <div class="row">
          <div class="col">
            <div class="my-1">
              <input type="text" class="focus:outline-hidden p-2 qty text-center" placeholder="10" name="qty[]">
            </div>
          </div>
        </div>
      </td>
      <td class="border border-gray-600">
        <div class="row">
          <div class="col">
            <div class="my-1">
              <input type="text" class="focus:outline-hidden p-2 harga text-right" placeholder="Rp 1.000" name="harga[]">
            </div>
          </div>
        </div>
      </td>
      <td class="border border-gray-600">
        <div class="row">
          <div class="col">
            <div class="my-1">
              <input type="text" class="focus:outline-hidden p-2 total text-right" placeholder="Rp 10.000" readonly>
            </div>
          </div>
        </div>
      </td>
      <td>
        <button type="button" class="px-2 text-red-700 btnHapusBaris">
          <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
            <polyline points="3 6 5 6 21 6"></polyline>
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
          </svg>
        </button>
      </td>
    `;
          tableBarang.appendChild(newRow);

          // Inputmask({
          //   mask: "*{1,50}", // Maksimal 50 karakter (bebas)
          //   definitions: {
          //     '*': {
          //       validator: "[A-Za-z0-9 ]", // huruf, angka, spasi
          //       casing: "upper", // opsional: bisa juga 'upper' atau 'title'
          //       placeholder: ''
          //     }
          //   }
          // }).mask(document.querySelectorAll("input[name='namabarang[]']"));

          // cari tombol btn hapus row, lalu hapus row berdasarkan row yang dihapus
          const btnHapusBaris = newRow.querySelector('.btnHapusBaris');
          btnHapusBaris.addEventListener('click', function() {
            newRow.remove();
            Array.from(tableBarang.rows).forEach((row, idx) => {
              const th = row.querySelector('th');
              if (th) th.textContent = idx + 1;
            });
            updateGrandTotal();
          });

          // console.log(tableBarang.rows);

        });

        btnTambahBarang.addEventListener('click', function() {
          setTimeout(calculateTotal, 100); // kasih jeda waktu
        });
      }


    }

  });
</script>