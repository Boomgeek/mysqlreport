<?php
require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/config.php');
require_once(dirname(dirname(dirname(__FILE__))).'/lib.php');
	
$is_student = user_has_role_assignment($USER->id,5)==0? "0" : "1";
$is_admin = is_siteadmin()==0? "0" : "1";

if($is_admin == 1){
	contentAdmin();
}else if($is_student == 1){
	contentStudent();
}

function contentAdmin(){
	include("../php/connection.php");
	$con = connection();
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
					include("./assignment_teacher.php");
				}
			}
		}
	}
	else
	{
		printf("Error: %s", mysqli_error($con));
		exit();
	}	
}

function contentStudent(){
	include("./assignment_student.php");
}

?>
