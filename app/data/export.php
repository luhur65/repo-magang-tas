<?php 

require_once '../../vendor/autoload.php';
require_once '../config.php';

use PhpOffice\PhpSpreadsheet\Exception\Exception as PhpSpreadsheetException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


// try-catch untuk menangani semua error
try {
    // parameter
    $sidx = isset($_REQUEST['sidx']) ? $_REQUEST['sidx'] : 'penjualan.tbl_penjualan.id_penjualan';
    $sord = isset($_REQUEST['sord']) ? $_REQUEST['sord'] : 'DESC';
    $filename = 'Laporan_Penjualan_' . date('Ymd_His') . '.xlsx';
    $globalSearch = isset($_REQUEST['global_search']) ? $_REQUEST['global_search'] : '';
    $searchOn = isset($_REQUEST['_search']) ? $_REQUEST['_search'] : 'false';
    $startRange = !empty($_REQUEST['start_range']) ? (int)$_REQUEST['start_range'] : 0;
    $endRange = !empty($_REQUEST['end_range']) ? (int)$_REQUEST['end_range'] : 0;

    // filter pencarian
    $queryPenjualan = "SELECT id_penjualan, no_bukti, DATE_FORMAT(tgl_bukti, '%d-%m-%Y') as tgl_bukti, nama_pelanggan FROM penjualan.tbl_penjualan LEFT JOIN penjualan.tbl_pelanggan ON penjualan.tbl_penjualan.pelanggan_id = penjualan.tbl_pelanggan.id";
    $wh = '';

    // Filter dari toolbar jqGrid
    if ($searchOn == 'true' && !empty($_REQUEST['filters'])) {
        $filters = json_decode($_REQUEST['filters'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Format filter (JSON) tidak valid.");
        }
        foreach ($filters['rules'] as $rule) {
            $field = $rule['field'];
            $data = addslashes($rule['data']);
            $condition = ($field == 'tgl_bukti') ? "DATE_FORMAT(tgl_bukti, '%d-%m-%Y') LIKE '%$data%'" : "$field LIKE '%$data%'";
            ($wh == '') ? $wh .= " WHERE $condition" : $wh .= " AND $condition";
        }
    }

    // Filter dari Global Search
    if ($globalSearch != '') {
        $globalCond = "(no_bukti LIKE '%$globalSearch%' OR DATE_FORMAT(tgl_bukti, '%d-%m-%Y') LIKE '%$globalSearch%' OR nama_pelanggan LIKE '%$globalSearch%')";
        $wh = " WHERE $globalCond";
    }

    // rentang data
    $limitClause = ""; 

    if ($startRange > 0 && $endRange > 0) {
        $offset = $startRange - 1; 
        $countToFetch = $endRange - $startRange + 1;
        $limitClause = "LIMIT $offset, $countToFetch";
    }

    // ambil data
    $exportQuery = "$queryPenjualan $wh ORDER BY $sidx $sord $limitClause";
    $dataToExport = query($exportQuery); 

    if (empty($dataToExport)) {
        throw new Exception("Tidak ada data untuk diekspor pada rentang atau kriteria yang dipilih.");
    }

    // buat file excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $rowNum = 1; 

    foreach ($dataToExport as $row) {
        $sheet->setCellValue('A' . $rowNum, 'No Bukti : ')->getStyle('A' . $rowNum)->getFont()->setBold(true);
        $sheet->setCellValue('B' . $rowNum, $row['no_bukti']);
        $rowNum++; 

        $sheet->setCellValue('A' . $rowNum, 'Tgl Bukti: ')->getStyle('A' . $rowNum)->getFont()->setBold(true);
        $sheet->setCellValue('B' . $rowNum, $row['tgl_bukti']);
        $rowNum++;

        $sheet->setCellValue('A' . $rowNum, 'Pelanggan: ')->getStyle('A' . $rowNum)->getFont()->setBold(true);
        $sheet->setCellValue('B' . $rowNum, $row['nama_pelanggan']);
        $rowNum++; 
        $rowNum++;

        // Header Detail
        $headerDetailRowNum = $rowNum;
        $sheet->mergeCells('B'.$headerDetailRowNum.':E'.$headerDetailRowNum);
        $sheet->setCellValue('B' . $headerDetailRowNum, 'DETAIL BARANG');
        $sheet->getStyle('B' . $headerDetailRowNum)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B' . $headerDetailRowNum . ':E' . $headerDetailRowNum)->getFont()->setBold(true);
        $rowNum++;
        
        $sheet->setCellValue('B' . $rowNum, 'Nama Barang');
        $sheet->setCellValue('C' . $rowNum, 'Qty');
        $sheet->setCellValue('D' . $rowNum, 'Harga');
        $sheet->setCellValue('E' . $rowNum, 'Total');
        $sheet->getStyle('B' . $rowNum . ':E' . $rowNum)->getFont()->setBold(true);
        $rowNum++;

        // Data Detail
        $idPenjualan = $row['id_penjualan'];
        $queryDetail = "SELECT nama_barang, qty, harga FROM penjualan.penjualan_detail WHERE penjualan_id = $idPenjualan";
        $detailBarang = query($queryDetail);

        if (count($detailBarang) > 0) {
            $startRowDetail = $rowNum;
            foreach ($detailBarang as $detail) {
                $sheet->setCellValue('B' . $rowNum, $detail['nama_barang']);
                $sheet->setCellValue('C' . $rowNum, $detail['qty']);
                $sheet->setCellValue('D' . $rowNum, $detail['harga']);
                $sheet->setCellValue('E' . $rowNum, "=C" . $rowNum . "*D" . $rowNum);
                $sheet->getStyle('C' . $rowNum)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_GENERAL);
                $sheet->getStyle('D' . $rowNum . ':E' . $rowNum)->getNumberFormat()->setFormatCode('"Rp "#,##0');
                $rowNum++;
            }
            $endRowDetail = $rowNum - 1;

            // Grand Total
            $formulaGrandTotal = "=SUM(E" . $startRowDetail . ":E" . $endRowDetail . ")";
            $sheet->setCellValue('D' . $rowNum, 'Grand Total:');
            $sheet->setCellValue('E' . $rowNum, $formulaGrandTotal);
            $sheet->getStyle('D' . $rowNum . ':E' . $rowNum)->getFont()->setBold(true);
            $sheet->getStyle('E' . $rowNum)->getNumberFormat()->setFormatCode('"Rp "#,##0');
        }

        $rowNum += 2; // Pemisah antar data penjualan
    }

    foreach (range('A', 'E') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment;filename=\"$filename\"");
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;

} catch (Throwable $e) {
  
    http_response_code(500); 
    echo json_encode([
        'error' => $e->getMessage(),
    ]);
    exit;
}