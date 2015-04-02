<?php 
$mode = $_REQUEST["mode"];
if(empty($mode)){
	echo "Error: mode was empty";
	exit(0);
}

if($mode == 'unitSetting' || $mode == 'insertUnit')
{
	$unit = $_REQUEST["unit"];
	if(empty($unit)){
		echo "Error: unit was empty";
		exit(0);
	}
	$uname = $_REQUEST["uname"];
	if(empty($uname)){
		echo "Error: uname was empty";
		exit(0);
	}
	$max_in_experiments = $_REQUEST["max_in_experiments"];
	if($max_in_experiments < 0){
		echo "Error: max_in_experiments was empty";
		exit(0);
	}
	$max_post_experiments = $_REQUEST["max_post_experiments"];
	if($max_post_experiments < 0){
		echo "Error: max_post_experiments was empty";
		exit(0);
	}

	insertUnit($unit,$uname,$max_in_experiments,$max_post_experiments);
}

if($mode == 'practiceSetting')
{
	$function = $_REQUEST["function"];
	if(empty($function)){
		echo "Error: function was empty";
		exit(0);
	}

	if($function == 'loadForm')
	{
		loadPracticeForm();
	}

	if($function == 'saveForm')
	{
		$pid = $_REQUEST["pid"];
		if(empty($pid)){
			echo "Error: pid was empty";
			exit(0);
		}

		$max_point = $_REQUEST["max_point"];
		if(empty($max_point)){
			echo "Error: max_point was empty";
			exit(0);
		}

		$question = $_REQUEST["question"];
		if(empty($question)){
			echo "Error: question was empty";
			exit(0);
		}
		savePracticeForm($pid, $max_point, $question);
	}

	if($function == 'saveUnitMaxPoint')
	{
		insertMaxUnitPoint();
	}

}

if($mode == 'callUnitSetting')
{
	callUnitSetting();
}

if($mode == 'deleteUnit')
{
	$unit = $_REQUEST["unit"];
	if(empty($unit)){
		echo "Error: unit was empty";
		exit(0);
	}

	deleteUnit($unit);
}

if($mode == 'updateUnit')
{
	$unit = $_REQUEST["unit"];
	if(empty($unit)){
		echo "Error: unit was empty";
		exit(0);
	}

	$uname = $_REQUEST["uname"];
	if(empty($uname)){
		echo "Error: uname was empty";
		exit(0);
	}

	$max_in_experiments = $_REQUEST["max_in_experiments"];
	if($max_in_experiments < 0){
		echo "Error: max_in_experiments was empty";
		exit(0);
	}
	$max_post_experiments = $_REQUEST["max_post_experiments"];
	if($max_post_experiments < 0){
		echo "Error: max_post_experiments was empty";
		exit(0);
	}

	updateUnit($unit,$uname,$max_in_experiments,$max_post_experiments);
}

if($mode == "callPracticeSetting")
{
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

	callPracticeSetting($unit,$type);
}

if($mode == "updatePractice")
{
	$pid = $_REQUEST["pid"];
	if(empty($pid)){
		echo "Error: pid was empty";
		exit(0);
	}

	$max_practice_point = $_REQUEST["max_practice_point"];
	if(empty($max_practice_point)){
		echo "Error: max_practice_point was empty";
		exit(0);
	}	

	$question = $_REQUEST["question"];
	if(empty($question)){
		echo "Error: question was empty";
		exit(0);
	}

	updatePractice($pid,$max_practice_point,$question);
}
//start function zone
function insertUnit($unit,$uname,$max_in_experiments,$max_post_experiments)
{
	include("./connection.php");
	$con = connection();
	//insert query
	$insert = "insert into mdl_mysql_unit (unit, uname, max_in_experiments, max_post_experiments) ";
	$insert .= "values(".$unit.",'".$uname."',".$max_in_experiments.",".$max_post_experiments.")";
	
	if($result = mysqli_query($con,$insert))
	{
		insertPractice($unit,$con);
		//delete spoint table when insert new unit
		$deleteSpoint = "delete from mdl_mysql_spoint";
		mysqli_query($con,$deleteSpoint);
	}
	else
	{
		printf("Error: %s", mysqli_error($con));
		exit();
	}
}

function insertPractice($unit,$con)
{
	//select query
	$select = "select * from mdl_mysql_unit where unit=".$unit;

	if($result = mysqli_query($con,$select))
	{
		$row=mysqli_fetch_array($result);
		$uid = $row['uid'];
		$max_in_experiments = $row['max_in_experiments'];
		$max_post_experiments = $row['max_post_experiments'];

		for($i=1;$i<=$max_in_experiments;$i++)
		{
			$article = $i;
			$type = 1;
			$insert = "insert into mdl_mysql_practice(article,type,uid) ";
			$insert .= "values(".$article.",'".$type."',".$uid.")";
			$result = mysqli_query($con,$insert);
		}

		for($i=1;$i<=$max_post_experiments;$i++)
		{
			$article = $i;
			$type = 2;
			$insert = "insert into mdl_mysql_practice(article,type,uid) ";
			$insert .= "values(".$article.",'".$type."',".$uid.")";
			$result = mysqli_query($con,$insert);
		}

	}
	else
	{
		printf("Error: %s", mysqli_error($con));
		exit();
	}
}

function loadPracticeForm()
{
	include("./connection.php");
	$con = connection();

	$table1 = "(select pid,uid,type,article from mdl_mysql_practice where (max_practice_point = 0) OR (question IS NULL))";
	$table2 = "(select uid,unit from mdl_mysql_unit)";
	$select = "select pid,unit,type,article from ".$table1." as t1 LEFT JOIN ".$table2." as t2 ON t1.uid=t2.uid ORDER BY unit ASC, type ASC, article ASC"; 
	if($result = mysqli_query($con,$select))
	{
		$num = 1;
		while($data = mysqli_fetch_array($result,MYSQLI_NUM)){
			echo "<tr>";
			echo "<td id='pid_".$num."' hidden>".$data[0]."</td>";
			echo "<td>".$num."</td>";
			echo "<td>".$data[1]."</td>";
			echo "<td>".($data[2] == 1 ? "In experiments" : "Pass experiments")."</td>";
			echo "<td>".$data[3]."</td>";
			echo "<td><input type='number' class='form-control' id='max_point_".$num."' value='1' min='1' step='0.1'></td>";
			echo "<td><textarea id='question_".$num."' rows='2' cols='80'></textarea></td>";
			echo "</tr>";
			$num++;
		}
	}else{
		printf("Question Error: %s", mysqli_error($con));
		exit();
	}
}

function savePracticeForm($pid, $max_point, $question)
{
	include("./connection.php");
	$con = connection();

	$update = "update mdl_mysql_practice set max_practice_point=".$max_point.",question='".$question."' where pid=".$pid;
	if($result = mysqli_query($con,$update))
	{
		//insert max unit point when update practice successful 
		insertMaxUnitPoint($con);
		echo "Success: Update table successful.";
	}
	else
	{
		printf("Error: %s", mysqli_error($con));
		exit();
	}
}

function insertMaxUnitPoint($con)
{
	//include("./connection.php");
	//$con = connection();
	$sumPoint = "select sum(max_practice_point) as max_point,uid from mdl_mysql_practice where uid in (select distinct uid from mdl_mysql_unit  ORDER BY unit ASC) GROUP BY uid";
	$orderUnit = "select distinct unit,uid from mdl_mysql_unit  ORDER BY unit ASC";
	$unitMaxPoint = "select max_point,unit from (".$sumPoint.") as t1 INNER JOIN (".$orderUnit.") as t2 ON t1.uid = t2.uid ORDER BY unit ASC";
	
	if($result = mysqli_query($con,$unitMaxPoint))
	{
		while($data = mysqli_fetch_array($result,MYSQLI_NUM)){
			$update = "update mdl_mysql_unit SET max_unit_point=".$data[0]." where unit=".$data[1];
			if($resultUpdate = mysqli_query($con,$update))
			{
				echo "Success: Update max_unit_point successful.";
			}
			else
			{
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

function callUnitSetting()
{
	include("./connection.php");
	$con = connection();

	$uidInfo = "select unit,uname,max_in_experiments,max_post_experiments from mdl_mysql_unit ORDER BY unit ASC";

	echo "<div class='table-responsive'><table class='table'><thead><tr>";
	echo "<th>Unit</th>";
	echo "<th>Unit Name</th>";
	echo "<th>Max in experiments</th>";
	echo "<th>Max post experiments</th>";
	echo "</tr></thead><tbody id='unitForm'>";

	if($resultUnit = mysqli_query($con,$uidInfo))
	{
		$i = 1;
		while($unit = mysqli_fetch_array($resultUnit,MYSQLI_NUM)){
			echo "<tr class='update-unit'>";
			echo "<td id='update_unit_".$i."'>".$unit[0]."</td>";
			echo "<td><input type='text' id='update_uname_".$i."' class='form-control' value='".$unit[1]."'></td>";
			echo "<td><input type='number' id='update_max_in_experiments_".$i."' class='form-control' value='".$unit[2]."' min='0'></td>";
			echo "<td><input type='number' id='update_max_post_experiments_".$i++."' class='form-control' value='".$unit[3]."' min='0'></td>";
		}
		echo "</tbody></table><button class='btn btn-primary' id='addUnit-btn'><span class='fa fa-plus'></span> Add Unit</button> <button class='btn btn-danger' id='delete-btn'><span class='fa fa-minus'></span> Delete Unit</button></div>";
	}
	else
	{
		printf("Error: %s", mysqli_error($con));
		exit();
	}
}

function deleteUnit($unit)
{
	include("./connection.php");
	$con = connection();

	$uid = "select uid from mdl_mysql_unit where unit=".$unit;
	$deletePractice = "delete from mdl_mysql_practice where uid=(".$uid.")";
	$deleteUnit = "delete from mdl_mysql_unit where unit=".$unit;
	
	if(mysqli_query($con,$deletePractice))
	{
		if(mysqli_query($con,$deleteUnit)){
			echo "Success: Delete unit ".$unit." successful";
			//delete spoint table when delete unit
			$deleteSpoint = "delete from mdl_mysql_spoint";
			mysqli_query($con,$deleteSpoint);
		}else{
			printf("Error: %s", mysqli_error($con));
			exit();
		}
	}
	else
	{
		printf("Error: %s", mysqli_error($con));
		exit();
	}
}

function updateUnit($unit,$uname,$max_in_experiments,$max_post_experiments)
{
	include("./connection.php");
	$con = connection();

	$unitInfo = "select uid,max_in_experiments,max_post_experiments from mdl_mysql_unit where unit=".$unit;
	$resultUnit = mysqli_query($con,$unitInfo);
	$oldUnit = mysqli_fetch_array($resultUnit,MYSQLI_NUM);	

	$update = "update mdl_mysql_unit set uname='".$uname."',max_in_experiments=".$max_in_experiments.",max_post_experiments=".$max_post_experiments." where unit=".$unit;
	if(mysqli_query($con,$update))
	{
		if($max_in_experiments > $oldUnit[1]){
			for($i=$oldUnit[1]+1; $i <= $max_in_experiments; $i++)
			{
				$insertPractice = "insert into mdl_mysql_practice(article,type,uid) values(".$i.",1,".$oldUnit[0].")";
				mysqli_query($con,$insertPractice);
			}
		}else if($max_in_experiments < $oldUnit[1]){
			for($i=$max_in_experiments+1; $i <= $oldUnit[1]; $i++)
			{
				$deletePractice = "delete from mdl_mysql_practice where uid=".$oldUnit[0]." AND type=1 AND article=".$i;
				mysqli_query($con,$deletePractice);
			}
		}

		if($max_post_experiments > $oldUnit[2]){
			for($i=$oldUnit[2]+1; $i <= $max_post_experiments; $i++)
			{
				$insertPractice = "insert into mdl_mysql_practice(article,type,uid) values(".$i.",2,".$oldUnit[0].")";
				mysqli_query($con,$insertPractice);
			}
		}else if($max_post_experiments < $oldUnit[2]){
			for($i=$max_post_experiments+1; $i <= $oldUnit[2]; $i++)
			{
				$deletePractice = "delete from mdl_mysql_practice where uid=".$oldUnit[0]." AND type=2 AND article=".$i;
				mysqli_query($con,$deletePractice);
			}
		}
	}
	else
	{
		printf("Error: %s", mysqli_error($con));
		exit();
	}
	$deleteSpoint = "delete from mdl_mysql_spoint";
	mysqli_query($con,$deleteSpoint);
	//insert max unit point when update unit and delete practice successful 
	insertMaxUnitPoint($con);
	echo "Success: Update Unit ".$unit." successful";
}

function callPracticeSetting($unit,$type)
{
	if($type == 'In Experiments'){
		$type = 1;
	}else if($type == 'Post Experiments'){
		$type = 2;
	}

	include("./connection.php");
	$con = connection();

	$uid = "select uid from mdl_mysql_unit where unit=".$unit;
	$practiceInfo = "select pid,article,max_practice_point,question from mdl_mysql_practice where uid=(".$uid.") AND type=".$type." ORDER BY article ASC";
	
	echo "<div class='table-responsive'><table class='table'><thead><tr>";
	echo "<th>Article</th>";
	echo "<th>Max practice point</th>";
	echo "<th>Question</th>";
	echo "</tr></thead><tbody id='practiceForm'>";

	if($result = mysqli_query($con,$practiceInfo))
	{
		$num = 1;
		while($data = mysqli_fetch_array($result,MYSQLI_NUM)){
			echo "<tr>";
			echo "<td id='pid_".$num."' hidden>".$data[0]."</td>";
			echo "<td>".$data[1]."</td>";
			echo "<td><input type='number' class='form-control' id='max_point_".$num."' value='".$data[2]."' min='1' step='0.1'></td>";
			echo "<td><textarea id='question_".$num."' rows='2' cols='80'>".$data[3]."</textarea></td>";
			echo "</tr>";
			$num++;
		}
		echo "</tbody></table></div>";
	}else{
		printf("Error: %s", mysqli_error($con));
		exit();
	}	
}

function updatePractice($pid,$max_practice_point,$question)
{
	include("./connection.php");
	$con = connection();

	$update = "update mdl_mysql_practice set max_practice_point=".$max_practice_point.", question='".$question."' where pid=".$pid;
	
	if(mysqli_query($con,$update))
	{
		insertMaxUnitPoint($con);								//update MaxUnitPoint when update practice
		echo "Success: Update practice successful.";
	}else{
		printf("Error: %s", mysqli_error($con));
		exit();
	}
}
//end function zone
?>