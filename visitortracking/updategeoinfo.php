<?php

include("../includes/dbconfig.php");	

$variable_to_echo = "";
$s_query = "SELECT ip_address FROM visitors_info WHERE geo_info_status = 0 GROUP BY ip_address";
$select_query = $dbcon->prepare($s_query);
$select_query->execute();
if($select_query->rowCount() > 0){

	$select_query_result = $select_query->fetchAll();
	foreach($select_query_result as $select_query_row){    
		$present_ip = $select_query_row['ip_address'];		
		$variable_to_echo .= "processed ip = ".$present_ip."\n";
		$geoinfo = "http://api.ipinfodb.com/v3/ip-city/?key=13ebc6d8740ab89e93e615530a59dd0f22df559274089129135f83188578f84d&ip=$present_ip&format=json";

		$ch_geoinfo = curl_init($geoinfo); 	
		curl_setopt($ch_geoinfo, CURLOPT_HEADER, 0);         	
		curl_setopt($ch_geoinfo, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch_geoinfo, CURLOPT_MAXREDIRS, 10);
		curl_setopt($ch_geoinfo, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch_geoinfo, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch_geoinfo,CURLOPT_CONNECTTIMEOUT,60);
		curl_setopt($ch_geoinfo, CURLOPT_FAILONERROR, 1);

		$execute_geoinfo = curl_exec($ch_geoinfo);
		
		if(!curl_errno($ch_geoinfo)){					
			$json_geoinfo = str_replace('\\', '\\\\', $execute_geoinfo);
			$json_decode_geoinfo = json_decode($json_geoinfo, true);   
			
			$country_name = $json_decode_geoinfo["countryName"];
			$city_name = $json_decode_geoinfo["cityName"];			
			
			$variable_to_echo .= "country :  ".$country_name." -------- city :  ".$city_name."\n";	

			$geo_info_status_var = 1;
			$update_q = "UPDATE `visitors_info` SET `country` = :country, `city` = :city, `geo_info_status` = 1 WHERE `geo_info_status` = 0 AND `ip_address` = :ip_address";
			$update_query = $dbcon->prepare($update_q);
			$update_query->bindParam(":country",$country_name);
			$update_query->bindParam(":city",$city_name);			
			$update_query->bindParam(":ip_address",$present_ip);
			try{ 
				$update_query->execute();
			}
			catch(PDOException $e){
				return $e->getMessage();
			}
		}
		sleep(3);		
	}
}
header('Location: index.php');
?>