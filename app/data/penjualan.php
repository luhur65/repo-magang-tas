<?php 

require_once '../penjualan_config.php';

function JSONData($conn) {

  $id = mysqli_insert_id($conn);
  $queryCount = mysqli_query($conn, "SELECT COUNT(*) as count FROM penjualan.tbl_penjualan");
  $row = mysqli_fetch_assoc($queryCount);
  $count = $row['count'];
  return json_encode([
    "id" => $id,
    "count" => $count
  ]);

}

function reverseDate($date) {
  return date('Y-m-d', strtotime($date));
}

function tambah_penjualan($conn) {

  // Ambil data dari POST
  $no_bukti = $_POST['no_bukti'];
  $tgl_bukti = reverseDate($_POST['tgl_bukti']); // format: dd-mm-yyyy
  $pelanggan = $_POST['pelanggan'];

  // array penjualan barang
  $namabarangs = $_POST['namabarang'];
  $qtys = $_POST['qty'];
  $hargas = $_POST['harga'];
  
  $lenghtData = count($namabarangs);
  
  $result = $conn->prepare("INSERT INTO penjualan.tbl_penjualan (no_bukti, tgl_bukti, pelanggan_id) VALUES (?, ?, ?)");
  $result->bind_param("ssi", $no_bukti, $tgl_bukti, $pelanggan);

  if ($result->execute()) {
    $result->close();

    $lastPenjualanID = $conn->insert_id; // id terakhir yang dimasukkan

    $penjualanBarang = $conn->prepare("INSERT INTO `penjualan`.`penjualan_detail` (`penjualan_id`, `nama_barang`, `qty`, `harga`) VALUES (?, UPPER(?), ?, ?)");

    for ($i = 0; $i < $lenghtData; $i++) {

      if ($namabarangs[$i] === "" || $qtys[$i] === "" || $hargas[$i] === "") {
        http_response_code(500);
        echo json_encode(["error" => "Data penjualan masih ada kosong!"]);
        return -1;
      }

      // $namabarang = strtoupper($namabarangs[$i]);
      
      $penjualanBarang->bind_param("isdd", $lastPenjualanID, $namabarangs[$i], $qtys[$i], $hargas[$i]);
      // $penjualanBarang->bind_param("isdi", $lastPenjualanID, $namabarang, $qtys[$i], $hargas[$i]);
      $penjualanBarang->execute();
    }

    $penjualanBarang->close();
    echo JSONData($conn);

  } else {
    http_response_code(500);
    echo json_encode(["error" => "Gagal menambah data"]);
  }

}

function ubah_penjualan($conn, $id) {

  // $noBukti = $data['no_bukti'];
  $tgl_bukti = reverseDate($_POST['tgl_bukti']);
  $pelanggan = $_POST['pelanggan'];

  $namabarangs = $_POST['namabarang'];
  $qtys = $_POST['qty'];
  $hargas = $_POST['harga'];


  $statement = $conn->prepare("UPDATE `penjualan`.`tbl_penjualan` 
  SET `tgl_bukti` = ?, `pelanggan_id` = ? 
  WHERE `id_penjualan` = ?");
  $statement->bind_param("sii", $tgl_bukti, $pelanggan, $id);

  if ($statement->execute()) {
    $statement->close();

    // hapus data
    $hapusSemuaDataBarang = $conn->prepare("DELETE FROM `penjualan`.`penjualan_detail` WHERE `penjualan_id` = ?");
    $hapusSemuaDataBarang->bind_param('i', $id);
    $hapusSemuaDataBarang->execute();

    $statement2 = $conn->prepare("INSERT INTO `penjualan`.`penjualan_detail` (`penjualan_id`, `nama_barang`, `qty`, `harga`) VALUES (?, UPPER(?), ?, ?)");

    for ($i = 0; $i < count($namabarangs); $i++) {

      if ($namabarangs[$i] === "" || $qtys[$i] === "" || $hargas[$i] === "") {
        http_response_code(500);
        echo json_encode(["error" => "Data penjualan masih ada kosong!"]);
        return -1;
      }

      // var_dump($hargas[$i]);
      // var_dump($qtys[$i]);
      // return false;


      // $namabarang = strtoupper($namabarangs[$i]);

      $statement2->bind_param("isdd", $id, $namabarangs[$i], $qtys[$i], $hargas[$i]);
      // $statement2->bind_param("isdi", $id, $namabarang, $qtys[$i], $hargas[$i]);
      $statement2->execute();
      
    }

    $statement2->close();
    echo JSONData($conn);

  } else {
    http_response_code(500);
    echo json_encode(["error" => "Gagal mengubah data"]);
  }

}

function hapus_penjualan($conn, $id) {

  $id = (int) $id;

  $statement = $conn->prepare("DELETE FROM `penjualan`.`tbl_penjualan` WHERE `id_penjualan` = ?");
  $statement->bind_param("i", $id);

  if ($statement->execute()) {
    $statement->close();

    $hapusSemuaDataBarang = $conn->prepare("DELETE FROM `penjualan`.`penjualan_detail` WHERE `penjualan_id` = ?");
    $hapusSemuaDataBarang->bind_param('i', $id);
    $hapusSemuaDataBarang->execute();
    $hapusSemuaDataBarang->close();

    echo JSONData($conn);
    
  } else {
    http_response_code(500);
    echo json_encode(["error" => "Gagal menambah data"]);
  }

}


// cek param 
// jika ada dimasukkan id maka itu ubah / delete
// jika tidak maka itu tambah data
if (isset($_GET['id'])) {

  $id = $_GET['id'];

  if (isset($_GET['action'])) {
    
    $action = $_GET['action'];
    
    if ($action == "ubah") {
      ubah_penjualan($conn, $id);
    }

    if ($action == "hapus") {
      hapus_penjualan($conn, $id);
    }

  } else {

    http_response_code(500);
    echo json_encode(["error" => "Url ada yang salah"]);

  }

} else {

  tambah_penjualan($conn);

}
