<?php

require_once '../../app/master_config.php';

if (hapusSatuBarang($_POST['idbarang']) == 200) {
  echo "berhasil";
} else {
  echo "gagal";
}


?>