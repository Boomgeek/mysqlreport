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
	$id = "select DISTINCT userid from mdl_role_assignments where roleid in (3,5)";			//5 is student. 3 is teacher 
	$userInfo = "select username as sid,CONCAT(firstname,' ',lastname) As fullname from mdl_user where id in (".$id.")";
	$totalPoint = "select sid,cast(sum(point) as decimal(10,1)) as total_point from mdl_mysql_spoint group by sid";
	$sumMaxUnitPoint = "select sum(max_unit_point) from mdl_mysql_unit";
	$studentPoint = "select t1.sid,fullname,total_point,(".$sumMaxUnitPoint.") as max_point from (".$userInfo.") as t1 inner join (".$totalPoint.") as t2 where t1.sid = t2.sid ORDER BY total_point DESC";
	$unitInfo = "select uid,unit,max_unit_point as max_point from mdl_mysql_unit ORDER BY unit ASC";

	echo "<div class='table-responsive'><table class='table'><thead><tr>";
	echo "<th>#</th>";
	echo "<th>Type</th>";
	echo "<th>Article</th>";
	echo "<th>Question</th>";
	echo "<th>Difficulty Index</th>";

	if($resultUnit = mysqli_query($con,$unitInfo))
	{
		while($unit = mysqli_fetch_array($resultUnit,MYSQLI_NUM)){
			echo "<th>Unit ".$unit[1]." Score</th>";
		}
	}
	else
	{
		printf("Unit1 Error: %s", mysqli_error($con));
		exit();
	}

	echo "<th>Total Score</th>";
	echo "</tr></thead><tbody>";

	if($resultUser = mysqli_query($con,$studentPoint))
	{
		$i = 1;
		while($user = mysqli_fetch_array($resultUser,MYSQLI_NUM)){
			echo "<tr>";
			echo "<td>".$i++."</td>";
			echo "<td>".$user[0]."</td>";
			echo "<td>".$user[1]."</td>";

			if($resultUnit = mysqli_query($con,$unitInfo))
			{
				while($unit = mysqli_fetch_array($resultUnit,MYSQLI_NUM)){
					$pid = "select pid from mdl_mysql_practice where uid = (".$unit[0].")";
					$sumPoint = "select sum(point) as point from mdl_mysql_answer where pid in (".$pid.") AND sid='".$user[0]."'";
					if($resultPoint = mysqli_query($con,$sumPoint)){
						while($point = mysqli_fetch_array($resultPoint,MYSQLI_NUM)){
							if($point[0]==$unit[2]){
								$progressColor = "progress-bar-success";
							}else{
								$progressColor = "progress-bar-warning";
							}
							if($point[0]==0 || $point[0] == NULL){
								$color = "color: Black;";
								$point[0] = 0;
							}else{
								$color = "color: White;";
							}
							echo "<td><div class='progress'>";
							echo "<div class='progress-bar ".$progressColor."' role='progressbar' aria-valuenow='".(($point[0]/$unit[2])*100)."' aria-valuemin='0' aria-valuemax='100' style='width: ".(($point[0]/$unit[2])*100)."%; ".$color."'>".$point[0]."/".$unit[2]."</div>";
							echo "</div></td>";
						}
					}
					else
					{
						printf("Point Error: %s", mysqli_error($con));
						exit();
					}
				}
			}
			else
			{
				printf("Unit2 Error: %s", mysqli_error($con));
				exit();
			}
			if($user[2]<($user[3]/4)){
				$color = "color: Black;";
			}else{
				$color = "color: White;";
			}
			echo "<td><div class='progress'>";
			echo "<div class='progress-bar' role='progressbar' aria-valuenow='".(($user[2]/$user[3])*100)."' aria-valuemin='0' aria-valuemax='100' style='width: ".(($user[2]/$user[3])*100)."%; ".$color."'>".$user[2]."/".$user[3]."</div>";
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