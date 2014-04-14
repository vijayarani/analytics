<?php
include "header.php";
/*if(isset($_SESSION['login']))
{ 
  echo "yes isset";
}
else
{
   echo "no isset";
}
echo "session value = ".$_SESSION['login']; */

if(isset($_SESSION['login']) && ($_SESSION['login'] == "yesloggedin"))
{
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
</script>
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
  
  include "php/db_connection.php";
mysql_select_db('Ibvisitors',$link) or die ("could not open db".mysql_error());

$query = "SELECT *
FROM Ib_visitors_info
ORDER BY `Ib_visitors_info`.`id` DESC";
$result = mysql_query($query); 

$var_for_csv_format = "";
$var_for_csv_format .= "Date,Country,Time,Ip-Address,Visited-page,Referring-page"."\r\n";
echo "<span style='font-size:35px;margin-left:250px;'>User Tracking For IdeaBytes</span>";
echo "<br><br><br>";
echo "<table id='example' class='display'>";
echo "<thead><tr><th>Date</th><th>Time</th><th>Country</th><th>City</th><th>Ip Address</th><th style='width:8opx;'>Visited Page</th><th>ReferringPage</th></tr></thead><tbody>";
 
echo "<tr><td colspan='6' class='dataTables_empty'>Processing</td></tr>";
echo "</tbody></table>";
echo "<br><br>";
  //echo $var_for_csv_format;
$csv_file = 'reports_downloads/reports_csv.csv';
//$csv_file = 'reports_downloads/report1.html';
//file_put_contents($csv_file, $var_for_csv_format);
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
}//end of login session check if
else
{
   header('Location: index.php');
}
include "footer.php";
?>

