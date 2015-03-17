<?php
$mode = $_REQUEST["mode"];
if(empty($mode)){
	echo "Error: mode was empty";
	exit(0);
}

if($mode == 'unit'){
	getUnitDropdown();
}

if($mode == 'type'){

	$unit = $_REQUEST["unit"];
	if(empty($mode)){
		echo "Error: mode was empty";
		exit(0);
	}
	getTypeDropdown($unit);
}

if($mode == 'article'){

	$unit = $_REQUEST["unit"];
	if(empty($unit)){
		echo "Error: unit was empty";
		exit(0);
	}

	$type = $_REQUEST["type"];
	if(empty($type)){
		echo "Error: type was empty";
		exit(0);
	}

	getArticleDropdown($unit,$type);
}

if($mode == 'assignment'){

	$unit = $_REQUEST["unit"];
	if(empty($unit)){
		echo "Error: unit was empty";
		exit(0);
	}

	$type = $_REQUEST["type"];
	if(empty($type)){
		echo "Error: type was empty";
		exit(0);
	}

	$article = $_REQUEST["article"];
	if(empty($article)){
		echo "Error: article was empty";
		exit(0);
	}

	getAssignment($unit,$type,$article);
}

function getUnitDropdown()
{
	include("./connection.php");
	$con = connection();
	$pid = "select distinct pid from mdl_mysql_answer where status=0";
	$uid = "select distinct uid from mdl_mysql_practice where pid in(".$pid.")";
	$select = "select unit from mdl_mysql_unit where uid in(".$uid.") ORDER BY unit ASC";

	if($result = mysqli_query($con,$select))
	{
		while($data = mysqli_fetch_array($result,MYSQLI_NUM))
		{
			echo "<option>".$data[0]."</option>";
		}
		
	}
	else
	{
		printf("Error: %s", mysqli_error($this->con));
		exit();
	}
}

function getTypeDropdown($unit)
{
	include("./connection.php");
	$con = connection();
	$pid = "select distinct pid from mdl_mysql_answer where status=0";
	$uid = "select uid from mdl_mysql_unit where unit=".$unit;
	$select = "select distinct type from mdl_mysql_practice where pid in (".$pid.") and uid in (".$uid.") ORDER BY type ASC";

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
		printf("Error: %s", mysqli_error($this->con));
		exit();
	}
}

function getArticleDropdown($unit,$type)
{
	if($type == 'In Experiments'){
		$type = 1;
	}else if($type == 'Post Experiments'){
		$type = 2;
	}

	include("./connection.php");
	$con = connection();
	$pid = "select distinct pid from mdl_mysql_answer where status=0";
	$uid = "select uid from mdl_mysql_unit where unit=".$unit;
	$select = "select distinct article from mdl_mysql_practice where pid in (".$pid.") and uid in (".$uid.") and type =".$type." ORDER BY article ASC";

	if($result = mysqli_query($con,$select))
	{
		while($data = mysqli_fetch_array($result,MYSQLI_NUM))
		{
			echo "<option>".$data[0]."</option>";
		}
		
	}
	else
	{
		printf("Error: %s", mysqli_error($this->con));
		exit();
	}
}

function getAssignment($unit,$type,$article)
{
	if($type == 'In Experiments'){
		$type = 1;
	}else if($type == 'Post Experiments'){
		$type = 2;
	}

	include("./connection.php");
	$con = connection();
	$uid = "select uid from mdl_mysql_unit where unit=".$unit;
	$pid = "select pid from mdl_mysql_practice where uid=(".$uid.") AND type=".$type." AND article=".$article;
	$select = "select aid,sid,answer from mdl_mysql_answer where pid = (".$pid.") AND status = 0 ORDER BY sid ASC";
	$maxPoint = 1;
	if($result = mysqli_query($con,$select))
	{
		if($type == 1){
			$type = 'In Experiments';
		}else if($type == 2){
			$type = 'Post Experiments';
		}
		$question = "select question from mdl_mysql_practice where pid=(".$pid.")";		//ดึงคำถามมาแสดง
		if($resultQuestion = mysqli_query($con,$question)){
			while($question = mysqli_fetch_array($resultQuestion,MYSQLI_NUM)){
				echo "<div class='panel panel-default'><div class='panel-heading'>";
				echo "<b>คำถาม</b> ".$question[0];
				echo "</div><div class='panel-body'>";
				echo "<div class='table-responsive'><table class='table'>";
				echo "<thead><tr>";
				echo "<th>#</th>";
				echo "<th>SID</th>";
				echo "<th>Name</th>";
				echo "<th>Answer</th>";
				echo "<th>Status</th>";
				echo "<th>Point</th>";
				echo "<th>Commment</th>";
				echo "</tr></thead>";
				echo "<tbody>";
			}
		}else{
			printf("Question Error: %s", mysqli_error($con));
			exit();
		}

		$num=1;
		while($data = mysqli_fetch_array($result,MYSQLI_NUM))
		{
			echo "<tr>";
			echo "<td>".$num."</td>";
			echo "<td id='aid_".$num."' hidden>".$data[0]."</td>";
			echo "<td>".$data[1]."</td>";
			echo "<td width='150'>Mr. Boom</td>";
			echo "<td width='180'>".$data[2]."</td>";
			echo "<td><label><input type='radio' name='status_".$num."' value='1' checked>Correct</label><br> <label><input type='radio' name='status_".$num."' value='2'>Wrong</label></td>";
			echo "<td><input type='number' id='point_".$num."' value='0' min='0' step='0.1'><b> / ".$maxPoint."</b>";
			echo "<td><textarea class='form-control' id='comment_".$num."' rows='2' cols='40'></textarea></td>";
			echo "</tr>";
			$num++;
		}
		echo "</tbody>";
  		echo "</table></div></div></div>";
	}
	else
	{
		printf("Error: %s", mysqli_error($con));
		exit();
	}
}


?>