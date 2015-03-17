<?php 
//start include connecttion file
include("../php/model_connection.php");
include("../php/model_getJsonConnection.php");

//get json connection
$gjc = new getJsonConnection();
$c = $gjc->getConnection('../connection.json');
//get connect to db
$cdb = new connectDB($c['host'],$c['user'],$c['pass'],$c['dbname']);
$cdb->connect();
$con = $cdb->con;
//end include connecttion file

$select = "select * from mdl_mysql_unit";
if($result = mysqli_query($con,$select))
{
	$numrow = mysqli_num_rows($result);
	if($numrow == 0)						//first using mysqlreport 
	{
		include("./setting_step1.php");
	}
	else
	{
		$select = "select * from mdl_mysql_practice where (max_practice_point = 0) OR (question IS NULL)";
		if($result = mysqli_query($con,$select)){
			$numrow = mysqli_num_rows($result);
			if($numrow > 0)						//first using mysqlreport 
			{
				include("./setting_step2.php");		//goto step 2
			}
			else
			{
				include("./assignment.php");
			}
		}
	}
}
else
{
	printf("Error: %s", mysqli_error($con));
	exit();
}

 ?>