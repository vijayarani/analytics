<?php

$q = intval($_GET['q']);
$sqlC = "SELECT distinct(country) FROM visitors_info as vi WHERE page_id = ".$q;
							try {
								$stmtC = $dbcon->prepare($sqlC, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
								$stmtC->execute();	
							}catch (PDOException $e){
								print $e->getMessage();
							}

							if($stmtC->rowCount() >0){
								$analyticsDataC = $stmtC->fetchALL(PDO::FETCH_ASSOC);
								foreach($analyticsDataC as $aData){
								
									$selectedC = ($aData["country"] == @$_POST['country_name']) ? "selected" : "";
									
									echo "<option ".$selectedC." value='".$aData["country"]."'>".$aData["country"]."</option>";										
								}													
							}							
						   
?> 