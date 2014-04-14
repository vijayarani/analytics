<?php

include 'header.inc.php';
$html= '';
$searhCond = "";
            if (@$_POST['country_name'] != "") {
                $searhCond .= " AND vi.country='" . $_POST['country_name'] . "'";
                $html .= '<b>Country : </b>'.$_POST['country_name'].'<br>';
            }else{
                $html .= '<b>Country :</b> ALL '.'<br>';
            }
            if (@$_POST['city_name'] != "") {
                $searhCond .= " AND vi.city='" . $_POST['city_name'] . "'";
                $html .= '<b>City :</b> '.$_POST['city_name'].'<br>';
            }else{
                $html .= '<b>City :</b>= ALL'.'<br>';
            }
            if (@$_POST['IpAddress'] != "") {
                $searhCond .= " AND vi.ip_address='" . $_POST['IpAddress'] . "'";
                $html .= '<b>IP Addrress :</b> '.$_POST['IpAddress'].'<br>';
            }else{
                $html .= '<b>IP Address :</b> ALL'.'<br>';
            }

            if (@$_POST['page_id'] != "") {
                $searhCond .= " AND vi.page_id='" . $_POST['page_id'] . "'";
                
            try {
                
                $stmt = $dbcon->prepare("SELECT page_name FROM `page` WHERE id=".$_POST['page_id'], array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                $analyticsData = $stmt->fetchALL(PDO::FETCH_ASSOC);
                //$temp = print_r($analyticsData,true);
                foreach ($analyticsData as $aData) {
                    $html .= '<b>Page :</b> '.$aData['page_name'].'<br>';
                }
                }
                
            } catch (PDOException $e) {
                print $e->getMessage();
            }
            }else{
                $html .= '<b>Page :</b> ALL'.'<br>';
            }
            if (@$_POST['fromdate'] != "") {
                $searhCond .= " AND date(vi.datetime)='" . $_POST['fromdate'] . "'";
                //$aData[0] = date("d-m-Y", strtotime($aData['datetime']));
                $html .= '<b>Date :</b> '.$_POST['fromdate'].'<br>';
            }else{
                $html .= '<b>Date :</b> ALL'.'<br>';
            }

            $orderField = (@$_POST['order_type'] != "") ? $_POST['order_type'] : "vi.datetime";
            //$html .= $orderField.'****';
            $orderby = (@$_POST['order_by'] != "") ? $_POST['order_by'] : "DESC";
           // $html .= $orderby.'****';

            switch($orderField){
             case 'vi.ip_address':
                 $html .= '<b>Order By :</b> IP Address ';
                 break;
             case 'vi.datetime': 
                 $html .= '<b>Order By :</b> Date ';
                  break;
             case 'vi.country':
                 $html .= '<b>Order By :</b> Country ';
                  break;
             case 'vi.city': 
                 $html .= '<b>Order By :</b> City ';
                  break;
             case 'vi.page_name': 
                 $html .= '<b>Order By :</b> Page ';
                  break;
                         
            }
            switch ($orderby){
                case 'DESC' :
                    $html .= ' By Descending <br><br><br>';
                    break;
                case 'ASC' :
                    $html .= ' By Ascending <br><br><br>';
                    break;
            }
            
            $sql = "SELECT * FROM visitors_info as vi LEFT JOIN page as p ON vi.page_id = p.id WHERE vi.geo_info_status = 1" . $searhCond . " ORDER BY " . $orderField . " " . $orderby;
             
            try {
                $stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                $stmt->execute();
            } catch (PDOException $e) {
                print $e->getMessage();
            }
            
error_reporting(1);
// Include the main TCPDF library (search for installation path).
require_once('lib/tcpdf/tcpdf.php'); //C:\xampp\htdocs\VisitorTracking\tcpdf\examples\tcpdf_include.php

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


// ---------------------------------------------------------


            
$pdf->AddPage();

$html .= '<table border="0" cellspacing="2" cellpadding="2">
	<b><tr>
            <th>Date&Time</th>
            <th>Country</th>
            <th>City</th>
            <th>IP Address</th>
            <th>Visited Page</th>
            <th>Reference Page</th>
	</tr></b>';

if ($stmt->rowCount() > 0) {

    $analyticsData = $stmt->fetchALL(PDO::FETCH_ASSOC);
    
    foreach ($analyticsData as $aData) {
        $aData[0] = date("d-m-Y", strtotime($aData['datetime']));
        $aData[1] = date("H:i:s", strtotime($aData['datetime']));

        $html .= '<tr>
		<td>' . $aData[0] . ' ' . $aData[1] . '</td>
                <td>' . $aData['country'] . '</td>
                <td>' . $aData['city'] . '</td>
                <td>' . $aData['ip_address'] . '</td>
                <td>' . $aData['page_name'] . '</td>
                <td>' . $aData['page_referer'] . '</td>
	</tr>';
    }
} else {
    //echo "<tr><td colspan='3'>No analytics found</td></tr>";
}
$html .= '</table>';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');



// ---------------------------------------------------------
//Close and output PDF document
$pdf->Output('Visitor Tracking-'.date("Y-m-d-H-i-s").'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
