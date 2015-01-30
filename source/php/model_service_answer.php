<?php 
	
	class answerService
	{
		var $con,$sid,$answer,$unit,$article,$type;

		function __construct($con,$sid,$answer,$unit,$article,$type)
		{
			$this->con = $con;
			$this->sid = $sid;
			$this->answer = $answer;
			$this->unit = $unit;
			$this->article = $article;
			$this->type = $type;
		}

		function pushAnswer()
		{
			$pid = $this->getPID();
			$sid = $this->sid;
			$answer = $this->answer;
			$insert = "insert into mdl_mysql_answer (sid,pid,answer) values ('".$sid."',".$pid.",'".$answer."')";

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