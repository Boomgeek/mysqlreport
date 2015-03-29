<?php 
$mode = $_REQUEST["mode"];
if(empty($mode)){
	echo "Error: mode was empty";
	exit(0);
}

if($mode == "callUnitDropdown")
{
	callUnitDropdown();
}

if($mode == "callTypeDropdown")
{
	$unit = $_REQUEST['unit'];
	if(empty($unit)){
		echo "Error: unit was empty";
		exit(0);
	}

	callTypeDropdown($unit);
}

if($mode == "callExperimentLogs")
{
	$unit = $_REQUEST['unit'];
	if(empty($unit)){
		echo "Error: unit was empty";
		exit(0);
	}

	$type = $_REQUEST['type'];
	if(empty($type)){
		echo "Error: type was empty";
		exit(0);
	}
	if($type == 'In Experiments'){
		$type = 1;
	}else if($type == 'Post Experiments'){
		$type = 2;
	}else{
		echo "Error: type not found";
		exit(0);
	}

	callExperimentLogs($unit,$type);
}

function callUnitDropdown()
{
	include("./connection.php");
	$con = connection();

	$select = "select distinct unit from mdl_mysql_unit ORDER BY unit ASC";

	if($result = mysqli_query($con,$select))
	{
		while($data = mysqli_fetch_array($result,MYSQLI_NUM))
		{
			echo "<option>".$data[0]."</option>";
		}
		
	}
	else
	{
		printf("Error: %s", mysqli_error($con));
		exit();
	}
}

function callTypeDropdown($unit)
{
	include("./connection.php");
	$con = connection();

	$uid = "select uid from mdl_mysql_unit where unit=".$unit;
	$select = "select distinct type from mdl_mysql_practice where uid=(".$uid.") ORDER BY type ASC";

	if($result = mysqli_query($con,$select))
	{
		while($data = mysqli_fetch_array($result,MYSQLI_NUM))
		{
			if($data[0] == 1){
				echo "<option>In Experiments</option>";
			}else if($data[0] == 2){
				echo "<option>Post Experiments</option>";
			}
		}
		
	}
	else
	{
		printf("Error: %s", mysqli_error($con));
		exit();
	}
}

function callExperimentLogs($unit,$type)
{
	include("./connection.php");
	$con = connection();

	$uid = "select uid from mdl_mysql_unit where unit=".$unit;
	$pidInfo = "select pid from mdl_mysql_practice where uid=(".$uid.") AND type=(".$type.") ORDER BY article ASC";

	if($resultPid = mysqli_query($con,$pidInfo))
	{
		$numrow = mysqli_num_rows($resultPid);
		$i = 1;
		while($pid = mysqli_fetch_array($resultPid,MYSQLI_NUM))
		{
			$countInfo = "select count(pid) from mdl_mysql_log where pid=(".$pid[0].")";
			if($resultCount = mysqli_query($con,$countInfo))
			{
				while($count = mysqli_fetch_array($resultCount,MYSQLI_NUM)){
					if($i == $numrow){
						echo $count[0];
					}else{
						echo $count[0].",";
					}
				}
			}
			else
			{
				printf("Error: %s", mysqli_error($con));
				exit();
			}
			$i++;
		}
	}
	else
	{
		printf("Error: %s", mysqli_error($con));
		exit();
	}
}

?>