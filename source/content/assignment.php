<?php  
require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/config.php');
require_once(dirname(dirname(dirname(__FILE__))).'/lib.php');
	
	$is_student = user_has_role_assignment($USER->id,5)==0? "0" : "1";
	$is_admin = is_siteadmin()==0? "0" : "1";
	//check now user is student
	echo "student :".$is_student."<br>"; // $roleid == 5 for student role //inside functions declare "global $USER;"

	//check now user is admin
	echo "admin: ".$is_admin;
?>
<body>
	<h1 class="page-header">Hello Assignment</h1>
	<div class="form-group">
		<div class="form-inline">
			<label>Unit:</label>
     		<select id="unit-Filter" class="form-control"></select>
		</div>
	</div>
	<div class="form-group">
		<div class="form-inline">
			<label>Type of practice:</label>
     		<select id="type-Filter" class="form-control"></select>
		</div>
	</div>
	<div class="form-group">
		<div class="form-inline">
			<label>Article:</label>
     		<select id="article-Filter" class="form-control"></select>
		</div>
	</div>
	<div id="assignment-Content"></div>
	<button type="button" id="saveAssignment" class="btn btn-primary btn-lg center-block">Save</button>
</body>
<script src="./source/js/content_assignment.js"></script>

