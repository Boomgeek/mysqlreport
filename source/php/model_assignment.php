<?php
$mode = $_REQUEST["mode"];
if(empty($mode)){
	echo "Error: mode was empty";
	exit(0);
}

if($mode == 'unit'){

	$status = $_REQUEST["status"];
	if($status != 0 && $status != 1){
		echo "Error: status is not 0 or 1";
		exit(0);
	}
	getUnitDropdown($status);
}

if($mode == 'type'){

	$status = $_REQUEST["status"];
	if($status != 0 && $status != 1){
		echo "Error: status is not 0 or 1";
		exit(0);
	}

	$unit = $_REQUEST["unit"];
	if(empty($mode)){
		echo "Error: unit was empty";
		exit(0);
	}
	getTypeDropdown($status,$unit);
}

if($mode == 'article'){

	$status = $_REQUEST["status"];
	if($status != 0 && $status != 1){
		echo "Error: status is not 0 or 1";
		exit(0);
	}

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

	getArticleDropdown($status,$unit,$type);
}

if($mode=="unitStudent")
{
	$status = 1;
	getUnitDropdownStudent($status);
}

if($mode=="typeStudent")
{
	$status = 1;
	$unit = $_REQUEST["unit"];
	if(empty($mode)){
		echo "Error: unit was empty";
		exit(0);
	}
	getTypeDropdownStudent($status,$unit);
}

if($mode == 'assignment'){

	$status = $_REQUEST["status"];
	if($status != 0 && $status != 1){
		echo "Error: status is not 0 or 1";
		exit(0);
	}

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

	if($unit == "null" || $type == "null" || $article == "null"){
		if($status == 0){
			echo "Assignment is empty:Students not submitted";
			exit(0);
		}else if($status == 1){
			echo "Assignment is empty:No assignments checked";
			exit(0);
		}
	}else{
		if($status == 0){
			getAssignmentUnchecked($status,$unit,$type,$article);
		}else if($status == 1){
			getAssignmentChecked($status,$unit,$type,$article);
		}
	}
}

if($mode == "saveAnswerChecked"){

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

if($mode == "assignmentStudent"){
	$status = 1;		//assignment checked for student

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
	if($unit == "null" || $type == "null"){
		echo "Assignment is empty:No assignments checked";
		exit(0);
	}else{
		assignmentStudent($status,$unit,$type);
	}
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

function getUnitDropdown($status)
{
	include("./connection.php");
	$con = connection();
	$pid = "select distinct pid from mdl_mysql_answer where status=".$status;
	$uid = "select distinct uid from mdl_mysql_practice where pid in(".$pid.")";
	$select = "select unit,uname from mdl_mysql_unit where uid in(".$uid.") ORDER BY unit ASC";

	if($result = mysqli_query($con,$select))
	{
		while($data = mysqli_fetch_array($result,MYSQLI_NUM))
		{
			$sizeUname = mb_strlen($data[1],'UTF-8');
			if($sizeUname > 15){
				$uname = mb_substr($data[1], 0, 18,'UTF-8')."...";
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

function getTypeDropdown($status,$unit)
{
	include("./connection.php");
	$con = connection();
	$pid = "select distinct pid from mdl_mysql_answer where status=".$status;
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

function getArticleDropdown($status,$unit,$type)
{
	if($type == 'In Experiments'){
		$type = 1;
	}else if($type == 'Post Experiments'){
		$type = 2;
	}

	include("./connection.php");
	$con = connection();
	$pid = "select distinct pid from mdl_mysql_answer where status=".$status;
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

function getUnitDropdownStudent($status)
{
	//start require for use USER object of Moodle
	require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/config.php');
	require_once(dirname(dirname(dirname(__FILE__))).'/lib.php');
	$sid = $USER->username;
	//end require for use USER object of Moodle

	include("./connection.php");
	$con = connection();
	$pid = "select distinct pid from mdl_mysql_answer where status=".$status." AND sid='".$sid."'";
	$uid = "select distinct uid from mdl_mysql_practice where pid in(".$pid.")";
	$select = "select unit,uname from mdl_mysql_unit where uid in(".$uid.") ORDER BY unit ASC";

	if($result = mysqli_query($con,$select))
	{
		while($data = mysqli_fetch_array($result,MYSQLI_NUM))
		{
			$sizeUname = mb_strlen($data[1],'UTF-8');
			if($sizeUname > 15){
				$uname = mb_substr($data[1], 0, 18,'UTF-8')."...";
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

function getTypeDropdownStudent($status,$unit)
{
	//start require for use USER object of Moodle
	require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/config.php');
	require_once(dirname(dirname(dirname(__FILE__))).'/lib.php');
	$sid = $USER->username;
	//end require for use USER object of Moodle

	include("./connection.php");
	$con = connection();
	$pid = "select distinct pid from mdl_mysql_answer where status=".$status." AND sid='".$sid."'";
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

function getAssignmentUnchecked($status,$unit,$type,$article)
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
	$select = "select aid,sid,answer from mdl_mysql_answer where pid = (".$pid.") AND status =".$status." ORDER BY sid ASC";
	
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
				echo "<div class='panel panel-danger'><div class='panel-heading'>";
				echo "<b>คำถาม</b> ".$question[0];
				echo "</div><div class='panel-body'>";
				echo "<div class='table-responsive'><table class='table'>";
				echo "<thead><tr>";
				echo "<th>#</th>";
				echo "<th>SID</th>";
				echo "<th>Name</th>";
				echo "<th>Answer</th>";
				echo "<th>Point</th>";
				echo "<th>Commment</th>";
				echo "<th>Status</th>";
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
			echo "<td id='no_".$num."'>".$num."</td>";
			echo "<td id='aid_".$num."' hidden>".$data[0]."</td>";
			echo "<td>".$data[1]."</td>";
			echo "<td width='150'>".$fullName."</td>";
			echo "<td width='180'>".$data[2]."</td>";
			echo "<td><input type='number' id='point_".$num."' value='0' min='0' max='".$maxPoint[0]."' step='0.1'><b> / ".$maxPoint[0]."</b>";
			echo "<td><textarea class='form-control' id='comment_".$num."' rows='2' cols='40'></textarea></td>";
			echo "<td><button class='btn btn-success status-btn' id='status_".$num."'><i class='fa fa-check-circle'></i> Checked</button></td>";
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

function getAssignmentChecked($status,$unit,$type,$article)
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
	$select = "select aid,sid,answer,point,comment from mdl_mysql_answer where pid = (".$pid.") AND status =".$status." ORDER BY sid ASC";
	
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
				echo "<div class='panel panel-success'><div class='panel-heading'>";
				echo "<b>คำถาม</b> ".$question[0];
				echo "</div><div class='panel-body'>";
				echo "<div class='table-responsive'><table class='table'>";
				echo "<thead><tr>";
				echo "<th>#</th>";
				echo "<th>SID</th>";
				echo "<th>Name</th>";
				echo "<th>Answer</th>";
				echo "<th>Point</th>";
				echo "<th>Commment</th>";
				echo "<th>Status</th>";
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
			echo "<td id='no_".$num."'>".$num."</td>";
			echo "<td id='aid_".$num."' hidden>".$data[0]."</td>";
			echo "<td>".$data[1]."</td>";
			echo "<td width='150'>".$fullName."</td>";
			echo "<td width='180'>".$data[2]."</td>";
			echo "<td><input type='number' id='point_".$num."' value='".$data[3]."' min='0' max='".$maxPoint[0]."' step='0.1'><b> / ".$maxPoint[0]."</b>";
			echo "<td><textarea class='form-control' id='comment_".$num."' rows='2' cols='40'>".$data[4]."</textarea></td>";
			echo "<td><button class='btn btn-success status-btn' id='status_".$num."'><i class='fa fa-check-circle'></i> Checked</button></td>";
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

function assignmentStudent($status,$unit,$type)
{
	//start require for use USER object of Moodle
	require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/config.php');
	require_once(dirname(dirname(dirname(__FILE__))).'/lib.php');
	$sid = $USER->username;
	//end require for use USER object of Moodle

	if($type == 'In Experiments'){
		$type = 1;
	}else if($type == 'Post Experiments'){
		$type = 2;
	}

	include("./connection.php");
	$con = connection();
	$uid = "select uid from mdl_mysql_unit where unit=".$unit;
	$pid = "select pid from mdl_mysql_practice where uid=(".$uid.") AND type=".$type;
	$select = "select pid,answer,point,comment from mdl_mysql_answer where pid in (".$pid.") AND status =".$status." AND sid='".$sid."' ORDER BY sid ASC";
	
	if($result = mysqli_query($con,$select))
	{
		$uname = "select uname from mdl_mysql_unit where unit=".$unit;
		$resultUname = mysqli_query($con,$uname);
		$uname = mysqli_fetch_array($resultUname,MYSQLI_NUM);

		echo "<div class='panel panel-primary'><div class='panel-heading'>";
		echo "<b>Unit ".$unit." ".$uname[0]."</b> ";
		echo "</div><div class='panel-body'>";
		echo "<div class='table-responsive'><table class='table'>";
		echo "<thead><tr>";
		echo "<th>Article</th>";
		echo "<th>Question</th>";
		echo "<th>Answer</th>";
		echo "<th>Point</th>";
		echo "<th>Commment</th>";
		echo "</tr></thead>";
		echo "<tbody id='assignmentForm'>";

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

			$selectInformation = "select question,max_practice_point,article from mdl_mysql_practice where pid=(".$data[0].")";		//ดึงคำถามมาแสดง
			if($resultInformation= mysqli_query($con,$selectInformation)){
				$information = mysqli_fetch_array($resultInformation,MYSQLI_NUM);
				$question = $information[0];
				$maxPoint = $information[1];
				$article = $information[2];
			}else{
				printf("Information Error: %s", mysqli_error($con));
				exit();
			}
			$answer = $data[1];
			$point = $data[2];
			$comment = $data[3];

			echo "<tr>";
			echo "<td>".$article."</td>";
			echo "<td>".$question."</td>";
			echo "<td width='150'>".$answer."</td>";
			echo "<td><b>".$point." / ".$maxPoint[0]."</b>";
			echo "<td>".$comment."</td>";
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