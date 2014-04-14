<?php
include "header.php";
$country_name_input = $_GET['country'];$sql = "SELECT vi.*,p.page_name FROM visitors_info as vi LEFT JOIN page as p ON p.id=vi.page_id WHERE vi.country = '".$country_name_input."' AND vi.geo_info_status = 1";try {	$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	$stmt->execute();		$no_of_rows = $stmt->rowCount();}catch (PDOException $e){	print $e->getMessage();}?><style>#example4_length,#example4_filter,#example4_processing{	margin-bottom:20px;}#example4_paginate{	margin-top:10px;}</style>
<script>
$(document).ready(function() {
    $('#example4').dataTable( {
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
			<span>&gt;</span> Country Wise Reports
			<span>&gt;</span>
              Individual Country Reports
		</div>
		<!-- End Small Nav -->
               <br />
		<!-- Main -->
		<div id="main">
			<div class="cl">&nbsp;</div> 
			<?php
  

echo "<h2 style='font-size:24px;'>Detailed Reports For Country <span style='color:red;'> $country_name_input</span></h2><br>";
echo "<span style='font-size:14px;'>( Total Visit Count for the Country = <span style='color:red;'> $no_of_rows </span>)</span><br><br>";
echo "<table  id = 'example4' class='display'>";
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

