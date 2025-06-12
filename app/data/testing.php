<?php 

require_once '../../app/config.php';

// parameter default untuk jqgrid
$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
$limit = isset($_REQUEST['rows']) ? $_REQUEST['rows'] : 10;
$sidx = isset($_REQUEST['sidx']) ? $_REQUEST['sidx'] : 'penjualan.tbl_penjualan.id_penjualan';
$sord = isset($_REQUEST['sord']) ? $_REQUEST['sord'] : 'DESC';

// global_search
$globalSearch = isset($_REQUEST['global_search']) ? $_REQUEST['global_search'] : '';

$queryPenjualan = "SELECT id_penjualan, no_bukti, DATE_FORMAT(tgl_bukti, '%d-%m-%Y') as tgl_bukti, nama_pelanggan FROM penjualan.tbl_penjualan LEFT JOIN penjualan.tbl_pelanggan ON penjualan.tbl_penjualan.pelanggan_id = penjualan.tbl_pelanggan.id";


// fitur search AND
$wh = '';
$searchOn = isset($_REQUEST['_search']) ? $_REQUEST['_search'] : 'false';
if ($searchOn == 'true') {
  $filters = json_decode($_REQUEST['filters'], true);
  // var_dump($_REQUEST);
  // var_dump($filters);
  foreach ($filters['rules'] as $rule) {
    $field = $rule['field'];
    $data = addslashes($rule['data']);

    if ($field == 'tgl_bukti' && !empty($data)) {
      $condition = "DATE_FORMAT($field, '%d-%m-%Y') LIKE '%" . $data . "%'";
      
    } else {
      // Field biasa pakai LIKE biasa
      $condition = "$field LIKE '%$data%'";
    }

    // Where Klausa
    ($wh == '') ? $wh .= " WHERE $condition" : $wh .= " AND $condition";

  }

}

// Global Search GLOBAL
if ($globalSearch != '') {
  $globalCond = "(no_bukti LIKE '%$globalSearch%' OR DATE_FORMAT(tgl_bukti, '%d-%m-%Y') LIKE '%$globalSearch%' OR nama_pelanggan LIKE '%$globalSearch%')";
  $wh = " WHERE $globalCond";
  
}

// hitung data
$count = count(query("$queryPenjualan $wh"));

if ($count > 0) {
  $total_pages = ceil($count / $limit);
} else {
  $total_pages = 0;
}

$start = $limit * $page - $limit;

$penjualans = query("$queryPenjualan $wh ORDER BY $sidx $sord LIMIT $start, $limit");

echo json_encode([
  "total" => $total_pages,
  "page" => $page,
  "records" => $count,
  "rows" => $penjualans,
]);
