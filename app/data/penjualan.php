<?php 

require_once '../penjualan_config.php';

function jsonPenjualanResponse($conn, $id)
{
  // Ambil seluruh id_penjualan terurut sesuai grid
  $sidx = isset($_REQUEST['sortname']) ? $_REQUEST['sortname'] : 'penjualan.tbl_penjualan.id_penjualan';
  $sord = isset($_REQUEST['sortorder']) ? $_REQUEST['sortorder'] : 'DESC';
  $query = "SELECT id_penjualan FROM penjualan.tbl_penjualan LEFT JOIN penjualan.tbl_pelanggan ON penjualan.tbl_penjualan.pelanggan_id = penjualan.tbl_pelanggan.id ORDER BY $sidx $sord";
  $result = mysqli_query($conn, $query);

  $ids = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $ids[] = $row['id_penjualan'];
  }

  // var_dump($ids)

  // Cari posisi id
  $rowIndex = array_search($id, $ids);

  // var_dump($rowIndex);
  // $rowNumber = $rowIndex !== false ? $rowIndex + 1 : 1;
  $rowNumber = $rowIndex + 1;
  $limit = isset($_REQUEST['rows']) ? intval($_REQUEST['rows']) : 10;
  $page = ceil($rowNumber / $limit);

  return [
    "id" => $id,
    "page" => $page,
    "count" => getTotalPenjualan($conn)
  ];
}

function getIDTerdekat($conn, $deletedId) 
{
  $sidx = isset($_REQUEST['sortname']) ? $_REQUEST['sortname'] : 'penjualan.tbl_penjualan.id_penjualan';
  $sord = isset($_REQUEST['sortorder']) ? $_REQUEST['sortorder'] : 'DESC';
  
  // Gunakan fungsi yang sudah ada untuk mendapatkan semua IDs
  $query = "SELECT id_penjualan FROM penjualan.tbl_penjualan LEFT JOIN penjualan.tbl_pelanggan ON penjualan.tbl_penjualan.pelanggan_id = penjualan.tbl_pelanggan.id ORDER BY $sidx $sord";
  $result = mysqli_query($conn, $query);

  $ids = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $ids[] = $row['id_penjualan'];
  }
  
  // Cari posisi yang akan dihapus
  $posisiTerhapus = array_search($deletedId, $ids);
  
  // if ($posisiTerhapus === false) {
  //   // Jika tidak ditemukan, ambil yang pertama (exclude yang dihapus)
  //   return !empty($ids) && $ids[0] != $deletedId ? $ids[0] : (isset($ids[1]) ? $ids[1] : null);
  // }
  // Hapus ID dari array
  // var_dump($ids);
  unset($ids[$posisiTerhapus]);
  $ids = array_values($ids); // Re-index array 
  // var_dump($ids);
  
  // Ambil ID pada posisi yang sama, atau sebelumnya jika tidak ada
  if (isset($ids[$posisiTerhapus])) {
    // var_dump("ini posisi sama");
    return $ids[$posisiTerhapus]; // Posisi yang sama
  } else if ($posisiTerhapus > 0 && isset($ids[$posisiTerhapus - 1])) {
    // var_dump("ini posisi terakhir");
    return $ids[$posisiTerhapus - 1]; // Posisi sebelumnya
  } else {
    // var_dump("empty");
    return !empty($ids) ? $ids[0] : null; // Fallback ke yang pertama
  }
}


function getTotalPenjualan($conn) 
{

  $queryCount = mysqli_query($conn, "SELECT COUNT(*) as count FROM penjualan.tbl_penjualan");
  $row = mysqli_fetch_assoc($queryCount);
  return $row['count'];

}

function reverseDate($date) 
{
  return date('Y-m-d', strtotime($date));
}

// function isValidTanggal($tgl) 
// {
//   // dd-mm-yyyy
//   $parts = explode('-', $tgl);
//   if (count($parts) !== 3) return false;

//   [$d, $m, $y] = $parts;
//   if (!checkdate((int)$m, (int)$d, (int)$y)) return false;

//   if ((int)$y < 1900 || (int)$y > 2099) return false;

//   return true;
// }

function tambah_penjualan($conn) {

  // Mysqli Transaction
  $conn->begin_transaction();

  try {

    // var_dump($_REQUEST);

    // Ambil data dari POST
    $no_bukti = $_POST['no_bukti'];
    $tgl_bukti = reverseDate($_POST['tgl_bukti']); // format: dd-mm-yyyy
    $pelanggan = $_POST['pelanggan'];

    // array penjualan barang
    $namabarangs = $_POST['namabarang'];
    $qtys = $_POST['qty'];
    $hargas = $_POST['harga'];
    
    $lenghtData = count($namabarangs);
    
    // insert penjualan
    $result = $conn->prepare("INSERT INTO penjualan.tbl_penjualan (no_bukti, tgl_bukti, pelanggan_id) VALUES (?, ?, ?)");
    $result->bind_param("ssi", $no_bukti, $tgl_bukti, $pelanggan);

    if (!$result->execute()) {
      throw new Exception("Gagal insert penjualan: " . $result->error);
    }

    $lastPenjualanID = $conn->insert_id; // id terakhir yang dimasukkan
    $result->close();

    // insert detail penjualan
    $penjualanBarang = $conn->prepare("INSERT INTO `penjualan`.`penjualan_detail` (`penjualan_id`, `nama_barang`, `qty`, `harga`) VALUES (?, UPPER(?), ?, ?)");

    for ($i = 0; $i < $lenghtData; $i++) {

      if ($namabarangs[$i] == "") {
        throw new Exception("Nama barang ada kosong");
      }

      if ($qtys[$i] == 0) {
        throw new Exception("Jumlah barang ada kosong");
      }

      if ($hargas[$i] == 0) {
        throw new Exception("Harga barang ada kosong");
      }

      // $namabarang = strtoupper($namabarangs[$i]);
      $penjualanBarang->bind_param("isdd", $lastPenjualanID, $namabarangs[$i], $qtys[$i], $hargas[$i]);
      // $penjualanBarang->bind_param("isdi", $lastPenjualanID, $namabarang, $qtys[$i], $hargas[$i]);
      $penjualanBarang->execute();
    }

    $penjualanBarang->close();

    // commit MySQL
    $conn->commit();
    echo json_encode(jsonPenjualanResponse($conn, $lastPenjualanID));

    
  } catch (mysqli_sql_exception | Exception $e) {

    // tangkap error MySQL atau ERROR Custom
    $conn->rollback();
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
    
  } 


}

function ubah_penjualan($conn, $id) {

  $conn->begin_transaction();

  $id = (int) $id;

  try {

    // $noBukti = $data['no_bukti'];
    $tgl_bukti = reverseDate($_POST['tgl_bukti']);
    $pelanggan = $_POST['pelanggan'];

    $namabarangs = $_POST['namabarang'];
    $qtys = $_POST['qty'];
    $hargas = $_POST['harga'];

    // update penjualan
    $statement = $conn->prepare("UPDATE `penjualan`.`tbl_penjualan` 
    SET `tgl_bukti` = ?, `pelanggan_id` = ? 
    WHERE `id_penjualan` = ?");
    $statement->bind_param("sii", $tgl_bukti, $pelanggan, $id);
    if (!$statement->execute()) {
      throw new Exception("Gagal insert penjualan: " . $statement->error);
    }

    $statement->close();
    
    // hapus data semua detail penjualan
    $hapusSemuaDataBarang = $conn->prepare("DELETE FROM `penjualan`.`penjualan_detail` WHERE `penjualan_id` = ?");
    $hapusSemuaDataBarang->bind_param('i', $id);
    if (!$hapusSemuaDataBarang->execute()) {
      throw new Exception("Gagal hapus detail penjualan: " . $hapusSemuaDataBarang->error);
    }

    $hapusSemuaDataBarang->close();

    // insert data barang secara batch
    $statement2 = $conn->prepare("INSERT INTO `penjualan`.`penjualan_detail` (`penjualan_id`, `nama_barang`, `qty`, `harga`) VALUES (?, UPPER(?), ?, ?)");

    for ($i = 0; $i < count($namabarangs); $i++) {

      // if ($namabarangs[$i] === "" || $qtys[$i] === "" || $hargas[$i] === "") {
      //   throw new Exception("Data masih ada kosong");
      // }

      if ($namabarangs[$i] == "") {
        throw new Exception("Nama barang ada kosong");
      }

      if ($qtys[$i] == 0) {
        throw new Exception("Jumlah barang ada kosong");
      }

      if ($hargas[$i] == 0) {
        throw new Exception("Harga barang ada kosong");
      }

      $statement2->bind_param("isdd", $id, $namabarangs[$i], $qtys[$i], $hargas[$i]);
      $statement2->execute();
      
    }

    $statement2->close();
    $conn->commit();
    echo json_encode(jsonPenjualanResponse($conn, $id));

  } catch(mysqli_sql_exception | Exception $e) {
    $conn->rollback();
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);

  } 

}

function hapus_penjualan($conn, $id) {


  $conn->begin_transaction();

  try {

    $id = (int) $id;

    // Dapatkan ID terdekat SEBELUM menghapus data
    $idTerdekat = getIDTerdekat($conn, $id);

    $statement = $conn->prepare("DELETE FROM `penjualan`.`tbl_penjualan` WHERE `id_penjualan` = ?");
    $statement->bind_param("i", $id);
    if (!$statement->execute) {
      throw new Exception("Gagal delete penjualan: " . $statement->error);
    }

    $statement->close();

    $hapusSemuaDataBarang = $conn->prepare("DELETE FROM `penjualan`.`penjualan_detail` WHERE `penjualan_id` = ?");
    $hapusSemuaDataBarang->bind_param('i', $id);
    $hapusSemuaDataBarang->execute();
    if (!$statement->execute) {
      throw new Exception("Gagal delete detail penjualan: " . $statement->error);
    }

    $hapusSemuaDataBarang->close();

    $conn->commit();

    // Gunakan ID terdekat yang sudah dihitung sebelumnya
    echo json_encode(jsonPenjualanResponse($conn, $idTerdekat));
    
  } catch (mysqli_sql_exception | Exception $e) {
    $conn->rollback();
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
    
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

    http_response_code(400);
    echo json_encode(["error" => "Url ada yang salah"]);

  }

} else {

  tambah_penjualan($conn);

}