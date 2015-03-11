<?php 
//check login
if(empty($USER->username)){
	header( "refresh: 0; url=../../login/index.php" );		//redirect to http://localhost/moodle/login/index.php
	exit(0);
}

$mode = $_REQUEST["mode"];
if(empty($mode)){
	echo "Error: Mode was empty";
	exit(0);
}

if($mode == "log")
{
	$code = $_REQUEST["code"];
	if(empty($code))
	{
		echo "Error: code was empty";
		exit(0);
	}	

	$unit = $_REQUEST["unit"];
	if(empty($unit))
	{
		echo "Error: unit was empty";
		exit(0);
	}
	else if(!is_numeric($unit))
	{
		echo "Error: unit is wrong. Unit only is integer.";
		exit(0);
	}	

	$article = $_REQUEST["article"];
	if(empty($article))
	{
		echo "Error: article was empty";
		exit(0);
	}
	else if(!is_numeric($article))
	{
		echo "Error: article is wrong. Article only is integer.";
		exit(0);
	}		

	$type = $_REQUEST["type"];
	if(empty($type))
	{
		echo "Error: type was empty";
		exit(0);
	}
	else if(($type != 1) && ($type != 2))
	{
		echo "Error: type is wrong. Only type is 1(while_exp) or 2(after_exp)";
		exit(0);
	}
	$sid = $USER->username;

	//echo $sid.",".$code.",".$unit.",".$article.",".$type;
	require("./source/php/connection.php");				//run on service.php outsite source folder
	include("./source/php/model_service_log.php");

	$con = connection();			//connection() from connection.php
	//print_r($con);
	$ls = new logService($con,$sid,$code,$unit,$article,$type);
	$ls->pushLog();
}

if($mode == "answer")
{
	$answer = $_REQUEST["answer"];
	if(empty($answer))
	{
		echo "Error: answer was empty";
		exit(0);
	}

	$unit = $_REQUEST["unit"];
	if(empty($unit))
	{
		echo "Error: unit was empty";
		exit(0);
	}
	else if(!is_numeric($unit))
	{
		echo "Error: unit is wrong. Unit only is integer.";
		exit(0);
	}	

	$article = $_REQUEST["article"];
	if(empty($article))
	{
		echo "Error: article was empty";
		exit(0);
	}
	else if(!is_numeric($article))
	{
		echo "Error: article is wrong. Article only is integer.";
		exit(0);
	}		

	$type = $_REQUEST["type"];
	if(empty($type))
	{
		echo "Error: type was empty";
		exit(0);
	}
	else if(($type != 1) && ($type != 2))
	{
		echo "Error: type is wrong. Only type is 1(while_exp) or 2(after_exp)";
		exit(0);
	}

	$sid = $USER->username;

	//echo $sid.",".$code.",".$unit.",".$article.",".$type;
	include("./source/php/connection.php");				//run on service.php outsite source folder
	include("./source/php/model_service_answer.php");

	$con = connection();			//connection() from connection.php
	//print_r($con);
	$as = new answerService($con,$sid,$answer,$unit,$article,$type);
	$as->pushAnswer();
}


//start function zone

//end function zone
?>