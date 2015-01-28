<?php 
//check login
if(empty($USER->username)){
	header( "refresh: 0; url=../../login/index.php" );		//redirect to http://localhost/moodle/login/index.php
	exit(0);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Hello Dashboard</title>
</head>
<body>
	<h1>Hello Dashboard</h1>
</body>
</html>