<?php  
$mode = $_REQUEST["mode"];
if(empty($mode)){
	echo "Error: mode was empty";
	exit(0);
}

if($mode == "difficulty")
{
	difficulty();
}

function difficulty()
{
	echo "Error:test difficulty function 55555!!!";
}

?>