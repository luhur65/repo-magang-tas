<?php

require_once 'config.php';

function tambahDetailPenjualan($data) 
{

  global $conn;

  // var_dump($data);
  $noBukti = $data['no_bukti'];
  $tglBukti = $data['tgl_bukti'];
  $pelanggan = $data['pelanggan'];
  // array namabarang, qty, harga, 
  $namabarangs = $data['namabarang'];
  $qtys = $data['qty'];
  $hargas = $data['harga'];

  $lastPenjualanID = $conn->insert_id;

  // input secara bersamaan
  $lenghtData = count($namabarangs);

  for ($i=0; $i < $lenghtData; $i++) {
    if ($namabarangs[$i] === "" || $qtys[$i] === "" || $hargas[$i] === "") {
      flashMessage('danger', 'Ada data barang yang kosong!');
      return -1;
    }
  }

  $statement1 = $conn->prepare("INSERT INTO `penjualan`.`tbl_penjualan` (`no_bukti`, `tgl_bukti`, `pelanggan_id`) VALUES (?, ?, ?)");
  $statement1->bind_param("ssi", $noBukti, $tglBukti, $pelanggan);

  if ($statement1->execute()) {
    $statement1->close();
  }

  // banyak data
  $totalInserted = 0;

  $statement2 = $conn->prepare("INSERT INTO `penjualan`.`penjualan_detail` (`penjualan_id`, `nama_barang`, `qty`, `harga`) VALUES (?, ?, ?, ?)");

  for ($i=0; $i < $lenghtData; $i++) { 

    $statement2->bind_param("isis", $lastPenjualanID, $namabarangs[$i], $qtys[$i], $hargas[$i]);
    $statement2->execute();

    if ($statement2->affected_rows > 0) {
      $totalInserted += $statement2->affected_rows; // Tambahkan jika berhasil
    }
  }

  if ($totalInserted > 0) {
    $statement2->close();
    flashMessage('success', 'Sukses beli '. $totalInserted .' barang!');
    return $totalInserted;

  } else {
    $statement2->close();
    flashMessage('danger', 'Gagal Beli');
    return -1;

  }

}


function ubahDetailPenjualan($data)
{

  global $conn;

  $id = $data['id_penjualan'];
  // $noBukti = $data['no_bukti'];
  $tglBukti = $data['tgl_bukti'];
  $pelanggan = $data['pelanggan'];

  // array namabarang, qty, harga, 
  $namabarangs = $data['namabarang'];
  $qtys = $data['qty'];
  $hargas = $data['harga'];
  $iddetails = $data['id_detail'];

  $statement1 = $conn->prepare("UPDATE `penjualan`.`tbl_penjualan` 
  SET `tgl_bukti` = ?, `pelanggan_id` = ? 
  WHERE `id_penjualan` = ?");
  $statement1->bind_param("sii", $tglBukti, $pelanggan, $id);
  
  // UPDATE `penjualan`.`penjualan_detail` SET `nama_barang` = 'dsadasd', `qty` = '2', `harga` = '3000' WHERE (`id_detail` = '10');

  if ($statement1->execute()) {
    $statement1->close();
  } else {
    $statement1->close();
    flashMessage('danger', 'Gagal Ubah Data Penjualan');
    return -1;
  }

  // hapus data
  $hapusSemuaDataBarang = $conn->prepare("DELETE FROM `penjualan`.`penjualan_detail` WHERE `penjualan_id` = ?");
  $hapusSemuaDataBarang->bind_param('i', $id);
  $hapusSemuaDataBarang->execute();

  $totalUpdated = 0;
  $statement2 = $conn->prepare("INSERT INTO `penjualan`.`penjualan_detail` (`penjualan_id`, `nama_barang`, `qty`, `harga`) VALUES (?, ?, ?, ?)");

  for ($i = 0; $i < count($namabarangs); $i++) {

    $statement2->bind_param("isis", $id, $namabarangs[$i], $qtys[$i], $hargas[$i]);
    $statement2->execute();

    if ($statement2->affected_rows > 0) {
      $totalUpdated += $statement2->affected_rows; // Tambahkan jika berhasil
    }
  }

  if ($totalUpdated === 1) {
      // Hanya 1 data yang berhasil diupdate
      flashMessage('success', 'Hanya 1 data yang diupdate');
  } else if ($totalUpdated > 1) {
      flashMessage('info ', $totalUpdated . 'Berhasil diupdate data penjualan barang');
  } else {
    return -1; // gagal
  }
}


function hapusDetailPenjualan($id)
{
  global $conn;

  $id = (int) $id;

  $statement = $conn->prepare("DELETE FROM `penjualan`.`tbl_penjualan` WHERE `id_penjualan` = ?");
  $statement->bind_param("i", $id);

  $hapusSemuaDataBarang = $conn->prepare("DELETE FROM `penjualan`.`penjualan_detail` WHERE `penjualan_id` = ?");
  $hapusSemuaDataBarang->bind_param('i', $id);

  if ($statement->execute()) {
    $hapusSemuaDataBarang->execute();
    $affected = mysqli_affected_rows($conn);
    $statement->close();
    $hapusSemuaDataBarang->close();
    flashMessage('success', 'Berhasil Dihapus');
    return $affected;
  } else {
    $statement->close();
    flashMessage('danger', 'Gagal Dihapus');
    return -1;
  }
}

function hapusSatuBarang($id)
{
  global $conn;

  $hapus = $conn->prepare("DELETE FROM `penjualan`.`penjualan_detail` WHERE `id_detail` = ?");
  $hapus->bind_param('i', $id);

  if ($hapus->execute()) {

    $affected = mysqli_affected_rows($conn);
    $hapus->close();

    if ($affected > 0) {
      return 200;
      
    } else {
      return 400;

    }

  }

  return 400;

}