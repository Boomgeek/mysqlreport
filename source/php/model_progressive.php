<?php 
//start check role of user
require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/config.php');
require_once(dirname(dirname(dirname(__FILE__))).'/lib.php');
$is_student = user_has_role_assignment($USER->id,5)==0? "0" : "1";
$is_teacher = user_has_role_assignment($USER->id,3)==0? "0" : "1";
$is_admin = is_siteadmin()==0? "0" : "1";

//end check role of user

$mode = $_REQUEST["mode"];
if(empty($mode)){
	echo "Error: mode was empty";
	exit(0);
}

if($mode == "progressive" && ($is_admin == 1 || $is_teacher == 1))
{
	progressiveTeacher();
}

if($mode == "progressive" && $is_student == 1)
{
	$sid = $USER->username;				//this function from moodle class
	progressiveStudent($sid);
}

function progressiveTeacher()
{
	include("./connection.php");
	$con = connection();
	$id = "select DISTINCT userid from mdl_role_assignments where roleid in (3,5)";			//5 is student. 3 is teacher 
	$userInfo = "select username,CONCAT(firstname,' ',lastname) As fullname from mdl_user where id in (".$id.")";
	$unitInfo = "select uid,unit,(max_in_experiments+max_post_experiments) as max_point from mdl_mysql_unit ORDER BY unit ASC";
	

	echo "<div class='table-responsive'><table class='table'><thead><tr>";
	echo "<th>SID</th>";
	echo "<th>Name</th>";

	if($resultUnit = mysqli_query($con,$unitInfo))
	{
		while($unit = mysqli_fetch_array($resultUnit,MYSQLI_NUM)){
			echo "<th>Unit ".$unit[1]."</th>";
		}
	}
	else
	{
		printf("Unit1 Error: %s", mysqli_error($con));
		exit();
	}

	echo "</tr></thead><tbody>";

	if($resultUser = mysqli_query($con,$userInfo))
	{

		while($user = mysqli_fetch_array($resultUser,MYSQLI_NUM)){
			echo "<tr>";
			echo "<td>".$user[0]."</td>";
			echo "<td>".$user[1]."</td>";

			if($resultUnit = mysqli_query($con,$unitInfo))
			{
				while($unit = mysqli_fetch_array($resultUnit,MYSQLI_NUM)){
					$pid = "select pid from mdl_mysql_practice where uid = (".$unit[0].")";
					$sumPoint = "select count(sid) as point from mdl_mysql_answer where pid in (".$pid.") AND sid='".$user[0]."'";
					if($resultPoint = mysqli_query($con,$sumPoint)){
						while($point = mysqli_fetch_array($resultPoint,MYSQLI_NUM)){
							if($point[0]==$unit[2]){
								$progressColor = "progress-bar-success";
							}else{
								$progressColor = "progress-bar-warning";
							}
							if($point[0]==0){
								$color = "color: Black;";
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
function progressiveStudent($sid)
{
	include("./connection.php");
	$con = connection();
	$unitInfo = "select uid,unit,(max_in_experiments+max_post_experiments) as max_point from mdl_mysql_unit ORDER BY unit ASC";

	echo "<div class='table-responsive'><table class='table'><thead><tr>";
	echo "<th>Unit</th>";
	echo "<th>Progressive of practice</th>";
	echo "</tr></thead><tbody>";

	if($resultUnit = mysqli_query($con,$unitInfo))
	{
		while($unit = mysqli_fetch_array($resultUnit,MYSQLI_NUM)){
			echo "<tr>";
			echo "<td>".$unit[1]."</td>";
			$pid = "select pid from mdl_mysql_practice where uid = (".$unit[0].")";
			$sumPoint = "select count(sid) as point from mdl_mysql_answer where pid in (".$pid.") AND sid='".$sid."'";
			if($resultPoint = mysqli_query($con,$sumPoint)){
				while($point = mysqli_fetch_array($resultPoint,MYSQLI_NUM)){
					if($point[0]==$unit[2]){
						$progressColor = "progress-bar-success";
					}else{
						$progressColor = "progress-bar-warning";
					}
					if($point[0]==0){
						$color = "color: Black;";
					}else{
						$color = "color: White;";
					}
					echo "<td><div class='progress'>";
					echo "<div class='progress-bar ".$progressColor."' role='progressbar' aria-valuenow='".(($point[0]/$unit[2])*100)."' aria-valuemin='0' aria-valuemax='100' style='width: ".(($point[0]/$unit[2])*100)."%; ".$color."'>".$point[0]."/".$unit[2]."</div>";
					echo "</div></td>";
					echo "</tr>";
				}
			}
			else
			{
				printf("Point Error: %s", mysqli_error($con));
				exit();
			}
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