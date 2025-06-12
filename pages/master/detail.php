<?php

require_once './app/master_config.php';

$id = htmlspecialchars(addslashes($_GET['id']));

$penjualans = query("SELECT * FROM penjualan.tbl_penjualan 
                     JOIN penjualan.tbl_pelanggan 
                     ON penjualan.tbl_penjualan.pelanggan_id = penjualan.tbl_pelanggan.id WHERE id_penjualan = " . $id);

if ($penjualans !== []) {

  $penjualanDetail = query("SELECT nama_barang, qty, harga, (qty * harga) AS total
  FROM penjualan.penjualan_detail
  JOIN penjualan.tbl_penjualan ON penjualan.penjualan_detail.penjualan_id = penjualan.tbl_penjualan.id_penjualan 
  WHERE id_penjualan = " . $id);
  
} else {

  flashMessage('danger', 'Data Penjualan Tidak Ditemukan');
  header("Location: ?page=master");
}

$totalAkhirHarga = 0;
foreach ($penjualanDetail as $pd) {
  $totalAkhirHarga += ($pd['harga'] * $pd['qty']);
}

?>

<?php foreach ($penjualans as $p): ?>
  <div class="container mt-3">
    <div class="card my-2 ">
      <div class="card-header bg-primary text-white">
        <h3 class="mb-0">Detail Penjualan</h3>
      </div>
      <div class="card-body">
        <div class="row mb-4">
          <div class="col-sm-6">
            <h5>Pelanggan:</h5>
            <p>
              <strong><?= $p['nama_pelanggan']; ?></strong>
              <!-- alamat <br> -->
              <!-- email <br> -->
              <!-- no telp -->
            </p>
          </div>
        </div>

        <div class="row mb-4">
          <div class="col-sm-6">
            <p><strong>No Bukti:</strong> <?= $p['no_bukti']; ?></p>
            <p><strong>Tanggal Transaksi:</strong> <?= date('d M Y', strtotime($p['tgl_bukti'])); ?> </p>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered" id="tableInvoice">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Nama Barang</th>
                <th class="text-end">Qty Barang</th>
                <th class="text-end">Harga Barang</th>
                <th class="text-end">Total</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1;
              foreach ($penjualanDetail as $pd): ?>
                <tr>
                  <td><?= $i++; ?></td>
                  <td><?= $pd['nama_barang']; ?></td>
                  <td class="text-end qtydetail"><?= $pd['qty']; ?></td>
                  <td class="text-end hargadetail"><?= $pd['harga']; ?></td>
                  <td class="text-end totaldetail"><?= $pd['total']; ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
            <tfoot>
              <tr>
                <th colspan="4" class="text-end">Total Akhir</th>
                <th class="text-end totalakhirdetail"><?= $totalAkhirHarga; ?></th>
              </tr>
            </tfoot>
          </table>
        </div>

      </div>
    </div>
  </div>
<?php endforeach; ?>