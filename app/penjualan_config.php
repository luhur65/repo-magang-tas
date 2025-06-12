<?php 

require_once 'config.php';

function tambahPenjualan($data) { 

  global $conn;

  $noBukti = $data['no_bukti'];
  $tglBukti = $data['tgl_bukti'];
  $pelanggan = $data['pelanggan'];

  $statement = $conn->prepare("INSERT INTO `penjualan`.`tbl_penjualan` (`no_bukti`, `tgl_bukti`, `pelanggan_id`) VALUES (?, ?, ?)");
  $statement->bind_param("ssi", $noBukti, $tglBukti, $pelanggan);

  if ($statement->execute()) {
    $affected = mysqli_affected_rows($conn);
    $statement->close();
    flashMessage('success', 'Berhasil Ditambahkan');
    return $affected;
    return http_response_code(200);

  } else {
    $statement->close();
    flashMessage('danger', 'Gagal Ditambahkan');
    // return -1;
    return http_response_code(500);
    
  }
}

function ubahPenjualan($data) {

  global $conn;

  $id = $data['id_penjualan'];
  // $noBukti = $data['no_bukti'];
  $tglBukti = $data['tgl_bukti'];
  $pelanggan = $data['pelanggan'];

  $statement = $conn->prepare("UPDATE `penjualan`.`tbl_penjualan` 
  SET `tgl_bukti` = ?, `pelanggan_id` = ? 
  WHERE `id_penjualan` = ?");
  $statement->bind_param("sii", $tglBukti, $pelanggan, $id);

  if ($statement->execute()) {
    $affected = mysqli_affected_rows($conn);
    $statement->close();
    flashMessage('success', 'Berhasil Diubah');
    return $affected;

  } else {
    $statement->close();
    flashMessage('danger', 'Gagal Diubah');
    return -1;

  }
}


function hapusPenjualan($id)
{
  global $conn;

  $id = (int) $id;

  $statement = $conn->prepare("DELETE FROM `penjualan`.`tbl_penjualan` WHERE `id_penjualan` = ?");
  $statement->bind_param("i", $id);

  if ($statement->execute()) {
    $affected = mysqli_affected_rows($conn);
    $statement->close();
    flashMessage('success', 'Berhasil Dihapus');
    return $affected; 

  } else {
    $statement->close();
    flashMessage('danger', 'Gagal Dihapus');
    return -1; 

  }
}


?>