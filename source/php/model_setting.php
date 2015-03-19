<?php 

$mode = $_REQUEST["mode"];
if(empty($mode)){
	echo "Error: mode was empty";
	exit(0);
}

if($mode == 'unitSetting')
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
		echo "Success: Update table successful.";
	}
	else
	{
		printf("Error: %s", mysqli_error($con));
		exit();
	}
}
//end function zone
?>