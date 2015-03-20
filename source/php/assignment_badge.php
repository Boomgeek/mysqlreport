<?php 
$mode = $_REQUEST['mode'];
if(empty($mode)){
	echo "Error: mode was empty";
	exit(0);
}

if($mode == "updateBadge"){
	updateBadge();
}

function updateBadge(){
	include("./connection.php");
	$con = connection();

	$select = "select count(aid) from mdl_mysql_answer where status=0";
	if($result = mysqli_query($con,$select))
	{
		while($data = mysqli_fetch_array($result,MYSQLI_NUM))
		{
			if($data[0] == 0){
				echo null;				//no echo
			}else{
				echo $data[0];
			}
		}
	}
	else
	{
		printf("Error: %s", mysqli_error($con));
		exit();
	}
}

?>