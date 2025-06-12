<?php

require_once './app/master_config.php';

$id = htmlspecialchars(addslashes($_GET['id'])); // dari URL 

if (isset($_POST['delete_penjualan'])) {
  if (hapusDetailPenjualan($id) > 0) {
    header("Location: ?page=master");
  }
}

$pelanggans = query("SELECT * FROM penjualan.tbl_pelanggan");

$idPenjualan = $_GET['id'];
$penjualan = query("SELECT * FROM penjualan.tbl_penjualan WHERE id_penjualan = " . $idPenjualan);

$barangs = query("SELECT * FROM penjualan.penjualan_detail WHERE penjualan_id = " . $idPenjualan);



?>

<div class="row my-4">
  <div class="col-md">
    <div class="card">
      <div class="card-header">
        <h4>Form Ubah Penjualan</h4>
      </div>
      <div class="card-body">
        <form action="" method="post">
          <div class="row">
            <div class="col-md-4">
              <?php foreach ($penjualan as $p): ?>
                <input type="text" name="id_penjualan" value="<?= $p['id_penjualan']; ?>" hidden>
                <div class="mb-3">
                  <label for="no_bukti" class="form-label">No Bukti</label>
                  <input type="text" class="form-control" id="no_bukti" name="no_bukti" value="<?= $p['no_bukti'] ?>" readonly disabled>
                </div>
                <div class="mb-3">
                  <label for="tgl_bukti" class="form-label">Tgl Bukti</label>
                  <input type="date" class="form-control" id="tgl_bukti" name="tgl_bukti" value="<?= $p['tgl_bukti'] ?>" readonly disabled>
                </div>
                <div class="mb-3">
                  <label for="pelanggan" class="form-label">Pelanggan</label>
                  <select id="pelanggan" name="pelanggan" class="form-select" aria-label="Default select example" disabled>
                    <option>Open this select menu</option>
                    <?php foreach ($pelanggans as $pelanggan) : ?>
                      <option <?= ($pelanggan['id'] == $p['pelanggan_id']) ? 'selected' : ''; ?> value="<?= $pelanggan['id']; ?>"><?= $pelanggan['nama_pelanggan']; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>

            </div>
            <div class="col-md">
              <table class="table table-hover" id="tableBarang" data-table="hapus">
                <thead>
                  <tr>
                    <th scope="col">No</th>
                    <th scope="col">Barang</th>
                    <th scope="col">Qty</th>
                    <th scope="col">Harga</th>
                    <th scope="col">Total</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $i = 1;
                  foreach ($barangs as $b): ?>
                    <!-- <input type="number" value="<?= $b['id_detail']; ?>" name="id_detail[]" hidden> -->
                    <tr>
                      <th scope="row"><?= $i++; ?></th>
                      <td>
                        <div class="row">
                          <div class="col">
                            <div class="mb-3">
                              <input type="text" class="form-control form-control-sm" placeholder="nama barang" name="namabarang[]" value="<?= $b['nama_barang']; ?>" readonly disabled>
                            </div>
                          </div>
                        </div>
                      </td>
                      <td>
                        <div class="row">
                          <div class="col">
                            <div class="mb-3">
                              <input type="number" class="form-control form-control-sm qty" placeholder="qty barang" name="qty[]" value="<?= $b['qty']; ?>" readonly disabled>
                            </div>
                          </div>
                        </div>
                      </td>
                      <td>
                        <div class=" row">
                          <div class="col">
                            <div class="mb-3">
                              <input type="text" class="form-control form-control-sm harga" placeholder="harga barang" name="harga[]" value="<?= $b['harga']; ?>" readonly disabled>
                            </div>
                          </div>
                        </div>
                      </td>
                      <td>
                        <div class=" row">
                          <div class="col">
                            <div class="mb-3">
                              <input type="text" class="form-control form-control-sm total" placeholder="total" readonly disabled>
                            </div>
                          </div>
                        </div>
                      </td>
                      <td>
                        <button type="button" class="btn btn-sm text-danger btnHapusData" data-barangID="<?= $b['id_detail']; ?>" data-idpenjualan="">
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
            </div>
          <?php endforeach; ?>
          </div>
          <button name="delete_penjualan" type="submit" class="btn btn-danger">Hapus Semua Data</button>
        </form>
      </div>
    </div>
  </div>
</div>