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

if($mode == "updateDifficulty")
{
	updateDifficulty();
}

if($mode == "callDifficulty")
{
	$unit = $_REQUEST["unit"];
	if(empty($unit)){
		echo "Error: unit was empty";
		exit(0);
	}

	callDifficulty($unit);
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

function callDifficulty($unit)
{
	include("./connection.php");
	$con = connection();
	
	$uid = "select uid from mdl_mysql_unit where unit=".$unit;
	$practiceInfo = "select uid,type,article,question,difficulty_index from mdl_mysql_practice where uid=(".$uid.") ORDER BY type ASC ,article ASC";

	echo "<div class='table-responsive'><table class='table'><thead><tr>";
	echo "<th>#</th>";
	echo "<th>Type</th>";
	echo "<th>Article</th>";
	echo "<th>Question</th>";
	echo "<th>Difficulty Index</th>";

	echo "</tr></thead><tbody>";

	if($resultPractice = mysqli_query($con,$practiceInfo))
	{
		$i = 1;
		while($practice = mysqli_fetch_array($resultPractice,MYSQLI_NUM)){
			if($practice[1] == 1){
				$practice[1] = "In Experiments";
			}else if($practice[1] == 2){
				$practice[1] = "Post Experiments";
			}
			echo "<tr>";
			echo "<td>".$i++."</td>";
			echo "<td>".$practice[1]."</td>";
			echo "<td>".$practice[2]."</td>";
			echo "<td>".$practice[3]."</td>";
			//$practice[4] = 0.9;
			if($practice[4] > 0.8){
				$progressColor = "progress-bar-primary";
			}else if($practice[4] > 0.6){
				$progressColor = "progress-bar-info";
			}else if($practice[4] > 0.5){
				$progressColor = "progress-bar-info";
			}else if($practice[4] == 0.5){
				$progressColor = "progress-bar-success";
			}else if($practice[4] > 0.4){
				$progressColor = "progress-bar-warning";
			}else if($practice[4] > 0.2){
				$progressColor = "progress-bar-warning";
			}else if($practice[4] >= 0){
				$progressColor = "progress-bar-danger";
			}

			if($practice[4]==0){
				$color = "color: Red;";
			}else{
				$color = "color: White;";
			}

			echo "<td><div class='progress'>";
			echo "<div class='progress-bar ".$progressColor."' role='progressbar' aria-valuenow='".(($practice[4]/1)*100)."' aria-valuemin='0' aria-valuemax='100' style='width: ".(($practice[4]/1)*100)."%; ".$color."'>".$practice[4]."/1</div>";
			echo "</div></td>";
			echo "</tr>";
		}
		echo "</tbody></table></div>";
	}
	else
	{
		printf("User Error: %s", mysqli_error($con));
		exit();
	}
}

?>