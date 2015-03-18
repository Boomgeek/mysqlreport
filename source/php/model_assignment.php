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

if($mode == "saveAnswerChecked")
{
	$aid = $_REQUEST["aid"];
	if(empty($aid)){
		echo "Error: aid was empty";
		exit(0);
	}

	$status = $_REQUEST["status"];
	if(empty($status)){
		echo "Error: status was empty";
		exit(0);
	}

	$point = $_REQUEST["point"];
	if($point<0 || !is_numeric($point)){
		echo "Error: point is not number or point < 0";
		exit(0);
	}

	$comment = $_REQUEST["comment"];
	if(empty($comment)){
		echo "Error: comment was empty";
		exit(0);
	}

	saveAnswerChecked($aid,$status,$point,$comment);
}

function saveAnswerChecked($aid,$status,$point,$comment)
{
	include("./connection.php");
	$con = connection();

	if($comment == "NULL"){
		$update = "update mdl_mysql_answer set status=".$status.",point=".$point.",comment= NULL where aid=".$aid;
	}else{
		$update = "update mdl_mysql_answer set status=".$status.",point=".$point.",comment='".$comment."' where aid=".$aid;
	}

	if($result = mysqli_query($con,$update))
	{
		echo "Success: Update table successful.";
	}
	else
	{
		printf("Error: %s", mysqli_error($con));
		exit();
	}
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
		printf("Error: %s", mysqli_error($con));
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
		printf("Error: %s", mysqli_error($con));
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
		printf("Error: %s", mysqli_error($con));
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
	
	$selectMaxPoint = "select max_practice_point from mdl_mysql_practice where pid=(".$pid.")";
	if($resultMaxPoint = mysqli_query($con,$selectMaxPoint)){
		$maxPoint = mysqli_fetch_array($resultMaxPoint,MYSQLI_NUM);				//select max_practice_point
	}else{
		printf("Question Error: %s", mysqli_error($con));
		exit();
	}

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
				echo "<tbody id='assignmentForm'>";
			}
		}else{
			printf("Question Error: %s", mysqli_error($con));
			exit();
		}

		$num=1;
		while($data = mysqli_fetch_array($result,MYSQLI_NUM))
		{
			//select max_practice_point
			$selectFullName = "select firstname,lastname from mdl_user where username='".$data[1]."'";
			if($resultFullName = mysqli_query($con,$selectFullName)){
				$fullName = mysqli_fetch_array($resultFullName,MYSQLI_NUM);
				$fullName = $fullName[0]." ".$fullName[1];
			}else{
				printf("Select fullname Error: %s", mysqli_error($con));
				exit();
			}
			//select max_practice_point

			echo "<tr>";
			echo "<td>".$num."</td>";
			echo "<td id='aid_".$num."' hidden>".$data[0]."</td>";
			echo "<td>".$data[1]."</td>";
			echo "<td width='150'>".$fullName."</td>";
			echo "<td width='180'>".$data[2]."</td>";
			echo "<td><label><input type='radio' name='status_".$num."' value='1' checked>Correct</label><br> <label><input type='radio' name='status_".$num."' value='2'>Wrong</label></td>";
			echo "<td><input type='number' id='point_".$num."' value='0' min='0' max='".$maxPoint[0]."' step='0.1'><b> / ".$maxPoint[0]."</b>";
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