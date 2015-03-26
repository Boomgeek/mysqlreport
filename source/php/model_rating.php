<?php  
$mode = $_REQUEST["mode"];
if(empty($mode)){
	echo "Error: mode was empty";
	exit(0);
}

if($mode == "checkStudentPoint")
{
	checkStudentPoint();
}

if($mode == "rating")
{
	getRating();
}

function checkStudentPoint()
{
	include("./connection.php");
	$con = connection();

	$id = "select DISTINCT userid from mdl_role_assignments where roleid in (3,5)";			//5 is student. 3 is teacher 
	$userInfo = "select username from mdl_user where id in (".$id.")";
	if($resultUser = mysqli_query($con,$userInfo))
	{
		//loop for check all user . if user not have data in mdl_mysql_spoint table then use insertStudentPoint()
		while($sid = mysqli_fetch_array($resultUser,MYSQLI_NUM)){
			$spointInfo = "select DISTINCT sid from mdl_mysql_spoint where sid='".$sid[0]."'";
			$spointQuery = mysqli_query($con,$spointInfo);
			$spointRow = mysqli_num_rows($spointQuery);
			if($spointRow == 0){
				insertStudentPoint($sid[0],$con);
			}else{
				updateStudentPoint($sid[0],$con);
			}
		}
	}
	else
	{
		printf("Error: %s", mysqli_error($con));
		exit();
	}
}

function insertStudentPoint($sid,$con)
{
	$unitInfo = "select uid,unit from mdl_mysql_unit ORDER BY unit ASC";
	if($resultUnit = mysqli_query($con,$unitInfo))
	{
		while($uid = mysqli_fetch_array($resultUnit,MYSQLI_NUM)){
			$pid = "select pid from mdl_mysql_practice where uid=".$uid[0];
			$sumPoint = "select sum(point) from mdl_mysql_answer where pid in(".$pid.") AND sid='".$sid."'";

			if($resultSumPoint = mysqli_query($con,$sumPoint)){
				$point = mysqli_fetch_array($resultSumPoint,MYSQLI_NUM);
				if($point[0] == NULL){
					$point[0] = 0;
				}
				$insert = "insert into mdl_mysql_spoint (sid,uid,point) values('".$sid."',".$uid[0].",".$point[0].")";
				mysqli_query($con,$insert);
			}else{
				printf("Error: %s", mysqli_error($con));
				exit();
			}
		}
	}
	else
	{
		printf("Error: %s", mysqli_error($con));
		exit();
	}
}

function updateStudentPoint($sid,$con)
{
	$unitInfo = "select uid,unit from mdl_mysql_unit ORDER BY unit ASC";
	if($resultUnit = mysqli_query($con,$unitInfo))
	{
		while($uid = mysqli_fetch_array($resultUnit,MYSQLI_NUM)){
			$pid = "select pid from mdl_mysql_practice where uid=".$uid[0];
			$sumPoint = "select sum(point) from mdl_mysql_answer where pid in(".$pid.") AND sid='".$sid."'";
			if($resultSumPoint = mysqli_query($con,$sumPoint)){
				$point = mysqli_fetch_array($resultSumPoint,MYSQLI_NUM);
				$update = "update mdl_mysql_spoint set point=".$point[0]." where sid='".$sid."' AND uid=".$uid[0];
				mysqli_query($con,$update);
			}else{
				printf("Error: %s", mysqli_error($con));
				exit();
			}
		}
	}
	else
	{
		printf("Error: %s", mysqli_error($con));
		exit();
	}
}

function getRating()
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
	echo "<th>No.</th>";
	echo "<th>SID</th>";
	echo "<th>Name</th>";

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
			if($user[2]<($user[3]/3)){
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