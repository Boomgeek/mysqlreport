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

if($mode == "callArticleDropdown")
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

	callArticleDropdown($unit,$type);
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

if($mode == "callExperimentDetails")
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

	$article = $_REQUEST['article'];
	if(empty($article)){
		echo "Error: article was empty";
		exit(0);
	}

	$sort = $_REQUEST['sort'];
	if(empty($sort)){
		echo "Error: sort was empty";
		exit(0);
	}

	callExperimentDetails($unit,$type,$article,$sort);
}

function callUnitDropdown()
{
	include("./connection.php");
	$con = connection();

	$select = "select distinct unit,uname from mdl_mysql_unit ORDER BY unit ASC";

	if($result = mysqli_query($con,$select))
	{
		while($data = mysqli_fetch_array($result,MYSQLI_NUM))
		{
			$sizeUname = strlen($data[1]);
			if($sizeUname > 15){
				$uname = substr($data[1], 0, 14)."...";
			}else{
				$uname = $data[1];
			}
			echo "<option value='".$data[0]."'>".$data[0]." ".$uname."</option>";
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

function callArticleDropdown($unit,$type)
{
	include("./connection.php");
	$con = connection();

	$uid = "select uid from mdl_mysql_unit where unit=".$unit;
	$select = "select distinct article from mdl_mysql_practice where uid=(".$uid .") AND type=".$type." ORDER BY article ASC";

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

function callExperimentDetails($unit,$type,$article,$sort)
{
	if($sort == 1){
		$sort = "t1.sid ASC";
	}else if($sort == 2){
		$sort = "times DESC, t1.sid ASC";
	}

	include("./connection.php");
	$con = connection();
	$id = "select DISTINCT userid from mdl_role_assignments where roleid in (3,5)";			//5 is student. 3 is teacher 
	$userInfo = "select username as sid,CONCAT(firstname,' ',lastname) As fullname from mdl_user where id in (".$id.")";
	$sid = "select username as sid from mdl_user where id in (".$id.")";
	$uid = "select uid from mdl_mysql_unit where unit=".$unit;
	$pidInfo = "select pid from mdl_mysql_practice where uid=(".$uid.") AND type=(".$type.") AND article=(".$article.") ORDER BY article ASC";
	$countInfo = "select sid,count(code) as times from mdl_mysql_log where pid=(".$pidInfo .") AND sid in(".$sid.") GROUP BY sid";
	$timesInfo = "select t1.sid,fullname,times from (".$userInfo.") as t1 INNER JOIN (".$countInfo.") as t2 on t1.sid=t2.sid ORDER BY ".$sort;

	echo "<div class='table-responsive'><table class='table'><thead><tr>";
	echo "<th>No.</th>";
	echo "<th>SID</th>";
	echo "<th>Name</th>";
	echo "<th>Times</th>";
	echo "</tr></thead><tbody>";

	if($resulttimes = mysqli_query($con,$timesInfo))
	{
		$i = 1;
		while($times = mysqli_fetch_array($resulttimes,MYSQLI_NUM)){
			echo "<tr>";
			echo "<td>".$i++."</td>";
			echo "<td>".$times[0]."</td>";
			echo "<td>".$times[1]."</td>";

			$countInfo = "select count(pid) from mdl_mysql_log where pid=(".$pidInfo.")";
			if($resultCount = mysqli_query($con,$countInfo))
			{
				while($count = mysqli_fetch_array($resultCount,MYSQLI_NUM)){
					if($times[2]==$count[0]){
						$progressColor = "progress-bar-success";
					}else{
						$progressColor = "progress-bar-warning";
					}
					if($times[2]< $count[0]/10){
						$color = "color: Black;";
					}else{
						$color = "color: White;";
					}
					echo "<td><div class='progress'>";
					echo "<div class='progress-bar progress-bar-info progress-bar-striped' role='progressbar' aria-valuenow='".(($times[2]/$count[0])*100)."' aria-valuemin='0' aria-valuemax='100' style='width: ".(($times[2]/$count[0])*100)."%; ".$color."'>".$times[2]."/".$count[0]."</div>";
					echo "</div></td>";
				}
			}
			else
			{
				printf("Error: %s", mysqli_error($con));
				exit();
			}
		}
		echo "</tbody></table></div>";
	}
	else
	{
		printf("Error: %s", mysqli_error($con));
		exit();
	}
}

?>