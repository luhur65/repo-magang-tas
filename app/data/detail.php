<?php 

require_once '../../app/config.php'; 
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = isset($_GET['rows']) ? $_GET['rows'] : 10;
$sidx = isset($_GET['sidx']) ? $_GET['sidx'] : 'penjualan_id';
$sord = isset($_GET['sord']) ? $_GET['sord'] : 'DESC';

// $examp = isset($_GET['q']) ? $_GET['q'] : 1;
if (isset($_GET['id'])) {
  $id = $_GET['id'];

} else {

  $id = 0;

}

$count = count(query("SELECT * FROM penjualan.penjualan_detail WHERE penjualan_id = " . $id));


if ($count > 0) {
  $total_pages = ceil($count / $limit);
} else {
  $total_pages = 0;
}

if ($page > $total_pages) {
  $page = $total_pages;
}

$start = $limit * $page - $limit;

if ($start < 0) $start = 0;

$query = "SELECT nama_barang, qty, harga, (qty * harga) AS total FROM penjualan.penjualan_detail WHERE penjualan_id=$id ORDER BY $sidx $sord LIMIT $start, $limit";

$details = query($query);

echo json_encode([
  "total" => $total_pages,
  "page" => $page,
  "records" => $count,
  "rows" => $details,
]);
