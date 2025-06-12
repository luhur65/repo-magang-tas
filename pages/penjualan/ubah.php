<?php

require_once './app/penjualan_config.php';

$pelanggans = query("SELECT * FROM penjualan.tbl_pelanggan");

$idPenjualan = $_GET['id'];
$penjualan = query("SELECT * FROM penjualan.tbl_penjualan WHERE id_penjualan = " . $idPenjualan);

?>

<div class="row my-4">
  <div class="col-md-8">
    <?php if (isset($_POST['ubah_penjualan'])): ?>
      <?php if (ubahPenjualan($_POST) > 0): ?>
        <?php header("Location: ?page=penjualan") ?>
      <?php endif; ?>
    <?php endif; ?>
    <div class="card">
      <div class="card-header">
        <h4>Form Ubah Penjualan</h4>
      </div>
      <div class="card-body">
        <form action="" method="post">
          <?php foreach ($penjualan as $p): ?>
            <input type="text" name="id_penjualan" value="<?= $p['id_penjualan']; ?>" hidden>
            <div class="mb-3">
              <label for="no_bukti" class="form-label">No Bukti</label>
              <input type="text" class="form-control" id="no_bukti" name="no_bukti" value="<?= $p['no_bukti'] ?>" readonly disabled>
            </div>
            <div class="mb-3">
              <label for="tgl_bukti" class="form-label">Tgl Bukti</label>
              <input type="date" class="form-control" id="tgl_bukti" name="tgl_bukti" value="<?= $p['tgl_bukti'] ?>">
            </div>
            <div class="mb-3">
              <label for="pelanggan" class="form-label">Pelanggan</label>
              <select id="pelanggan" name="pelanggan" class="form-select" aria-label="Default select example">
                <option>Open this select menu</option>
                <?php foreach ($pelanggans as $pelanggan) : ?>
                  <option <?= ($pelanggan['id'] == $p['pelanggan_id']) ? 'selected' : ''; ?> value="<?= $pelanggan['id']; ?>"><?= $pelanggan['nama_pelanggan']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          <?php endforeach; ?>
          <button name="ubah_penjualan" type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>