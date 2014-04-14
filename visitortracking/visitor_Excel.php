<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');

include 'header.inc.php';
	
/** Include PHPExcel */
require_once 'lib/excel/Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

$searhCond = "";
if (@$_POST['country_name'] != "") {
    $searhCond .= " AND vi.country='" . $_POST['country_name'] . "'";
    $CountryName = @$_POST['country_name'];
} else {
    $CountryName = "All";
}
if (@$_POST['city_name'] != "") {
    $searhCond .= " AND vi.city='" . $_POST['city_name'] . "'";
    $City = @$_POST['city_name'];
} else {
    $City = "All";
}
if (@$_POST['IpAddress'] != "") {
    $searhCond .= " AND vi.ip_address='" . $_POST['IpAddress'] . "'";
    $IpAddress = @$_POST['IpAddress'];
} else {
    $IpAddress = "All";
}

if (@$_POST['page_id'] != "") {
    $searhCond .= " AND vi.page_id='" . $_POST['page_id'] . "'";
    try {

        $stmt = $dbcon->prepare("SELECT page_name FROM `page` WHERE id=" . $_POST['page_id'], array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $analyticsData = $stmt->fetchALL(PDO::FETCH_ASSOC);
            foreach ($analyticsData as $aData) {
                $Page = $aData['page_name'];
            }
        }
    } catch (PDOException $e) {
        print $e->getMessage();
    }
} else {
    $Page = 'ALL';
}

if (@$_POST['fromdate'] != "") {
    $searhCond .= " AND date(vi.datetime)='" . $_POST['fromdate'] . "'";
}

$orderField = (@$_POST['order_type'] != "") ? $_POST['order_type'] : "vi.datetime";
switch ($orderField) {
    case 'vi.ip_address':
        $OrderType = 'IP Address ';
        break;
    case 'vi.datetime':
        $OrderType = 'Date ';
        break;
    case 'vi.country':
        $OrderType = 'Country ';
        break;
    case 'vi.city':
        $OrderType = 'City ';
        break;
    case 'vi.page_name':
        $OrderType = 'Page ';
        break;
}


$orderby = (@$_POST['order_by'] != "") ? $_POST['order_by'] : "DESC";
if (@$_POST['order_by'] != "ASC") {
    $OrderType .= " By Descending";
} else {
    $OrderType .= " By Ascending";
}
$sql = "SELECT * FROM visitors_info as vi LEFT JOIN page as p ON vi.page_id = p.id WHERE vi.geo_info_status = 1" . $searhCond . " ORDER BY " . $orderField . " " . $orderby;

try {
    $stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $stmt->execute();
} catch (PDOException $e) {
    print $e->getMessage();
}


$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Visitor Tracker');

$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Country : ' . $CountryName);
//$objPHPExcel->getActiveSheet()->setCellValue('B1',$CountryName);
$objPHPExcel->getActiveSheet()->setCellValue('A2', 'City : ' . $City);
//$objPHPExcel->getActiveSheet()->setCellValue('B2',$City);
$objPHPExcel->getActiveSheet()->setCellValue('A3', 'Ip Address : ' . $IpAddress);
//$objPHPExcel->getActiveSheet()->setCellValue('B3',$IpAddress);
$objPHPExcel->getActiveSheet()->setCellValue('A4', 'Page : ' . $Page);
//$objPHPExcel->getActiveSheet()->setCellValue('B4',$Page);
$objPHPExcel->getActiveSheet()->setCellValue('A5', 'Order Type : ' . $OrderType);

$objPHPExcel->getActiveSheet()->setCellValue('A7', 'Date');
$objPHPExcel->getActiveSheet()->setCellValue('B7', 'Time');
$objPHPExcel->getActiveSheet()->setCellValue('C7', 'Country');
$objPHPExcel->getActiveSheet()->setCellValue('D7', 'City');
$objPHPExcel->getActiveSheet()->setCellValue('E7', 'Ip Address');
$objPHPExcel->getActiveSheet()->setCellValue('F7', 'Visited Page');
$objPHPExcel->getActiveSheet()->setCellValue('G7', 'Page Referer');

$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A7')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B7')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C7')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('D7')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('E7')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('F7')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('G7')->getFont()->setBold(true);

$i = 8;
if ($stmt->rowCount() > 0) {
    $analyticsData = $stmt->fetchALL(PDO::FETCH_ASSOC);

    foreach ($analyticsData as $aData) {
        $aData[0] = date("d-m-Y", strtotime($aData['datetime']));
        $aData[1] = date("H:i:s", strtotime($aData['datetime']));
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $aData[0]);
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $aData[1]);
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $aData['country']);
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $aData['city']);
        $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $aData['ip_address']);
        $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $aData['page_name']);
        $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $aData['page_referer']);
        $i = $i + 1;
    }
}

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Vistortracking-' . date("Y-m-d-H-i-s") . '.xls"');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
