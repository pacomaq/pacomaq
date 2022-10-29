<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$spreadsheet->setActiveSheetIndex(0);
$activeSheet = $spreadsheet->getActiveSheet();
$activeSheet->setCellValue('A1', 'ID');
$activeSheet->setCellValue('B1', 'name');

require 'db.php';
$query = $db->query("SELECT * FROM item_categories ORDER BY id ASC");

if($query->num_rows > 0) {
	$i = 2;
	while($row = $query->fetch_assoc()){
		$activeSheet->setCellValue('A'.$i, $row['ID']);
		$activeSheet->setCellValue('B'.$i, $row['name']);
		$i++;
	}
}

$Excel_writer = new Xlsx($spreadsheet);
$Excel_writer->save('output/GeneratedXls_7.xlsx');

$query = $db->query("SELECT id, name FROM item_categories ORDER BY id ASC");
$posts = $query->fetch_all(MYSQLI_ASSOC);
file_put_contents('output/GeneratedJSON_7.json', json_encode($posts));
echo 'XLS and JSON Files SAVED';