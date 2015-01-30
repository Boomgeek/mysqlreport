<?php 
	class getJsonConnection
	{
		function getConnection()
		{
			$str = file_get_contents('./source/connection.json');
			$json = json_decode($str, true);
			$host = $json['connection']['host'];
			$user = $json['connection']['user'];
			$pass = $json['connection']['pass'];
			$dbname = $json['connection']['dbname'];
			$connection = array('host' => $host,'user' => $user,'pass' => $pass,'dbname' => $dbname);
			return $connection;
		}
	}
?>