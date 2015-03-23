<?php 
$mode = $_REQUEST["mode"];
if(empty($mode)){
	echo "Error: mode was empty";
	exit(0);
}

if($mode == "progressiveTeacher")
{
	progressiveTeacher();
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
?>