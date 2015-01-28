<?php 
	
	class logService
	{
		var $con,$sid,$code,$unit,$article,$type;

		function __construct($con,$sid,$code,$unit,$article,$type)
		{
			$this->con = $con;
			$this->sid = $sid;
			$this->code = $code;
			$this->unit = $unit;
			$this->article = $article;
			$this->type = $type;
		}

		function pushLog()
		{
			$pid = $this->getPID();
			$sid = $this->sid;
			$code = $this->code;
			$insert = "insert into mdl_mysql_log (sid,pid,code) values ('".$sid."',".$pid.",'".$code."')";

			if($result = mysqli_query($this->con,$insert))
			{
				echo "insert successful.";
			}
			else
			{
				printf("Error: %s", mysqli_error($this->con));
				exit();
			}
		} 

		private function getPID()
		{
			$uid = $this->getUID();
			$select = "select pid from mdl_mysql_practice where article=".$this->article." and type='".$this->type."' and uid=".$uid;
			//echo $select;
			if($result = mysqli_query($this->con,$select))
			{
				while($data = mysqli_fetch_array($result,MYSQLI_NUM)) 
				{
					return $data[0];
				}
			}
			else
			{
				printf("Error: %s", mysqli_error($this->con));
				exit();
			}
		}

		private function getUID()
		{
			$select = "select * from mdl_mysql_unit where unit=".$this->unit;
			//echo $select;
			if($result = mysqli_query($this->con,$select))
			{
				while($data = mysqli_fetch_array($result,MYSQLI_NUM))
				{
					return $data[0];
				}
			}
			else
			{
				printf("Error: %s", mysqli_error($this->con));
				exit();
			}
		}


	}

?>