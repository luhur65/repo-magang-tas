<?php

require_once './app/master_config.php';

$penjualans = query("SELECT penjualan.tbl_penjualan.*, penjualan.tbl_pelanggan.nama_pelanggan 
                     FROM penjualan.tbl_penjualan 
                     JOIN penjualan.tbl_pelanggan 
                     ON penjualan.tbl_penjualan.pelanggan_id = penjualan.tbl_pelanggan.id
                     ORDER BY penjualan.tbl_penjualan.id_penjualan DESC");

?>

<h1>Master Penjualan</h1>

<a href="?page=createMaster" class="btn btn-primary my-3">Tambah Data</a>

<div class="row g-2">
  <div class="col-md-10">
    <?php if (isset($_SESSION['flash'])): ?>
      <div class="alert alert-<?= $_SESSION['flash']['tipe']; ?> alert-dismissible fade show" role="alert"> <?= $_SESSION['flash']['pesan']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>
    <div class="card">
      <div class="card-header">
        <h4>Data Penjualan</h4>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">No Bukti</th>
                <th scope="col">Tanggal Bukti</th>
                <th scope="col">Pelanggan</th>
                <th scope="col">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1;
              foreach ($penjualans as $penjualan) : ?>
                <tr>
                  <th scope="row"><?= $i++; ?></th>
                  <td><?= $penjualan['no_bukti']; ?></td>
                  <td><?= date('d M Y', strtotime($penjualan['tgl_bukti'])); ?></td>
                  <td><?= $penjualan['nama_pelanggan']; ?></td>
                  <td>
                    <a href="?page=readMaster&id=<?= $penjualan['id_penjualan']; ?>">Detail</a>
                    <a href="?page=updateMaster&id=<?= $penjualan['id_penjualan']; ?>">Edit</a>
                    <a href="?page=deleteMaster&id=<?= $penjualan['id_penjualan']; ?>">Hapus</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>