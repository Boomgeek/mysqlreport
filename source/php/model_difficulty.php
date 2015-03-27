<?php  
$mode = $_REQUEST["mode"];
if(empty($mode)){
	echo "Error: mode was empty";
	exit(0);
}

if($mode == "updateDifficulty")
{
	updateDifficulty();
}

if($mode == "callDifficulty")
{
	callDifficulty();
}

function updateDifficulty()
{
	include("./connection.php");
	$con = connection();

	$pidInfo = "select pid from mdl_mysql_practice";
	if($resultPid = mysqli_query($con,$pidInfo))
	{
		while($pid = mysqli_fetch_array($resultPid,MYSQLI_NUM)){
			$difficulty = "select (select count(point) from mdl_mysql_answer where pid = ".$pid[0]." AND point >0) / (select count(point) from mdl_mysql_answer where pid = ".$pid[0].")";
			$difficulty = "select cast((".$difficulty.") as decimal(10,2))";	// to 2 decimal digit
			$updateDifficulty = "update mdl_mysql_practice set difficulty_index=(".$difficulty .") where pid=".$pid[0];
			mysqli_query($con,$updateDifficulty);
		}
	}
	else
	{
		printf("Unit1 Error: %s", mysqli_error($con));
		exit();
	}
}

function callDifficulty()
{
	echo "Error:test difficulty function 55555!!!";
}
?>