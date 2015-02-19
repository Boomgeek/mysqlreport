<?php 

$unit = $_REQUEST["unit"];
if(empty($unit)){
	echo "Error: unit was empty";
	exit(0);
}
$uname = $_REQUEST["uname"];
if(empty($unit)){
	echo "Error: unit was empty";
	exit(0);
}
$max_practice_while = $_REQUEST["max_practice_while"];
if(empty($unit)){
	echo "Error: unit was empty";
	exit(0);
}
$max_practice_after = $_REQUEST["max_practice_after"];
if(empty($unit)){
	echo "Error: unit was empty";
	exit(0);
}
echo $unit.$uname.$max_practice_while.$max_practice_after;
?>