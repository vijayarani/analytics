<?php
include "header.php";
$page_name_input = $_REQUEST['page_id'];
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
echo "<thead><tr><th>Date&Time</th><th>IP</th><th>Country</th><th>City</th><th>Referer</th></tr></thead><tbody>";
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

