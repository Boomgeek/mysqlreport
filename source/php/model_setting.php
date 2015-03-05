<?php 

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
$max_practice_while = $_REQUEST["max_practice_while"];
if($max_practice_while < 0){
	echo "Error: max_practice_while was empty";
	exit(0);
}
$max_practice_after = $_REQUEST["max_practice_after"];
if($max_practice_after < 0){
	echo "Error: max_practice_after was empty";
	exit(0);
}

insertUnit($unit,$uname,$max_practice_while,$max_practice_after);

function insertUnit($unit,$uname,$max_practice_while,$max_practice_after)
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
	$insert = "insert into mdl_mysql_unit (unit, uname, max_practice_while, max_practice_after) ";
	$insert .= "values(".$unit.",'".$uname."',".$max_practice_while.",".$max_practice_after.")";
	
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
		$max_practice_while = $row['max_practice_while'];
		$max_practice_after = $row['max_practice_after'];

		for($i=1;$i<=$max_practice_while;$i++)
		{
			$article = $i;
			$type = 1;
			$insert = "insert into mdl_mysql_practice(article,type,uid) ";
			$insert .= "values(".$article.",'".$type."',".$uid.")";
			$result = mysqli_query($con,$insert);
		}

		for($i=1;$i<=$max_practice_after;$i++)
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
?>