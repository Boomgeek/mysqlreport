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
		//
	}
}

//start function zone
function insertUnit($unit,$uname,$max_in_experiments,$max_post_experiments)
{
	include("./model_connection.php");
	include("./model_getJsonConnection.php");

	//get json connection
	$gjc = new getJsonConnection();
	$c = $gjc->getConnection('../connection.json');
	//get connect to db
	$cdb = new connectDB($c['host'],$c['user'],$c['pass'],$c['dbname']);
	$cdb->connect();
	$con = $cdb->con;
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
	echo "<tr>";
	echo "<td>1</td>";
	echo "<td>1</td>";
	echo "<td>In experiment</td>";
	echo "<td>1</td>";
	echo "<td><input type='number' class='form-control' id='max_post_experiments1' value='0' min='0' step='0.1'></td>";
	echo "<td><textarea rows='2' cols='80'></textarea></td>";
	echo "</tr>";	
}
//end function zone
?>