<?php
include "header.php";
?>
<script>
$(document).ready(function() {
    $('#example').dataTable( {
	    "bStateSave": true,		
        "sPaginationType": "full_numbers",
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "server_processing.php"
    } );
} );
</script><style>
#example_length,#example_filter,#example_processing{
	margin-bottom:20px;
}
#example_paginate{
	margin-top:10px;
}
</style>

<div id="container">
	<div class="shell">
		<!-- Small Nav -->
		<div class="small-nav">
			<a href="#">Home</a>
			<span>&gt;</span>
              All Visitors
		</div>
		<!-- End Small Nav -->
               <br />
		<!-- Main -->
		<div id="main">
			<div class="cl">&nbsp;</div> 
<?php
echo "<span style='font-size:35px;margin-left:250px;'>User Tracking For Infofam</span>";
echo "<br><br><br><br><br>";
echo "<table id='example' class='display'>";
echo "<thead><tr><th>Date</th><th>Time</th><th>Country</th><th>City</th><th>Ip Address</th><th>Visited Page</th><th>ReferringPage</th></tr></thead><tbody>";
echo "<tr><td colspan='6' class='dataTables_empty'>Processing</td></tr>";
echo "</tbody></table>";

echo "<br><br>";
?>
<br><br>
<!-- <a href="reports_csv_download.php" class="add-button"><span>Download CSV File</span></a> -->
		<!--	<a href="reports_html_download.php" class="add-button"><span>Download Html File</span></a> -->
<div class="cl">&nbsp;</div>			
		</div>
		<!-- Main -->
	</div>
</div>
<!-- End Container -->			

<?php
include "footer.php";
?>

