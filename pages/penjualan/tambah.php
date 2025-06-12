<?php

require_once './app/penjualan_config.php';
$pelanggans = query("SELECT * FROM penjualan.tbl_pelanggan");

?>

<div class="row my-4">
  <div class="col-md-8">
    <?php if (isset($_POST['tambah_penjualan'])): ?>
      <?php if (tambahPenjualan($_POST) > 0): ?>
        <?php header("Location: ?page=penjualan") ?>
      <?php endif; ?>
    <?php endif; ?>
    <div class="card">
      <div class="card-header">
        <h4>Form Penjualan</h4>
      </div>
      <div class="card-body">
        <form action="" method="post">
          <div class="mb-3">
            <label for="no_bukti" class="form-label">No Bukti</label>
            <input type="text" class="form-control" id="no_bukti" name="no_bukti" value="<?= mt_rand(1111, 9999) ?>" readonly>
          </div>
          <div class="mb-3">
            <label for="tgl_bukti" class="form-label">Tgl Bukti</label>
            <input type="date" class="form-control" id="tgl_bukti" name="tgl_bukti">
          </div>
          <div class="mb-3">
            <label for="pelanggan" class="form-label">Pelanggan</label>
            <select id="pelanggan" name="pelanggan" class="form-select" aria-label="Default select example">
              <option selected>Open this select menu</option>
              <?php foreach ($pelanggans as $pelanggan) : ?>
                <option value="<?= $pelanggan['id']; ?>"><?= $pelanggan['nama_pelanggan']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <button name="tambah_penjualan" type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>