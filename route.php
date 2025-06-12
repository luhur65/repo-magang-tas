<?php 

define('pages', './pages/');

if (isset($_GET['page'])) {
  $page = htmlspecialchars(addslashes(strip_tags($_GET['page'])), ENT_QUOTES);

  switch ($page) {
    case 'penjualan':
      require_once pages . 'penjualan/index.php';
      break;
    case 'createPenjualan':
      require_once pages . 'penjualan/tambah.php';
      break;
    case 'updatePenjualan':
      require_once pages . 'penjualan/ubah.php';
      break;
    case 'deletePenjualan':
      require_once pages . 'penjualan/hapus.php';
      break;
    case 'master':
      require_once pages . 'master/index.php';
      break;
    case 'createMaster':
      require_once pages . 'master/tambah.php';
      break;
    case 'readMaster':
      require_once pages . 'master/detail.php';
      break;
    case 'updateMaster':
      require_once pages . 'master/ubah.php';
      break;
    case 'deleteMaster':
      require_once pages . 'master/hapus.php';
      break;
    case 'deleteBarangMaster':
      require_once pages . 'master/hapussatubarang.php';
      break;

    // case 'pelanggan':
    //   require_once pages . 'pelanggan/index.php';
    
    default:
      require_once pages . 'home.php';
      break;
    }
    
  } else {
    
    require_once pages . 'home.php';
}