<?php
include "header.php";$ip_address_input = $_REQUEST['ip_address'];$sql = "SELECT vi.*,p.page_name FROM visitors_info as vi LEFT JOIN page as p ON p.id=vi.page_id WHERE vi.ip_address = '".$ip_address_input."' AND vi.geo_info_status = 1";try {	$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	$stmt->execute();		$no_of_rows = $stmt->rowCount();}catch (PDOException $e){	print $e->getMessage();}
?><style>#example3_length,#example3_filter,#example3_processing{	margin-bottom:20px;}#example3_paginate{	margin-top:10px;}</style>
<script>
$(document).ready(function() {
    $('#example3').dataTable( {
	     "bStateSave": true,
         "sPaginationType": "full_numbers"
    } );
    
    $('#example7').dataTable( {
	     "bStateSave": true,
         "sPaginationType": "full_numbers"
    } );
} );
</script>
<div id="container">
	<div class="shell">
		
		<!-- Small Nav -->
		<div class="small-nav">
			<a href="#">Home</a>
			<span>&gt;</span> IP-Address wise Reports
           <span>&gt;</span>
              Individual Ip-Address Reports
		</div>
		<!-- End Small Nav -->
               <br />
		<!-- Main -->
		<div id="main">
			<div class="cl">&nbsp;</div> 
			<?php
  echo "<h2 style='font-size:24px;'>Detailed Reports For <span style='color:red;'>$ip_address_input</span></h2><br>";
echo "<span style='font-size:14px;'>(Total Visit Count for the IP-Address =  <span style='color:red;'>$no_of_rows </span>) </span><br><br>";
echo "<table  id='example3' class='display'>";
echo "<thead><tr><th>Date&Time</th><th>Country</th><th>City</th><th>IP Address</th><th>Visited Page</th><th>Referer</th></tr></thead><tbody>";if($stmt->rowCount() > 0){	$analyticsData = $stmt->fetchALL(PDO::FETCH_ASSOC);	foreach($analyticsData as $aData){		echo "<tr><td>".$aData['datetime']."</td>";		echo "<td>".$aData['country']."</td>";		echo "<td>".$aData['city']."</td>";		echo "<td>".$aData['ip_address']."</td>";		echo "<td>".$aData['page_name']."</td>";				if($aData['page_referer'] == ""){			echo "<td>No Referrer/Direct Site Visitor</td>";		}elseif($aData['page_referer'] != ""){			echo "<td>".html_escaped_output($aData['page_referer'])."</td>";		}   		echo "</tr>";	}}else{	echo "<tr><td colspan='6'>No analytics found</td></tr>";} 
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


