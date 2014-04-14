<?php
//Escape output based on customised version of php based htmlspecialchars functions (', ", <, >, &')
function html_escaped_output($output_value){
	$escaped_output = htmlspecialchars($output_value, ENT_QUOTES, "UTF-8");
	return $escaped_output;}
    
?>