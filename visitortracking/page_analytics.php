<?php
include "header.php";
$page_name_input = $_REQUEST['page_id'];$sql = "SELECT vi.*,p.page_name FROM visitors_info as vi LEFT JOIN page as p ON p.id=vi.page_id WHERE vi.page_id = '".$page_name_input."' AND vi.geo_info_status = 1";try {	$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	$stmt->execute();		$no_of_rows = $stmt->rowCount();}catch (PDOException $e){	print $e->getMessage();}$page_name = getPageName($page_name_input);function getPageName($pid){	global $dbcon;	$sql = "SELECT page_name FROM page WHERE id = '".$pid."'";	try {		$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));		$stmt->execute();			$pageDaata = $stmt->fetch(PDO::FETCH_ASSOC);		return $pageDaata['page_name'];	}catch (PDOException $e){		print $e->getMessage();	}}?><style>#example1_length,#example1_filter,#example1_processing{	margin-bottom:20px;}#example1_paginate{	margin-top:10px;}</style>
<script>
$(document).ready(function() {
    $('#example1').dataTable( {
	     "bStateSave": true,
         "sPaginationType": "full_numbers"
        //"bProcessing": true,
        //"bServerSide": true,
        //"sAjaxSource": "server_processing.php"
    } );
} );
</script>
<div id="container">
	<div class="shell">
		
		<!-- Small Nav -->
		<div class="small-nav">
			<a href="#">Home</a>
			<span>&gt;</span> Page Wise Reports
			<span>&gt;</span>
              Individual Page Reports
		</div>
		<!-- End Small Nav -->
               <br />
		<!-- Main -->
		<div id="main">
			<div class="cl">&nbsp;</div> 
			<?php

echo "<h2 style='font-size:24px;'>Detailed Reports For <span style='color:red;'>".$page_name."</span></h2><br>";
echo "<span style='font-size:14px;'>(Total Visit Count for the page = <span style='color:red;'> $no_of_rows </span>) </span>";
echo "<br><br>";
echo "<table  id='example1' class='display'>";
echo "<thead><tr><th>Date&Time</th><th>IP</th><th>Country</th><th>City</th><th>Referer</th></tr></thead><tbody>";if($stmt->rowCount() > 0){	$analyticsData = $stmt->fetchALL(PDO::FETCH_ASSOC);	foreach($analyticsData as $aData){			echo "<tr><td>".$aData['datetime']."</td>";		echo "<td>".$aData['ip_address']."</td>";		echo "<td>".$aData['country']."</td>";		echo "<td>".$aData['city']."</td>";				if($aData['page_referer'] == ""){			echo "<td>No Referrer/Direct Site Visitor</td>";		}elseif($aData['page_referer'] != ""){			echo "<td>".html_escaped_output($aData['page_referer'])."</td>";		}   		echo "</tr>";	}}else{	echo "<tr><td colspan='6'>No analytics found</td></tr>";} 
echo "</tbody></table>";
?>
<div class="cl">&nbsp;</div>			
		</div>
		<!-- Main -->
	</div>
</div>
<!-- End Container -->			

<?php

include "footer.php";
?>


