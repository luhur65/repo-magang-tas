<?php

require_once './app/master_config.php';
$pelanggans = query("SELECT * FROM penjualan.tbl_pelanggan");

?>

<div class="row my-4">
  <div class="col-md">
    <?php if (isset($_POST['tambah_penjualan'])): ?>
      <?php if (tambahDetailPenjualan($_POST) > 0): ?>
        <?php header("Location: ?page=master") ?>
      <?php else: ?>
        <?php header("Location: ?page=master") ?>
      <?php endif; ?>
    <?php endif; ?> 
    <div class="card">
      <div class="card-header">
        <h4>Form Penjualan</h4>
      </div>
      <div class="card-body">
        <form action="" method="post">
          <div class="row">
            <div class="col-md-4">
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

            </div>
            <div class="col-md">
              <table class="table table-hover" id="tableBarang">
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
                  <tr>
                    <th scope="row">1</th>
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
                            <input type="number" class="form-control form-control-sm qty" placeholder="qty barang" name="qty[]">
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
                  </tr>
                </tbody>
              </table>
              <button type="button" class="btn btn-dark btn-sm" id="tambahbarang">+ Barang baru</button>
            </div>
          </div>
          <button name="tambah_penjualan" type="submit" class="btn btn-primary my-3">Pesan Barang</button>
        </form>
      </div>
    </div>
  </div>


</div>