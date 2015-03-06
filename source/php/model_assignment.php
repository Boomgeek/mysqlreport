<?php 

	include("../php/model_connection.php");
	include("../php/model_getJsonConnection.php");

	//get json connection
	$gjc = new getJsonConnection();
	$c = $gjc->getConnection('../connection.json');
	//get connect to db
	$cdb = new connectDB($c['host'],$c['user'],$c['pass'],$c['dbname']);
	$cdb->connect();
	$con = $cdb->con;

	$select = "select * from mdl_mysql_answer";
	if($result = mysqli_query($con,$select))
	{
		while($data = mysqli_fetch_array($result,MYSQLI_NUM))
		{
			echo $data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[6],$data[7],$data[8]; 
		}
		
	}
	else
	{
		printf("Error: %s", mysqli_error($this->con));
		exit();
	}

 ?>