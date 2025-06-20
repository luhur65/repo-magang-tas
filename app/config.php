<?php

session_start();

$hostname = 'localhost';
$username = 'root';
$pass = '';
$dbname = 'penjualan';

/* Tell mysqli to throw an exception if an error occurs */
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = mysqli_connect($hostname, $username, $pass, $dbname);

// Query Database
function query($query)
{

  global $conn;
  $result = mysqli_query($conn, $query);
  if ($result === false) {
    // Optional: log error
    // error_log(mysqli_error($conn));
    return [];
  }
  $rows   = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
  }

  return $rows;
}

function flashMessage($type, $message)
{
  return $_SESSION['flash'] = [
    'tipe' => $type,
    'pesan' => $message
  ];
}
