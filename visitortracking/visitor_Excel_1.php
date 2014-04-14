<?php

$date = "2014-03-25";

/*
echo "<pre>";
print_r($infoArray);
echo "</pre>";
exit;*/
/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
//date_default_timezone_set('Europe/London');

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

date_default_timezone_set('Europe/London');

/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2014 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    ##VERSION##, ##DATE##
 */

/** PHPExcel */
require_once 'excel/Classes/PHPExcel.php';
//require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
$dbcon = new PDO('mysql:host=localhost;port=3306;dbname=visitortracking', 'root', 'root', array(PDO::ATTR_PERSISTENT => false));

//$dbcon = new PDO('mysql:host=localhost;port=3306;dbname=sparkle_with_logs', 'root', 'root', array( PDO::ATTR_PERSISTENT => false));
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
}catch (PDOException $e){
	print $e->getMessage();
}

if($stmt->rowCount() > 0){
	$analyticsData = $stmt->fetchALL(PDO::FETCH_ASSOC);
	$infoArray = array();
	foreach($analyticsData as $aData){
	 $aData[0] = date("d-m-Y", strtotime($aData['datetime']));
        $aData[1] = date("H:i:s", strtotime($aData['datetime']));
		$info_1 = array();
		$info_1[] = $aData[0];
		$info_1[] = $aData[1];
		$info_1[] = $aData['country'];
		$info_1[] = $aData['city'];
		$info_1[] = $aData['ip_address'];
		$info_1[] = $aData['page_name'];
		$info_1[] = $aData['page_referer'];
		array_push($infoArray,$info_1);
	}
}
$count = count($infoArray);
$a = array('',	$searhCond);
array_unshift($infoArray, $a);

$objPHPExcel = new PHPExcel();

$objWorksheet = $objPHPExcel->getActiveSheet();
/*$objWorksheet->fromArray(
	array(
		array('',	2010,	2011,	2012),
		array('Jan',   47,   45,		71),
		array('Feb',   56,   73,		86),
		array('Mar',   52,   61,		69),
		array('Apr',   40,   52,		60),
		array('May',   42,   55,		71),
		array('Jun',   58,   63,		76),
		array('Jul',   53,   61,		89),
		array('Aug',   46,   69,		85),
		array('Sep',   62,   75,		81),
		array('Oct',   51,   70,		96),
		array('Nov',   55,   66,		89),
		array('Dec',   68,   62,		0),
	)
); */

$objWorksheet->fromArray(
	$infoArray
);
//	Set the Labels for each data series we want to plot
//		Datatype
//		Cell reference for data
//		Format Code
//		Number of datapoints in series
//		Data values
//		Data Marker
/*
$dataseriesLabels = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$C$1', NULL, 1),	//	2011
	new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$D$1', NULL, 1),	//	2012
);
*/
$dataseriesLabels = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$B$1', NULL, 1),	//	$date
	//new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$D$1', NULL, 1),	//	2012
);
//	Set the X-Axis Labels
//		Datatype
//		Cell reference for data
//		Format Code
//		Number of datapoints in series
//		Data values
//		Data Marker
$xAxisTickValues = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$A$2:$A$'.($count+1), NULL, $count),	//	canada to india33 //Jan to Dec
	//new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$A$2:$A$13', NULL, 12),	//	Jan to Dec
);
//	Set the Data values for each data series we want to plot
//		Datatype
//		Cell reference for data
//		Format Code
//		Number of datapoints in series
//		Data values
//		Data Marker
$dataSeriesValues = array(
	new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$B$2:$B$'.($count+1), NULL, $count),
	//new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$D$2:$D$13', NULL, 12),
);

//	Build the dataseries
$series = new PHPExcel_Chart_DataSeries(
	PHPExcel_Chart_DataSeries::TYPE_RADARCHART,				// plotType
	NULL,													// plotGrouping
	range(0, count($dataSeriesValues)-1),					// plotOrder
	$dataseriesLabels,										// plotLabel
	$xAxisTickValues,										// plotCategory
	$dataSeriesValues,										// plotValues
	NULL,													// smooth line
	PHPExcel_Chart_DataSeries::STYLE_MARKER					// plotStyle
);

//	Set up a layout object for the Pie chart
$layout = new PHPExcel_Chart_Layout();

//	Set the series in the plot area
$plotarea = new PHPExcel_Chart_PlotArea($layout, array($series));
//	Set the chart legend
$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);

$title = new PHPExcel_Chart_Title('Test Radar Chart');


//	Create the chart
$chart = new PHPExcel_Chart(
	'chart1',		// name
	$title,			// title
	$legend,		// legend
	$plotarea,		// plotArea
	true,			// plotVisibleOnly
	0,				// displayBlanksAs
	NULL,			// xAxisLabel
	NULL			// yAxisLabel		- Radar charts don't have a Y-Axis
);

//	Set the position where the chart should appear in the worksheet
$chart->setTopLeftPosition('F2');
$chart->setBottomRightPosition('M15');

//	Add the chart to the worksheet
$objWorksheet->addChart($chart);


// Save Excel 2007 file
echo date('H:i:s') , " Write to Excel2007 format" , EOL;
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->setIncludeCharts(TRUE);
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
echo date('H:i:s') , " File written to " , str_replace('.php', '.xlsx', pathinfo(__FILE__, PATHINFO_BASENAME)) , EOL;


// Echo memory peak usage
echo date('H:i:s') , " Peak memory usage: " , (memory_get_peak_usage(true) / 1024 / 1024) , " MB" , EOL;

// Echo done
echo date('H:i:s') , " Done writing file" , EOL;
echo 'File has been created in ' , getcwd() , EOL;
